<?php

namespace App\Http\Controllers;

use App\Models\Spk;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\DeliveryNote;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Date filter (default to current month)
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;
        $monthLabel = \DateTime::createFromFormat('!m', $month)->format('F') . ' ' . $year;

        // Base date query
        $dateFilter = function ($query) use ($month, $year) {
            return $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        };

        // Stats based on selected month
        $stats = [
            'total_spk' => Spk::whereMonth('created_at', $month)->whereYear('created_at', $year)->count(),
            'pending_spk' => Spk::pending()->whereMonth('created_at', $month)->whereYear('created_at', $year)->count(),
            'in_progress_spk' => Spk::inProgress()->whereMonth('created_at', $month)->whereYear('created_at', $year)->count(),
            'completed_spk' => Spk::completed()->whereMonth('completed_at', $month)->whereYear('completed_at', $year)->count(),
            'low_stock_products' => Product::active()->lowStock()->count(),
            'total_products' => Product::active()->count(),
        ];

        // Invoice stats
        $stats['total_invoices'] = Invoice::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
        $stats['unpaid_invoices'] = Invoice::whereIn('status', ['draft', 'sent', 'partial'])
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();

        // Finance-specific stats
        $stats['overdue_invoices'] = Invoice::whereIn('status', ['draft', 'sent', 'partial'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->count();
        $stats['due_soon_invoices'] = Invoice::whereIn('status', ['draft', 'sent', 'partial'])
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->count();
        $stats['completed_deliveries'] = DeliveryNote::where('status', 'delivered')
            ->whereMonth('updated_at', $month)
            ->whereYear('updated_at', $year)
            ->count();

        // Recent SPKs for selected month
        $recentSpks = Spk::with(['creator', 'approver'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->take(5)
            ->get();

        // Low stock alerts (not filtered by date)
        $lowStockProducts = Product::active()
            ->lowStock()
            ->with('category')
            ->take(5)
            ->get();

        // Invoices approaching due date (for Finance dashboard)
        $upcomingDueInvoices = Invoice::with('customer')
            ->whereIn('status', ['draft', 'sent', 'partial'])
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now()->subDays(3))
            ->where('due_date', '<=', now()->addDays(7))
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Recent completed delivery notes
        $recentDeliveries = DeliveryNote::with('customer')
            ->where('status', 'delivered')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'recentSpks',
            'lowStockProducts',
            'upcomingDueInvoices',
            'recentDeliveries',
            'month',
            'year',
            'monthLabel'
        ));
    }
}

