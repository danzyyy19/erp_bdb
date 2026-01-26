<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manual Book
    Route::view('/manual-book', 'manual-book')->name('manual-book');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // SPK Routes (Owner, Operasional)
    Route::prefix('spk')->name('spk.')->middleware('role:owner,operasional')->group(function () {
        Route::get('/', [SpkController::class, 'index'])->name('index');
        Route::get('/create', [SpkController::class, 'create'])->name('create');
        Route::post('/', [SpkController::class, 'store'])->name('store');
        Route::get('/pending', [SpkController::class, 'pending'])->name('pending');
        Route::get('/{spk}', [SpkController::class, 'show'])->name('show');
        Route::get('/{spk}/edit', [SpkController::class, 'edit'])->name('edit');
        Route::put('/{spk}', [SpkController::class, 'update'])->name('update');
        Route::delete('/{spk}', [SpkController::class, 'destroy'])->name('destroy');
        Route::post('/{spk}/approve', [SpkController::class, 'approve'])->name('approve');
        Route::post('/{spk}/reject', [SpkController::class, 'reject'])->name('reject');
        Route::post('/{spk}/start', [SpkController::class, 'start'])->name('start');
        Route::post('/{spk}/complete', [SpkController::class, 'complete'])->name('complete');
        Route::post('/{spk}/production-log', [SpkController::class, 'addProductionLog'])->name('add-log');
        Route::delete('/production-log/{log}', [SpkController::class, 'deleteProductionLog'])->name('delete-log');
        Route::get('/{spk}/print', [SpkController::class, 'print'])->name('print');
    });

    // Inventory Routes
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/bahan-baku', [InventoryController::class, 'bahanBaku'])->name('bahan-baku');
        Route::get('/bahan-baku/create', [InventoryController::class, 'createBahanBaku'])->name('bahan-baku.create');
        Route::post('/bahan-baku', [InventoryController::class, 'storeBahanBaku'])->name('bahan-baku.store');
        Route::get('/packaging', [InventoryController::class, 'packaging'])->name('packaging');
        Route::get('/packaging/create', [InventoryController::class, 'createPackaging'])->name('packaging.create');
        Route::post('/packaging', [InventoryController::class, 'storePackaging'])->name('packaging.store');
        Route::get('/barang-jadi', [InventoryController::class, 'barangJadi'])->name('barang-jadi');
        Route::get('/barang-jadi/create', [InventoryController::class, 'createBarangJadi'])->name('barang-jadi.create');
        Route::post('/barang-jadi', [InventoryController::class, 'storeBarangJadi'])->name('barang-jadi.store');
        Route::get('/bahan-baku/history', [InventoryController::class, 'bahanBakuHistory'])->name('bahan-baku.history');
        Route::get('/packaging/history', [InventoryController::class, 'packagingHistory'])->name('packaging.history');
        Route::get('/barang-jadi/history', [InventoryController::class, 'barangJadiHistory'])->name('barang-jadi.history');
        Route::get('/stock-history', [InventoryController::class, 'stockHistory'])->name('stock-history');
        Route::get('/pending-approval', [InventoryController::class, 'pendingApproval'])->name('pending-approval');
        Route::post('/products/{product}/approve', [InventoryController::class, 'approveItem'])->name('approve');
        Route::post('/products/{product}/reject', [InventoryController::class, 'rejectItem'])->name('reject');
        Route::get('/pending-deletion', [InventoryController::class, 'pendingDeletion'])->name('pending-deletion');
        Route::post('/products/{product}/approve-deletion', [InventoryController::class, 'approveDeletion'])->name('approve-deletion');
        Route::post('/products/{product}/reject-deletion', [InventoryController::class, 'rejectDeletion'])->name('reject-deletion');
        Route::get('/products', [InventoryController::class, 'index'])->name('index');
        Route::get('/products/create', [InventoryController::class, 'create'])->name('create');
        Route::post('/products', [InventoryController::class, 'store'])->name('store');
        Route::get('/products/{product}', [InventoryController::class, 'show'])->name('show');
        Route::get('/products/{product}/edit', [InventoryController::class, 'edit'])->name('edit');
        Route::put('/products/{product}', [InventoryController::class, 'update'])->name('update');
        Route::delete('/products/{product}', [InventoryController::class, 'destroy'])->name('destroy');
        Route::post('/products/{product}/adjust-stock', [InventoryController::class, 'adjustStock'])->name('adjust-stock');

        // Stock Addition for Operasional
        Route::get('/add-stock', [InventoryController::class, 'addStock'])->name('add-stock');
        Route::post('/add-stock', [InventoryController::class, 'storeStock'])->name('add-stock.store');
    });

    // Sales Routes
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
    });

    // Invoice Routes (Owner, Finance)
    Route::prefix('invoice')->name('invoice.')->middleware('role:owner,finance')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/create-from-delivery-note/{deliveryNote}', [InvoiceController::class, 'createFromDeliveryNote'])->name('create-from-delivery-note');
        Route::post('/store-from-delivery-note/{deliveryNote}', [InvoiceController::class, 'storeFromDeliveryNote'])->name('store-from-delivery-note');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
        Route::get('/{invoice}/print', [InvoiceController::class, 'print'])->name('print');
        Route::post('/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('mark-paid');
        Route::post('/{invoice}/record-payment', [InvoiceController::class, 'recordPayment'])->name('payment');
        Route::post('/{invoice}/approve', [InvoiceController::class, 'approveInvoice'])->name('approve');
        Route::post('/{invoice}/reject', [InvoiceController::class, 'rejectInvoice'])->name('reject');
    });

    // Special Orders - REMOVED (fitur dihapus)

    // Delivery Notes (Owner, Finance, Operational)
    Route::prefix('delivery-notes')->name('delivery-notes.')->group(function () {
        Route::get('/', [App\Http\Controllers\DeliveryNoteController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\DeliveryNoteController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\DeliveryNoteController::class, 'store'])->name('store');
        Route::get('/{deliveryNote}', [App\Http\Controllers\DeliveryNoteController::class, 'show'])->name('show');
        Route::get('/{deliveryNote}/print', [App\Http\Controllers\DeliveryNoteController::class, 'print'])->name('print');
        Route::post('/{deliveryNote}/approve', [App\Http\Controllers\DeliveryNoteController::class, 'approve'])->name('approve');
        Route::post('/{deliveryNote}/delivered', [App\Http\Controllers\DeliveryNoteController::class, 'markDelivered'])->name('delivered');
    });

    // Suppliers (Owner, Finance only)
    Route::prefix('suppliers')->name('suppliers.')->middleware('role:owner,finance')->group(function () {
        Route::get('/', [App\Http\Controllers\SupplierController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\SupplierController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SupplierController::class, 'store'])->name('store');
        Route::get('/{supplier}', [App\Http\Controllers\SupplierController::class, 'show'])->name('show');
        Route::get('/{supplier}/edit', [App\Http\Controllers\SupplierController::class, 'edit'])->name('edit');
        Route::put('/{supplier}', [App\Http\Controllers\SupplierController::class, 'update'])->name('update');
        Route::post('/{supplier}/toggle-status', [App\Http\Controllers\SupplierController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Purchases (Owner, Finance only)
    Route::prefix('purchases')->name('purchases.')->middleware('role:owner,finance')->group(function () {
        Route::get('/', [App\Http\Controllers\PurchaseController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\PurchaseController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\PurchaseController::class, 'store'])->name('store');
        Route::get('/{purchase}', [App\Http\Controllers\PurchaseController::class, 'show'])->name('show');
        Route::get('/{purchase}/edit', [App\Http\Controllers\PurchaseController::class, 'edit'])->name('edit');
        Route::put('/{purchase}', [App\Http\Controllers\PurchaseController::class, 'update'])->name('update');
        Route::post('/{purchase}/submit', [App\Http\Controllers\PurchaseController::class, 'submit'])->name('submit');
        Route::post('/{purchase}/approve', [App\Http\Controllers\PurchaseController::class, 'approve'])->name('approve');
        Route::post('/{purchase}/reject', [App\Http\Controllers\PurchaseController::class, 'reject'])->name('reject');
        Route::post('/{purchase}/receive', [App\Http\Controllers\PurchaseController::class, 'receive'])->name('receive');
        Route::get('/{purchase}/print', [App\Http\Controllers\PurchaseController::class, 'print'])->name('print');
        Route::delete('/{purchase}', [App\Http\Controllers\PurchaseController::class, 'destroy'])->name('destroy');
    });


    // FPB (Form Permintaan Barang)
    Route::prefix('fpb')->name('fpb.')->group(function () {
        Route::get('/', [App\Http\Controllers\FpbController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\FpbController::class, 'create'])->name('create');
        Route::get('/create-from-spk/{spk}', [App\Http\Controllers\FpbController::class, 'createFromSpk'])->name('create-from-spk');
        Route::post('/', [App\Http\Controllers\FpbController::class, 'store'])->name('store');
        Route::get('/{fpb}', [App\Http\Controllers\FpbController::class, 'show'])->name('show');
        Route::post('/{fpb}/approve', [App\Http\Controllers\FpbController::class, 'approve'])->name('approve');
        Route::post('/{fpb}/reject', [App\Http\Controllers\FpbController::class, 'reject'])->name('reject');
        Route::get('/{fpb}/print', [App\Http\Controllers\FpbController::class, 'print'])->name('print');
    });

    // Job Cost (Inventory usage outside FPB)
    Route::prefix('job-costs')->name('job-costs.')->middleware('role:owner,operasional')->group(function () {
        Route::get('/', [App\Http\Controllers\JobCostController::class, 'index'])->name('index');
        Route::get('/pending', [App\Http\Controllers\JobCostController::class, 'pending'])->name('pending');
        Route::get('/create', [App\Http\Controllers\JobCostController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\JobCostController::class, 'store'])->name('store');
        Route::get('/{jobCost}', [App\Http\Controllers\JobCostController::class, 'show'])->name('show');
        Route::get('/{jobCost}/edit', [App\Http\Controllers\JobCostController::class, 'edit'])->name('edit');
        Route::put('/{jobCost}', [App\Http\Controllers\JobCostController::class, 'update'])->name('update');
        Route::post('/{jobCost}/submit', [App\Http\Controllers\JobCostController::class, 'submit'])->name('submit');
        Route::post('/{jobCost}/approve', [App\Http\Controllers\JobCostController::class, 'approve'])->name('approve');
        Route::post('/{jobCost}/reject', [App\Http\Controllers\JobCostController::class, 'reject'])->name('reject');
    });

    // Report Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/production', [ReportController::class, 'production'])->name('production');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
    });

    // Customer Routes
    Route::resource('customers', CustomerController::class);

    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/', [SettingsController::class, 'update'])->name('update');
    });

    // Export Routes
    Route::prefix('export')->name('export.')->group(function () {
        // Products Export
        Route::get('/products/excel', [App\Http\Controllers\ExportController::class, 'productsExcel'])->name('products.excel');
        Route::get('/products/pdf', [App\Http\Controllers\ExportController::class, 'productsPdf'])->name('products.pdf');

        // Stock Movements Export
        Route::get('/stock-movements/excel', [App\Http\Controllers\ExportController::class, 'stockMovementsExcel'])->name('stock-movements.excel');
        Route::get('/stock-movements/pdf', [App\Http\Controllers\ExportController::class, 'stockMovementsPdf'])->name('stock-movements.pdf');

        // SPK Export (Owner, Operational)
        Route::get('/spk/excel', [App\Http\Controllers\ExportController::class, 'spkExcel'])->name('spk.excel');
        Route::get('/spk/pdf', [App\Http\Controllers\ExportController::class, 'spkPdf'])->name('spk.pdf');

        // Invoice Export (Owner, Finance)
        Route::get('/invoices/excel', [App\Http\Controllers\ExportController::class, 'invoicesExcel'])->name('invoices.excel');
        Route::get('/invoices/pdf', [App\Http\Controllers\ExportController::class, 'invoicesPdf'])->name('invoices.pdf');

        // Customers Export (Owner, Finance)
        Route::get('/customers/excel', [App\Http\Controllers\ExportController::class, 'customersExcel'])->name('customers.excel');
        Route::get('/customers/pdf', [App\Http\Controllers\ExportController::class, 'customersPdf'])->name('customers.pdf');

        // FPB Export (Owner, Operational)
        Route::get('/fpb/excel', [App\Http\Controllers\ExportController::class, 'fpbExcel'])->name('fpb.excel');
        Route::get('/fpb/pdf', [App\Http\Controllers\ExportController::class, 'fpbPdf'])->name('fpb.pdf');
    });
});

require __DIR__ . '/auth.php';
