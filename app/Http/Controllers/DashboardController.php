<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
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

        // 6. Chart Data Logic
        $filter = $request->input('filter', 'daily'); // Default to daily
        $chartLabels = [];
        $chartData = [];

        if ($filter === 'monthly') {
            // Monthly Logic (Current Year)
            $startOfYear = Carbon::now()->startOfYear();
            $endOfYear = Carbon::now()->endOfYear();

            $invoices = Invoice::where('status', 'paid')
                ->whereBetween('date', [$startOfYear, $endOfYear])
                ->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->date)->format('M'); // Jan, Feb...
                });
            
            // Fill all 12 months to ensure continuity
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            foreach ($months as $month) {
                $chartLabels[] = $month;
                $chartData[] = isset($invoices[$month]) ? $invoices[$month]->sum('total_amount') : 0;
            }

        } else {
            // Daily Logic (Last 30 Days)
            $startDate = Carbon::today()->subDays(29);
            $endDate = Carbon::today();

            $invoices = Invoice::where('status', 'paid')
                ->whereBetween('date', [$startDate, $endDate])
                ->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->date)->format('d M');
                });

            // Loop through last 30 days
            for ($i = 0; $i < 30; $i++) {
                $date = $startDate->copy()->addDays($i);
                $key = $date->format('d M');
                $chartLabels[] = $key;
                $chartData[] = isset($invoices[$key]) ? $invoices[$key]->sum('total_amount') : 0;
            }
        }

        return view('dashboard', compact(
            'todayIncome', 
            'monthlyIncome', 
            'unpaidAmount', 
            'pendingCount',
            'partialCount',
            'paidCount',
            'recentInvoices',
            'chartLabels',
            'chartData',
            'filter'
        ));
    }
}
