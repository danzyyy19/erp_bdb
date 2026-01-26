<?php

namespace App\Http\Controllers;

use App\Models\Spk;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function production(Request $request)
    {
        $year = $request->year ?? now()->year;
        $month = $request->month;

        $query = Spk::with(['items.product'])
            ->where('status', 'completed')
            ->whereYear('completed_at', $year);

        if ($month) {
            $query->whereMonth('completed_at', $month);
        }

        $spks = $query->latest('completed_at')->get();

        // Monthly production summary
        $monthlyData = Spk::where('status', 'completed')
            ->whereYear('completed_at', $year)
            ->select(
                DB::raw('MONTH(completed_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $productionChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $productionChart[$i] = $monthlyData[$i] ?? 0;
        }

        return view('reports.production', compact('spks', 'productionChart', 'year', 'month'));
    }

    public function sales(Request $request)
    {
        $year = $request->year ?? now()->year;
        $month = $request->month;

        $query = Invoice::with(['customer', 'items.product'])
            ->where('status', 'paid')
            ->whereYear('invoice_date', $year);

        if ($month) {
            $query->whereMonth('invoice_date', $month);
        }

        $invoices = $query->latest('invoice_date')->get();

        // Summary
        $summary = [
            'total_revenue' => $invoices->sum('total'),
            'invoice_count' => $invoices->count(),
            'average_invoice' => $invoices->count() > 0 ? $invoices->sum('total') / $invoices->count() : 0,
        ];

        // Monthly sales data
        $monthlyData = Invoice::where('status', 'paid')
            ->whereYear('invoice_date', $year)
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
            $salesChart[$i] = $monthlyData[$i] ?? 0;
        }

        return view('reports.sales', compact('invoices', 'summary', 'salesChart', 'year', 'month'));
    }

    public function inventory(Request $request)
    {
        $categoryType = $request->category_type;

        $query = Product::with('category')->active();

        if ($categoryType) {
            $query->whereHas('category', fn($q) => $q->where('type', $categoryType));
        }

        $products = $query->orderBy('current_stock')->get();

        // Category summaries
        $categorySummary = [
            'bahan_baku' => [
                'count' => Product::bahanBaku()->active()->count(),
                'low_stock' => Product::bahanBaku()->active()->lowStock()->count(),
                'total_stock' => Product::bahanBaku()->active()->sum('current_stock'),
            ],
            'packaging' => [
                'count' => Product::packaging()->active()->count(),
                'low_stock' => Product::packaging()->active()->lowStock()->count(),
                'total_stock' => Product::packaging()->active()->sum('current_stock'),
            ],
            'barang_jadi' => [
                'count' => Product::barangJadi()->active()->count(),
                'low_stock' => Product::barangJadi()->active()->lowStock()->count(),
                'total_stock' => Product::barangJadi()->active()->sum('current_stock'),
            ],
        ];

        // Low stock products
        $lowStockProducts = Product::active()->lowStock()->with('category')->get();

        return view('reports.inventory', compact('products', 'categorySummary', 'lowStockProducts', 'categoryType'));
    }
}
