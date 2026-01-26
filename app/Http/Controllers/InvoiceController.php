<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\DeliveryNote;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
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

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        $invoices = $query->latest()->paginate(10)->withQueryString();
        $customers = Customer::active()->orderBy('name')->get();

        return view('invoice.index', compact('invoices', 'customers'));
    }

    public function create()
    {
        // Only Owner and Finance can create invoices
        $user = auth()->user();
        if (!$user->isOwner() && !$user->isFinance()) {
            abort(403, 'Unauthorized');
        }

        // Get delivery notes that are "delivered" AND do NOT have an active invoice
        // Check BOTH: delivery_notes.invoice_id AND invoices.delivery_note_id

        // Get IDs of delivery notes that have active invoices (from invoices table)
        $deliveryNotesWithActiveInvoice = \App\Models\Invoice::whereNotIn('status', ['cancelled'])
            ->whereNotNull('delivery_note_id')
            ->pluck('delivery_note_id')
            ->toArray();

        $deliveryNotes = DeliveryNote::with('customer')
            ->where('status', 'delivered')
            ->where(function ($query) {
                // Either has no invoice_id at all
                $query->whereNull('invoice_id')
                    // Or has an invoice that is cancelled (can be reused)
                    ->orWhereHas('invoice', function ($q) {
                    $q->where('status', 'cancelled');
                });
            })
            // ALSO exclude delivery notes that have active invoice via invoices.delivery_note_id
            ->whereNotIn('id', $deliveryNotesWithActiveInvoice)
            ->latest()
            ->get();

        return view('invoice.create', compact('deliveryNotes'));
    }

    public function store(Request $request)
    {
        // Only Owner and Finance can create invoices
        $user = auth()->user();
        if (!$user->isOwner() && !$user->isFinance()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = DB::transaction(function () use ($request) {
            // Create invoice
            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'created_by' => auth()->id(),
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'tax_percent' => $request->tax_percent ?? 0,
                'discount' => $request->discount ?? 0,
                'notes' => $request->notes,
                // Finance needs approval, Owner auto-approved. BUT user requested Finance can acc themselves aka auto-approve
                'status' => (auth()->user()->isOwner() || auth()->user()->isFinance()) ? 'sent' : 'pending_approval',
            ]);

            // Add items and reduce stock
            foreach ($request->items as $item) {
                if (!empty($item['product_id']) && !empty($item['quantity']) && !empty($item['unit_price'])) {
                    $product = Product::find($item['product_id']);

                    $invoice->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'notes' => $item['notes'] ?? null,
                    ]);

                    // Reduce stock
                    $product->reduceStock(
                        $item['quantity'],
                        auth()->id(),
                        'invoice',
                        $invoice->id,
                        "Penjualan Invoice {$invoice->invoice_number}"
                    );
                }
            }

            return $invoice;
        });

        // Notify owner if created by finance and pending approval
        if ($invoice->status === 'pending_approval') {
            Notification::notifyInvoicePending($invoice);
        }

        return redirect()->route('invoice.show', $invoice)->with('success', 'Invoice berhasil dibuat.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'creator', 'items.product']);

        return view('invoice.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->status === 'cancelled') {
            return redirect()->route('invoice.show', $invoice)->with('error', 'Invoice yang dibatalkan tidak dapat diedit.');
        }

        $invoice->load('items.product');
        $customers = Customer::active()->orderBy('name')->get();
        $products = Product::active()->barangJadi()->get();

        return view('invoice.edit', compact('invoice', 'customers', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'cancelled') {
            return redirect()->route('invoice.show', $invoice)->with('error', 'Invoice yang dibatalkan tidak dapat diedit.');
        }

        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            // Update invoice details
            $invoice->update($request->only([
                'customer_id',
                'invoice_date',
                'due_date',
                'tax_percent',
                'discount',
                'notes'
            ]));

            // Get submitted item IDs (existing items being kept)
            $submittedItemIds = collect($request->items)
                ->pluck('id')
                ->filter()
                ->toArray();

            // Delete items that were removed
            $invoice->items()->whereNotIn('id', $submittedItemIds)->delete();

            // Update or create items
            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                $subtotal = $itemData['quantity'] * $itemData['unit_price'];

                if (!empty($itemData['id'])) {
                    // Update existing item
                    $invoice->items()->where('id', $itemData['id'])->update([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'subtotal' => $subtotal,
                    ]);
                } else {
                    // Create new item
                    $invoice->items()->create([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            // Recalculate totals
            $invoice->calculateTotals();
        });

        return redirect()->route('invoice.show', $invoice)->with('success', 'Invoice berhasil diperbarui.');
    }

    public function destroy(Invoice $invoice)
    {
        if (!in_array($invoice->status, ['draft'])) {
            return redirect()->route('invoice.show', $invoice)->with('error', 'Invoice tidak dapat dihapus.');
        }

        // Restore stock
        foreach ($invoice->items as $item) {
            $item->product->addStock(
                $item->quantity,
                auth()->id(),
                'invoice',
                $invoice->id,
                "Pembatalan Invoice {$invoice->invoice_number}"
            );
        }

        $invoice->delete();

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dihapus.');
    }

    public function print(Invoice $invoice)
    {
        // Must be approved (sent/paid/partial/overdue) to print
        if (in_array($invoice->status, ['draft', 'pending_approval', 'cancelled'])) {
            return back()->with('error', 'Invoice harus disetujui terlebih dahulu sebelum dicetak.');
        }

        $invoice->load(['customer', 'creator', 'items.product', 'deliveryNote']);

        // Check if delivery note exists and is delivered
        if ($invoice->deliveryNote && $invoice->deliveryNote->status !== 'delivered') {
            return back()->with('error', 'Invoice tidak dapat dicetak karena Surat Jalan belum berstatus Terkirim (Delivered).');
        }

        return view('invoice.print', compact('invoice'));
    }

    public function markPaid(Request $request, Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);

        return redirect()->back()->with('success', 'Invoice ditandai sebagai lunas.');
    }

    public function approveInvoice(Invoice $invoice)
    {
        // Owner and Finance can approve
        if (!auth()->user()->isOwner() && !auth()->user()->isFinance()) {
            abort(403, 'Unauthorized');
        }

        if (!$invoice->isPendingApproval()) {
            return redirect()->back()->with('error', 'Invoice tidak dalam status menunggu persetujuan.');
        }

        $invoice->approve(auth()->user());

        // Notify finance
        Notification::notifyInvoiceApproved($invoice);

        return redirect()->back()->with('success', 'Invoice berhasil disetujui dan dirilis.');
    }

    public function rejectInvoice(Invoice $invoice)
    {
        // Owner and Finance can reject
        if (!auth()->user()->isOwner() && !auth()->user()->isFinance()) {
            abort(403, 'Unauthorized');
        }

        if (!$invoice->isPendingApproval()) {
            return redirect()->back()->with('error', 'Invoice tidak dalam status menunggu persetujuan.');
        }

        $invoice->reject();

        // If invoice is rejected, unlink the delivery note so a new invoice can be created
        if ($invoice->deliveryNote) {
            $invoice->deliveryNote->update(['invoice_id' => null]);
        }

        return redirect()->back()->with('success', 'Invoice ditolak. Surat Jalan dapat dibuatkan invoice ulang.');
    }

    public function recordPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        if ($invoice->status === 'paid') {
            return redirect()->back()->with('error', 'Invoice sudah lunas.');
        }

        if ($invoice->status === 'pending_approval') {
            return redirect()->back()->with('error', 'Invoice belum disetujui.');
        }

        $invoice->addPayment((float) $request->amount);

        if ($invoice->isFullyPaid()) {
            Notification::notifyInvoicePaid($invoice);
        }

        $message = $invoice->isFullyPaid() ? 'Invoice berhasil dilunaskan.' : 'Pembayaran sebagian berhasil dicatat.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Create invoice from Surat Jalan (Delivery Note)
     */
    public function createFromDeliveryNote(DeliveryNote $deliveryNote)
    {
        // Only Owner and Finance can create invoices
        $user = auth()->user();
        if (!$user->isOwner() && !$user->isFinance()) {
            abort(403, 'Unauthorized');
        }

        // Check if delivery note is delivered
        if ($deliveryNote->status !== 'delivered') {
            return back()->with('error', 'Surat Jalan harus berstatus Terkirim (Delivered) sebelum dibuatkan Invoice.');
        }

        // Check if already has invoice (unless it's cancelled)
        if ($deliveryNote->invoice_id) {
            $existingInvoice = Invoice::find($deliveryNote->invoice_id);
            if ($existingInvoice && $existingInvoice->status !== 'cancelled') {
                // Check if invoice is paid - cannot create new invoice
                if ($existingInvoice->status === 'paid') {
                    return redirect()->route('invoice.show', $existingInvoice)
                        ->with('error', 'Surat Jalan sudah memiliki Invoice yang sudah LUNAS. Tidak dapat membuat Invoice baru.');
                }
                return redirect()->route('invoice.show', $existingInvoice)
                    ->with('error', 'Surat Jalan sudah memiliki Invoice yang aktif.');
            }
        }

        $deliveryNote->load(['customer', 'items.product']);

        return view('invoice.create-from-delivery-note', compact('deliveryNote'));
    }

    /**
     * Store invoice from Surat Jalan
     */
    public function storeFromDeliveryNote(Request $request, DeliveryNote $deliveryNote)
    {
        $user = auth()->user();
        if (!$user->isOwner() && !$user->isFinance()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = DB::transaction(function () use ($request, $deliveryNote) {
            // Create invoice
            $invoice = Invoice::create([
                'customer_id' => $deliveryNote->customer_id,
                'delivery_note_id' => $deliveryNote->id,
                'created_by' => auth()->id(),
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'tax_percent' => $request->tax_percent ?? 0,
                'discount' => $request->discount ?? 0,
                'notes' => $request->notes,
                'status' => (auth()->user()->isOwner() || auth()->user()->isFinance()) ? 'sent' : 'pending_approval',
            ]);

            $subtotal = 0;

            // Add items
            foreach ($request->items as $item) {
                if (!empty($item['product_id']) && !empty($item['quantity']) && !empty($item['unit_price'])) {
                    $itemSubtotal = $item['quantity'] * $item['unit_price'];
                    $subtotal += $itemSubtotal;

                    $invoice->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $itemSubtotal,
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            // Calculate totals
            $taxAmount = $subtotal * (($request->tax_percent ?? 0) / 100);
            $total = $subtotal + $taxAmount - ($request->discount ?? 0);

            $invoice->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
            ]);

            // Link invoice to delivery note
            $deliveryNote->update(['invoice_id' => $invoice->id]);

            return $invoice;
        });

        // Notify owner if created by finance and pending approval
        if ($invoice->status === 'pending_approval') {
            Notification::notifyInvoicePending($invoice);
        }

        return redirect()->route('invoice.show', $invoice)->with('success', 'Invoice berhasil dibuat dari Surat Jalan.');
    }
}
