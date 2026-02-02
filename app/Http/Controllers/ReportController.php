<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::where('status', 'paid');

        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'daily':
                    $query->whereDate('date', Carbon::today());
                    break;
                case 'weekly':
                    $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'monthly':
                    $month = $request->input('month', Carbon::now()->month);
                    $year = $request->input('year', Carbon::now()->year);
                    $query->whereMonth('date', $month)->whereYear('date', $year);
                    break;
                case 'yearly':
                    $year = $request->input('year', Carbon::now()->year);
                    $query->whereYear('date', $year);
                    break;
                case 'custom':
                    if ($request->has('start_date') && $request->has('end_date')) {
                        $query->whereBetween('date', [$request->start_date, $request->end_date]);
                    }
                    break;
            }
        }

        $invoices = $query->latest('date')->get();
        $totalAmount = $invoices->sum('total_amount');

        return view('reports.index', compact('invoices', 'totalAmount'));
    }
}
