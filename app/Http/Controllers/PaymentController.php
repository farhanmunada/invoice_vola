<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function destroy(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $invoice = $payment->invoice;
            
            // 1. Delete the payment
            $payment->delete();

            // 2. Recalculate Invoice Data
            $totalPayments = $invoice->payments()->sum('amount');
            $newRemaining = $invoice->total_amount - $totalPayments;

            // 3. Determine new Status
            $status = 'pending';
            if ($newRemaining <= 0) {
                $status = 'paid';
                $newRemaining = 0;
            } elseif ($totalPayments > 0) {
                $status = 'partially_paid';
            }

            // 4. Update Invoice
            $invoice->update([
                'dp_amount' => $totalPayments,
                'remaining_amount' => $newRemaining,
                'status' => $status,
            ]);
        });

        return back()->with('status', 'Payment deleted and invoice updated successfully.');
    }
}
