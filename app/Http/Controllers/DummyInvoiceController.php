<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DummyInvoiceController extends Controller
{
    public function create()
    {
        // 1. Get real customers for convenience (optional, or just text input)
        $customers = Customer::orderBy('name')->get();

        // 2. Generate a Dummy Invoice Number Suggestion
        $date = now()->format('Ymd');
        $randomSeq = rand(1000, 9999);
        $invoiceNumber = 'inv/' . $date . '/' . $randomSeq; // Small 'inv' to distinguish

        return view('dummy.create', compact('customers', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        // 1. Validate Input (Similar to Real Invoice)
        $validated = $request->validate([
            'customer_name' => 'required|string', // Manual input or selected
            'customer_phone' => 'nullable|string',
            'customer_address' => 'nullable|string',
            'date' => 'required|date',
            'invoice_number' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_type' => 'required|string',
            'items.*.specifications' => 'nullable|array',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount_type' => 'required|in:nominal,percent',
            'discount_value' => 'required|numeric|min:0',
            'dp_amount' => 'required|numeric|min:0',
        ]);

        // 2. Calculate Totals (In-Memory Only)
        $subtotal = 0;
        $items = [];
        
        foreach ($validated['items'] as $item) {
            $lineTotal = $item['quantity'] * $item['price'];
            $subtotal += $lineTotal;
            
            // Transform to object-like structure for view
            $items[] = (object) [
                'product_type' => $item['product_type'],
                'specifications' => $item['specifications'] ?? [],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $lineTotal,
            ];
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
            $remainingAmount = 0;
        } elseif ($validated['dp_amount'] > 0) {
            $status = 'partially_paid';
        }

        // 3. Construct "Fake" Invoice Object
        $invoice = (object) [
            'invoice_number' => $validated['invoice_number'],
            'date' => Carbon::parse($validated['date']),
            'status' => $status,
            'subtotal' => $subtotal,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'total_amount' => $totalAmount,
            'dp_amount' => $validated['dp_amount'],
            'remaining_amount' => $remainingAmount,
            'customer' => (object) [
                'name' => $validated['customer_name'],
                'phone' => $validated['customer_phone'] ?? '-',
                'address' => $validated['customer_address'] ?? '-',
            ],
            'items' => $items,
        ];

        // 4. Render Print View Directly
        $setting = Setting::first();
        return view('dummy.print', compact('invoice', 'setting'));
    }
}
