<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->active();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->stock_status == 'low') {
            $query->lowStock();
        } elseif ($request->stock_status == 'normal') {
            $query->whereRaw('current_stock > min_stock');
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::active()->get();
        $lowStockCount = Product::active()->lowStock()->count();

        return view('inventory.index', compact('products', 'categories', 'lowStockCount'));
    }

    public function bahanBaku(Request $request)
    {
        $query = Product::with('category')->active()->bahanBaku();

        if ($request->filled('spec_type')) {
            $query->where('spec_type', $request->spec_type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->stock_status == 'low') {
            $query->lowStock();
        } elseif ($request->stock_status == 'normal') {
            $query->whereRaw('current_stock > min_stock');
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('inventory.partials.bahan-baku-table', compact('products'))->render(),
                'pagination' => $products->links()->toHtml(),
            ]);
        }

        return view('inventory.bahan-baku', compact('products'));
    }

    public function packaging(Request $request)
    {
        $query = Product::with('category')->active()->packaging();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->stock_status == 'low') {
            $query->lowStock();
        } elseif ($request->stock_status == 'normal') {
            $query->whereRaw('current_stock > min_stock');
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('inventory.partials.packaging-table', compact('products'))->render(),
                'pagination' => $products->links()->toHtml(),
            ]);
        }

        return view('inventory.packaging', compact('products'));
    }

    public function barangJadi(Request $request)
    {
        $query = Product::with('category')->active()->barangJadi();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->stock_status == 'low') {
            $query->lowStock();
        } elseif ($request->stock_status == 'normal') {
            $query->whereRaw('current_stock > min_stock');
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('inventory.partials.barang-jadi-table', compact('products'))->render(),
                'pagination' => $products->links()->toHtml(),
            ]);
        }

        return view('inventory.barang-jadi', compact('products'));
    }

    public function stockHistory(Request $request)
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

        $movements = $query->latest()->paginate(20)->withQueryString();
        $products = Product::active()->orderBy('name')->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('inventory.partials.stock-history-table', compact('movements'))->render(),
                'pagination' => $movements->links()->toHtml(),
            ]);
        }

        return view('inventory.stock-history', compact('movements', 'products'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:products,code',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'spec_type' => 'nullable|in:high_spec,medium_spec',
            'current_stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'supplier_type' => 'nullable|in:supplier_resmi,agen,internal',
            'description' => 'nullable|string',
        ]);

        $user = auth()->user();
        $isOwner = $user->isOwner();

        $product = Product::create([
            ...$request->all(),
            'created_by' => $user->id,
            'approval_status' => $isOwner ? 'approved' : 'pending',
            'approved_by' => $isOwner ? $user->id : null,
            'approved_at' => $isOwner ? now() : null,
            'is_active' => $isOwner,
        ]);

        // Create initial stock movement if stock > 0 and approved
        if ($product->current_stock > 0 && $isOwner) {
            $product->stockMovements()->create([
                'user_id' => auth()->id(),
                'type' => 'in',
                'quantity' => $product->current_stock,
                'stock_before' => 0,
                'stock_after' => $product->current_stock,
                'reference_type' => 'manual',
                'notes' => 'Stok awal',
            ]);
        }

        if ($isOwner) {
            return redirect()->route('inventory.index')->with('success', 'Produk berhasil ditambahkan.');
        } else {
            return redirect()->route('inventory.index')->with('success', 'Produk berhasil diajukan. Menunggu persetujuan Owner.');
        }
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'stockMovements' => function ($q) {
                $q->with('user')->latest()->take(20);
            }
        ]);

        return view('inventory.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        return view('inventory.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code' => 'required|string|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'spec_type' => 'nullable|in:high_spec,medium_spec',
            'min_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'supplier_type' => 'nullable|in:supplier_resmi,agen,internal',
            'description' => 'nullable|string',
        ]);

        $product->update($request->except('current_stock'));

        return redirect()->route('inventory.show', $product)->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $user = auth()->user();

        // Owner can delete directly
        if ($user->isOwner()) {
            $product->update([
                'is_active' => false,
                'approval_status' => 'deleted',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Produk berhasil dihapus.');
        }

        // Operasional requests deletion (needs owner approval)
        if ($user->isOperasional()) {
            $product->requestDeletion($user);
            return redirect()->back()->with('success', 'Permintaan hapus produk telah diajukan ke Owner.');
        }

        abort(403, 'Unauthorized');
    }

    public function approveDeletion(Product $product)
    {
        // Only owner can approve deletion
        if (!auth()->user()->isOwner()) {
            abort(403);
        }

        if ($product->approval_status !== 'pending_deletion') {
            return redirect()->back()->with('error', 'Produk tidak dalam status pending deletion.');
        }

        $product->completeDeletion(auth()->user());

        return redirect()->back()->with('success', 'Permintaan hapus produk berhasil disetujui.');
    }

    public function rejectDeletion(Product $product)
    {
        // Only owner can reject deletion
        if (!auth()->user()->isOwner()) {
            abort(403);
        }

        if ($product->approval_status !== 'pending_deletion') {
            return redirect()->back()->with('error', 'Produk tidak dalam status pending deletion.');
        }

        $product->cancelDeletion(auth()->user());

        return redirect()->back()->with('success', 'Permintaan hapus produk ditolak.');
    }

    public function adjustStock(Request $request, Product $product)
    {
        $request->validate([
            'adjustment_type' => 'required|in:add,reduce,set',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        $user = auth()->user();
        $stockBefore = $product->current_stock;
        $quantity = $request->quantity;

        switch ($request->adjustment_type) {
            case 'add':
                $product->addStock($quantity, $user->id, 'manual', null, $request->notes);
                break;
            case 'reduce':
                $product->reduceStock($quantity, $user->id, 'manual', null, $request->notes);
                break;
            case 'set':
                $difference = $quantity - $stockBefore;
                $product->update(['current_stock' => $quantity]);
                $product->stockMovements()->create([
                    'user_id' => $user->id,
                    'type' => 'adjustment',
                    'quantity' => abs($difference),
                    'stock_before' => $stockBefore,
                    'stock_after' => $quantity,
                    'reference_type' => 'manual',
                    'notes' => $request->notes ?? 'Penyesuaian stok',
                ]);
                break;
        }

        // Check for low stock and notify
        $product->refresh();
        if ($product->isLowStock()) {
            $owners = User::where('role', 'owner')->where('is_active', true)->get();
            foreach ($owners as $owner) {
                // Only create notification if not already notified recently
                $recentNotification = Notification::where('user_id', $owner->id)
                    ->where('type', 'low_stock')
                    ->where('data->product_id', $product->id)
                    ->where('created_at', '>=', now()->subHours(24))
                    ->exists();

                if (!$recentNotification) {
                    Notification::createLowStockNotification($product, $owner);
                }
            }
        }

        return redirect()->back()->with('success', 'Stok berhasil disesuaikan.');
    }

    public function pendingApproval(Request $request)
    {
        // Owner and Finance can view pending approval
        if (!auth()->user()->isOwner() && !auth()->user()->isFinance()) {
            abort(403);
        }

        $products = Product::with(['category', 'creator'])
            ->pendingApproval()
            ->latest()
            ->paginate(10);

        return view('inventory.pending-approval', compact('products'));
    }

    public function pendingDeletion(Request $request)
    {
        // Only owner can view pending deletion
        if (!auth()->user()->isOwner()) {
            abort(403);
        }

        $products = Product::with(['category', 'creator'])
            ->pendingDeletion()
            ->latest()
            ->paginate(10);

        return view('inventory.pending-deletion', compact('products'));
    }

    public function approveItem(Product $product)
    {
        // Owner and Finance can approve
        if (!auth()->user()->isOwner() && !auth()->user()->isFinance()) {
            abort(403);
        }

        if (!$product->isPendingApproval()) {
            return redirect()->back()->with('error', 'Item tidak dalam status pending.');
        }

        $user = auth()->user();

        // Create initial stock movement when approved
        if ($product->current_stock > 0) {
            $product->stockMovements()->create([
                'user_id' => $user->id,
                'type' => 'in',
                'quantity' => $product->current_stock,
                'stock_before' => 0,
                'stock_after' => $product->current_stock,
                'reference_type' => 'manual',
                'notes' => 'Stok awal (setelah approval)',
            ]);
        }

        $product->approve($user);

        return redirect()->back()->with('success', 'Item berhasil disetujui.');
    }

    public function rejectItem(Product $product)
    {
        // Owner and Finance can reject
        if (!auth()->user()->isOwner() && !auth()->user()->isFinance()) {
            abort(403);
        }

        if (!$product->isPendingApproval()) {
            return redirect()->back()->with('error', 'Item tidak dalam status pending.');
        }

        $product->reject(auth()->user());

        return redirect()->back()->with('success', 'Item ditolak.');
    }

    // Separate Create Forms
    public function createBahanBaku()
    {
        $category = Category::where('type', 'bahan_baku')->first();
        return view('inventory.create-bahan-baku', compact('category'));
    }

    public function storeBahanBaku(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:products,code',
            'name' => 'required|string|max:255',
            'spec_type' => 'nullable|in:high_spec,medium_spec',
            'current_stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'supplier_type' => 'nullable|in:supplier_resmi,agen,internal',
            'description' => 'nullable|string',
        ]);

        $user = auth()->user();
        $isOwner = $user->isOwner();
        $canAutoApprove = $isOwner || $user->isFinance();
        $category = Category::where('type', 'bahan_baku')->firstOrFail();

        $product = Product::create([
            'code' => $request->code,
            'name' => $request->name,
            'category_id' => $category->id,
            'spec_type' => $request->spec_type,
            'current_stock' => $request->current_stock,
            'min_stock' => $request->min_stock,
            'unit' => $request->unit,
            'supplier_type' => $request->supplier_type,
            'description' => $request->description,
            'created_by' => $user->id,
            'approval_status' => $canAutoApprove ? 'approved' : 'pending',
            'approved_by' => $canAutoApprove ? $user->id : null,
            'approved_at' => $canAutoApprove ? now() : null,
            'is_active' => $canAutoApprove,
        ]);

        if ($product->current_stock > 0 && $canAutoApprove) {
            $product->stockMovements()->create([
                'user_id' => $user->id,
                'type' => 'in',
                'quantity' => $product->current_stock,
                'stock_before' => 0,
                'stock_after' => $product->current_stock,
                'reference_type' => 'manual',
                'notes' => 'Stok awal',
            ]);
        }

        $msg = $canAutoApprove ? 'Bahan baku berhasil ditambahkan.' : 'Bahan baku berhasil diajukan. Menunggu persetujuan Owner.';
        return redirect()->route('inventory.bahan-baku')->with('success', $msg);
    }

    public function createPackaging()
    {
        $category = Category::where('type', 'packaging')->first();
        return view('inventory.create-packaging', compact('category'));
    }

    public function storePackaging(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:products,code',
            'name' => 'required|string|max:255',
            'current_stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $user = auth()->user();
        $isOwner = $user->isOwner();
        $canAutoApprove = $isOwner || $user->isFinance();
        $category = Category::where('type', 'packaging')->firstOrFail();

        $product = Product::create([
            'code' => $request->code,
            'name' => $request->name,
            'category_id' => $category->id,
            'current_stock' => $request->current_stock,
            'min_stock' => $request->min_stock,
            'unit' => $request->unit,
            'description' => $request->description,
            'created_by' => $user->id,
            'approval_status' => $canAutoApprove ? 'approved' : 'pending',
            'approved_by' => $canAutoApprove ? $user->id : null,
            'approved_at' => $canAutoApprove ? now() : null,
            'is_active' => $canAutoApprove,
        ]);

        if ($product->current_stock > 0 && $canAutoApprove) {
            $product->stockMovements()->create([
                'user_id' => $user->id,
                'type' => 'in',
                'quantity' => $product->current_stock,
                'stock_before' => 0,
                'stock_after' => $product->current_stock,
                'reference_type' => 'manual',
                'notes' => 'Stok awal',
            ]);
        }

        $msg = $canAutoApprove ? 'Material/Packaging berhasil ditambahkan.' : 'Material/Packaging berhasil diajukan. Menunggu persetujuan Owner.';
        return redirect()->route('inventory.packaging')->with('success', $msg);
    }

    public function createBarangJadi()
    {
        $category = Category::where('type', 'barang_jadi')->first();
        return view('inventory.create-barang-jadi', compact('category'));
    }

    public function storeBarangJadi(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:products,code',
            'name' => 'required|string|max:255',
            'current_stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $user = auth()->user();
        $isOwner = $user->isOwner();
        $canAutoApprove = $isOwner || $user->isFinance();
        $category = Category::where('type', 'barang_jadi')->firstOrFail();

        $product = Product::create([
            'code' => $request->code,
            'name' => $request->name,
            'category_id' => $category->id,
            'current_stock' => $request->current_stock,
            'min_stock' => $request->min_stock,
            'unit' => $request->unit,
            'description' => $request->description,
            'created_by' => $user->id,
            'approval_status' => $canAutoApprove ? 'approved' : 'pending',
            'approved_by' => $canAutoApprove ? $user->id : null,
            'approved_at' => $canAutoApprove ? now() : null,
            'is_active' => $canAutoApprove,
        ]);

        if ($product->current_stock > 0 && $canAutoApprove) {
            $product->stockMovements()->create([
                'user_id' => $user->id,
                'type' => 'in',
                'quantity' => $product->current_stock,
                'stock_before' => 0,
                'stock_after' => $product->current_stock,
                'reference_type' => 'manual',
                'notes' => 'Stok awal',
            ]);
        }

        $msg = $canAutoApprove ? 'Barang Jadi berhasil ditambahkan.' : 'Barang Jadi berhasil diajukan. Menunggu persetujuan Owner.';
        return redirect()->route('inventory.barang-jadi')->with('success', $msg);
    }

    public function bahanBakuHistory(Request $request)
    {
        $products = Product::active()->bahanBaku()->orderBy('name')->get();

        $query = StockMovement::with(['product', 'user'])
            ->whereHas('product', fn($q) => $q->bahanBaku());

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->latest()->paginate(15)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('inventory.partials.category-history-table', compact('movements'))->render(),
                'pagination' => $movements->hasPages() ? $movements->links()->toHtml() : '',
            ]);
        }

        return view('inventory.bahan-baku-history', compact('movements', 'products'));
    }

    public function packagingHistory(Request $request)
    {
        $products = Product::active()->packaging()->orderBy('name')->get();

        $query = StockMovement::with(['product', 'user'])
            ->whereHas('product', fn($q) => $q->packaging());

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->latest()->paginate(15)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('inventory.partials.category-history-table', compact('movements'))->render(),
                'pagination' => $movements->hasPages() ? $movements->links()->toHtml() : '',
            ]);
        }

        return view('inventory.packaging-history', compact('movements', 'products'));
    }

    public function barangJadiHistory(Request $request)
    {
        $products = Product::active()->barangJadi()->orderBy('name')->get();

        $query = StockMovement::with(['product', 'user'])
            ->whereHas('product', fn($q) => $q->barangJadi());

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->latest()->paginate(15)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('inventory.partials.category-history-table', compact('movements'))->render(),
                'pagination' => $movements->hasPages() ? $movements->links()->toHtml() : '',
            ]);
        }

        return view('inventory.barang-jadi-history', compact('movements', 'products'));
    }

    /**
     * Show stock addition form for Operasional
     */
    public function addStock()
    {
        $products = Product::active()->orderBy('name')->get();
        return view('inventory.add-stock', compact('products'));
    }

    /**
     * Store stock addition
     */
    public function storeStock(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $product->addStock(
            $validated['quantity'],
            auth()->id(),
            'manual',
            null,
            $validated['notes'] ?? 'Penambahan stok manual'
        );

        return redirect()->route('inventory.add-stock')
            ->with('success', "Berhasil menambah {$validated['quantity']} {$product->unit} untuk {$product->name}");
    }
}

