<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'items.product'])
            ->whereIn('status', ['sent', 'paid', 'partial']);

        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        if ($request->filled('month')) {
            $query->whereMonth('invoice_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('invoice_date', $request->year);
        }

        $invoices = $query->latest('invoice_date')->paginate(15)->withQueryString();

        // Summary
        $summary = [
            'total_sales' => Invoice::whereIn('status', ['sent', 'paid', 'partial'])->sum('total'),
            'paid_amount' => Invoice::where('status', 'paid')->sum('total'),
            'pending_amount' => Invoice::whereIn('status', ['sent', 'partial'])->sum('total'),
            'invoice_count' => Invoice::whereIn('status', ['sent', 'paid', 'partial'])->count(),
        ];

        // Monthly sales for chart
        $monthlySales = Invoice::where('status', 'paid')
            ->whereYear('invoice_date', $request->year ?? now()->year)
            ->select(
                DB::raw('MONTH(invoice_date) as month'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $salesChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $salesChart[$i] = $monthlySales[$i] ?? 0;
        }

        return view('sales.index', compact('invoices', 'summary', 'salesChart'));
    }
}
