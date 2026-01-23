<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // 1. Income Today (Omzet Hari Ini) - Based on Payments received today
        $todayIncome = Payment::whereDate('paid_at', Carbon::today())->sum('amount');

        // 2. Monthly Income (Omzet Bulan Ini)
        $monthlyIncome = Payment::whereMonth('paid_at', Carbon::now()->month)
                                ->whereYear('paid_at', Carbon::now()->year)
                                ->sum('amount');

        // 3. Unpaid Invoices Amount (Piutang)
        $unpaidAmount = Invoice::where('remaining_amount', '>', 0)->sum('remaining_amount');

        // 4. Invoices Count Status (Production Status proxy)
        $pendingCount = Invoice::where('status', 'pending')->count();
        $partialCount = Invoice::where('status', 'partially_paid')->count();
        $paidCount    = Invoice::where('status', 'paid')->count();

        // 5. Recent Invoices
        $recentInvoices = Invoice::with('customer')->latest()->take(5)->get();

        return view('dashboard', compact(
            'todayIncome', 
            'monthlyIncome', 
            'unpaidAmount', 
            'pendingCount',
            'partialCount',
            'paidCount',
            'recentInvoices'
        ));
    }
}
