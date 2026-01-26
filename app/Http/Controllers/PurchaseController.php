<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchases.
     */
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'creator']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('purchase_number', 'like', '%' . $request->search . '%')
                    ->orWhereHas('supplier', function ($sq) use ($request) {
                        $sq->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        $purchases = $query->latest()->paginate(15)->withQueryString();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    /**
     * Show the form for creating a new purchase.
     */
    public function create()
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        $products = Product::active()->orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created purchase.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'tax_percentage' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $taxPercentage = $validated['tax_percentage'] ?? 0;
        $taxAmount = $subtotal * ($taxPercentage / 100);
        $discount = $validated['discount'] ?? 0;
        $totalAmount = $subtotal + $taxAmount - $discount;

        $purchase = Purchase::create([
            'supplier_id' => $validated['supplier_id'],
            'purchase_date' => $validated['purchase_date'],
            'subtotal' => $subtotal,
            'tax' => $taxAmount, // Save calculated amount
            'discount' => $discount,
            'total_amount' => $totalAmount,
            'notes' => $validated['notes'],
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        foreach ($validated['items'] as $item) {
            $subtotalItem = $item['quantity'] * $item['unit_price'];
            $purchase->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $subtotalItem,
            ]);
        }

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Purchase Order berhasil dibuat.');
    }

    /**
     * Display the specified purchase.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'creator', 'approver', 'items.product']);

        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the purchase.
     */
    public function edit(Purchase $purchase)
    {
        if (!in_array($purchase->status, ['draft', 'pending'])) {
            return back()->with('error', 'Purchase Order tidak dapat diedit.');
        }

        $purchase->load('items.product');
        $suppliers = Supplier::active()->orderBy('name')->get();
        $products = Product::active()->orderBy('name')->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    /**
     * Update the specified purchase.
     */
    public function update(Request $request, Purchase $purchase)
    {
        if (!in_array($purchase->status, ['draft', 'pending'])) {
            return back()->with('error', 'Purchase Order tidak dapat diedit.');
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'tax_percentage' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $taxPercentage = $validated['tax_percentage'] ?? 0;
        $taxAmount = $subtotal * ($taxPercentage / 100);
        $discount = $validated['discount'] ?? 0;
        $totalAmount = $subtotal + $taxAmount - $discount;

        $purchase->update([
            'supplier_id' => $validated['supplier_id'],
            'purchase_date' => $validated['purchase_date'],
            'subtotal' => $subtotal,
            'tax' => $taxAmount, // Save calculated amount
            'discount' => $discount,
            'total_amount' => $totalAmount,
            'notes' => $validated['notes'],
        ]);

        // Delete existing items and recreate
        $purchase->items()->delete();
        foreach ($validated['items'] as $item) {
            $subtotalItem = $item['quantity'] * $item['unit_price'];
            $purchase->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $subtotalItem,
            ]);
        }

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Purchase Order berhasil diperbarui.');
    }

    /**
     * Submit for approval.
     */
    public function submit(Purchase $purchase)
    {
        if ($purchase->status !== 'draft') {
            return back()->with('error', 'Hanya draft yang dapat diajukan.');
        }

        $purchase->update(['status' => 'pending']);

        // Notify owner
        Notification::notifyPoPending($purchase);

        return back()->with('success', 'Purchase Order diajukan untuk persetujuan.');
    }

    /**
     * Approve the purchase.
     */
    public function approve(Purchase $purchase)
    {
        if ($purchase->status !== 'pending') {
            return back()->with('error', 'Purchase Order tidak dalam status pending.');
        }

        $purchase->approve(auth()->user());

        // Notify finance
        Notification::notifyPoApproved($purchase);

        return back()->with('success', 'Purchase Order disetujui.');
    }

    /**
     * Reject the purchase.
     */
    public function reject(Purchase $purchase)
    {
        if ($purchase->status !== 'pending') {
            return back()->with('error', 'Purchase Order tidak dalam status pending.');
        }

        $purchase->update(['status' => 'cancelled']);

        return back()->with('success', 'Purchase Order ditolak.');
    }

    /**
     * Mark as received.
     * Only Finance can receive goods.
     */
    public function receive(Purchase $purchase)
    {
        // Only Finance can receive goods
        if (!auth()->user()->isFinance()) {
            abort(403, 'Hanya Finance yang dapat menerima barang.');
        }

        if ($purchase->status !== 'approved') {
            return back()->with('error', 'Hanya PO yang disetujui dapat diterima.');
        }

        $purchase->markReceived(auth()->user());

        // Notify owner
        Notification::notifyPoReceived($purchase);

        return back()->with('success', 'Barang sudah diterima dan stok diperbarui.');
    }

    /**
     * Print purchase order.
     */
    public function print(Purchase $purchase)
    {
        $purchase->load(['supplier', 'creator', 'approver', 'items.product']);

        return view('purchases.print', compact('purchase'));
    }
}
