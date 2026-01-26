<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Exports\StockMovementsExport;
use App\Exports\SpkExport;
use App\Exports\InvoicesExport;
use App\Exports\CustomersExport;
use App\Exports\FpbExport;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Spk;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Fpb;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    /**
     * Export Products to Excel
     */
    public function productsExcel(Request $request)
    {
        $categoryType = $request->category_type;
        $showPrices = auth()->user()->isOwner() || auth()->user()->isFinance();

        $filename = 'products';
        if ($categoryType) {
            $filename = $categoryType === 'bahan_baku' ? 'bahan-baku' : ($categoryType === 'packaging' ? 'packaging' : 'barang-jadi');
        }

        return Excel::download(
            new ProductsExport($categoryType, $showPrices),
            $filename . '-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export Products to PDF
     */
    public function productsPdf(Request $request)
    {
        $categoryType = $request->category_type;
        $showPrices = auth()->user()->isOwner() || auth()->user()->isFinance();

        $query = Product::with('category')->active();
        if ($categoryType) {
            $query->whereHas('category', fn($q) => $q->where('type', $categoryType));
        }
        $products = $query->orderBy('code')->get();

        $title = 'Daftar Produk';
        if ($categoryType === 'bahan_baku')
            $title = 'Daftar Bahan Baku';
        if ($categoryType === 'packaging')
            $title = 'Daftar Material/Packaging';
        if ($categoryType === 'barang_jadi')
            $title = 'Daftar Barang Jadi';

        $pdf = Pdf::loadView('exports.products-pdf', compact('products', 'showPrices', 'title'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('products-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Stock Movements to Excel
     */
    public function stockMovementsExcel(Request $request)
    {
        return Excel::download(
            new StockMovementsExport($request->all()),
            'stock-movements-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export Stock Movements to PDF
     */
    public function stockMovementsPdf(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('category_type')) {
            $query->whereHas(
                'product',
                fn($q) =>
                $q->whereHas('category', fn($c) => $c->where('type', $request->category_type))
            );
        }

        $movements = $query->orderBy('created_at', 'desc')->limit(500)->get();

        $pdf = Pdf::loadView('exports.stock-movements-pdf', compact('movements'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('stock-movements-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export SPK to Excel
     */
    public function spkExcel(Request $request)
    {
        return Excel::download(
            new SpkExport($request->all()),
            'spk-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export SPK to PDF
     */
    public function spkPdf(Request $request)
    {
        $query = Spk::with(['creator', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $spks = $query->orderBy('created_at', 'desc')->limit(500)->get();

        $pdf = Pdf::loadView('exports.spk-pdf', compact('spks'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('spk-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Invoices to Excel
     */
    public function invoicesExcel(Request $request)
    {
        return Excel::download(
            new InvoicesExport($request->all()),
            'invoices-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export Invoices to PDF
     */
    public function invoicesPdf(Request $request)
    {
        $query = Invoice::with(['customer', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->orderBy('created_at', 'desc')->limit(500)->get();

        $pdf = Pdf::loadView('exports.invoices-pdf', compact('invoices'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('invoices-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Customers to Excel
     */
    public function customersExcel()
    {
        return Excel::download(new CustomersExport(), 'customers-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export Customers to PDF
     */
    public function customersPdf()
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();

        $pdf = Pdf::loadView('exports.customers-pdf', compact('customers'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('customers-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export FPB to Excel
     */
    public function fpbExcel(Request $request)
    {
        return Excel::download(
            new FpbExport($request->all()),
            'fpb-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export FPB to PDF
     */
    public function fpbPdf(Request $request)
    {
        $query = Fpb::with(['spk', 'creator', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $fpbs = $query->orderBy('created_at', 'desc')->limit(500)->get();

        $pdf = Pdf::loadView('exports.fpb-pdf', compact('fpbs'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('fpb-' . date('Y-m-d') . '.pdf');
    }
}
