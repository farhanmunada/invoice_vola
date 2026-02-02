<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('customer')->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('invoice_number', 'like', "%{$search}%");
        }

        $invoices = $query->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        // Auto-generate invoice number: INV/YYYYMMDD/XXXX
        $date = now()->format('Ymd');
        $lastInvoice = Invoice::whereDate('created_at', today())->latest()->first();
        $sequence = $lastInvoice ? intval(substr($lastInvoice->invoice_number, -4)) + 1 : 1;
        $invoiceNumber = 'INV/' . $date . '/' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return view('invoices.create', compact('customers', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'items' => 'required|array|min:1',
            'items.*.product_type' => 'required|string',
            'items.*.specifications' => 'nullable|array',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:nominal,percent',
            'discount_value' => 'required|numeric|min:0',
            'dp_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
             // 1. Calculate Totals
             $subtotal = 0;
             foreach ($validated['items'] as $item) {
                 $subtotal += $item['quantity'] * $item['price'];
             }

             $discountAmount = 0;
             if ($validated['discount_type'] === 'nominal') {
                 $discountAmount = $validated['discount_value'];
             } else {
                 $discountAmount = ($subtotal * $validated['discount_value']) / 100;
             }

             $totalAmount = $subtotal - $discountAmount;
             $remainingAmount = $totalAmount - $validated['dp_amount'];
             
             $status = 'pending';
             if ($remainingAmount <= 0) {
                 $status = 'paid';
                 $remainingAmount = 0; // Prevent negative remaining
             } elseif ($validated['dp_amount'] > 0) {
                 $status = 'partially_paid';
             }

             // 2. Create Invoice
             $invoice = Invoice::create([
                 'customer_id' => $validated['customer_id'],
                 'invoice_number' => $validated['invoice_number'],
                 'date' => $validated['date'],
                 'status' => $status,
                 'subtotal' => $subtotal,
                 'discount_type' => $validated['discount_type'],
                 'discount_value' => $validated['discount_value'],
                 'total_amount' => $totalAmount,
                 'dp_amount' => $validated['dp_amount'],
                 'remaining_amount' => $remainingAmount,
             ]);

             // 3. Create Items
             foreach ($validated['items'] as $item) {
                 $invoice->items()->create([
                     'product_type' => $item['product_type'],
                     'specifications' => $item['specifications'] ?? [],
                     'quantity' => $item['quantity'],
                     'price' => $item['price'],
                     'total' => $item['quantity'] * $item['price'],
                 ]);
             }

             // 4. Create Payment Record if DP > 0 or Full Payment
             if ($validated['dp_amount'] > 0) {
                 Payment::create([
                     'invoice_id' => $invoice->id,
                     'amount' => $validated['dp_amount'],
                     'method' => $validated['payment_method'] ?? 'cash',
                     'paid_at' => now(),
                 ]);
             }
        });

        return redirect()->route('invoices.index')->with('status', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function print(Invoice $invoice)
    {
        $setting = \App\Models\Setting::first();
        return view('invoices.print', compact('invoice', 'setting'));
    }

    public function addPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $invoice->remaining_amount,
            'method' => 'required|string',
        ]);

        DB::transaction(function () use ($validated, $invoice) {
            // 1. Create Payment
            Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $validated['amount'],
                'method' => $validated['method'],
                'paid_at' => now(),
            ]);

            // 2. Update Invoice Logic
            $newDpAmount = $invoice->dp_amount + $validated['amount'];
            $newRemaining = $invoice->total_amount - $newDpAmount;

            $status = $invoice->status;
            if ($newRemaining <= 0) {
                $status = 'paid';
                $newRemaining = 0;
            } else {
                $status = 'partially_paid';
            }

            $invoice->update([
                'dp_amount' => $newDpAmount,
                'remaining_amount' => $newRemaining,
                'status' => $status,
            ]);
        });

        return back()->with('status', 'Payment added successfully.');
    }
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('status', 'Invoice deleted successfully.');
    }
}
