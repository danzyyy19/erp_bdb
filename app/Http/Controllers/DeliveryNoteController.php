<?php

namespace App\Http\Controllers;

use App\Models\DeliveryNote;
use App\Models\DeliveryNoteItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryNoteController extends Controller
{
    /**
     * Check if user can access Surat Jalan
     */
    private function authorizeAccess(): void
    {
        if (!in_array(auth()->user()->role, ['owner', 'finance', 'operasional'])) {
            abort(403, 'Anda tidak memiliki akses ke Surat Jalan.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAccess();

        // Load all delivery notes for client-side filtering
        $deliveryNotesCollection = DeliveryNote::with(['customer', 'creator', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Map data to avoid Blade arrow function syntax issues
        $deliveryNotes = $deliveryNotesCollection->map(function ($dn) {
            return [
                'id' => $dn->id,
                'uuid' => $dn->uuid,
                'sj_number' => $dn->sj_number,
                'customer_name' => $dn->customer?->name ?? '-',
                'delivery_date' => $dn->delivery_date?->format('d M Y') ?? '-',
                'created_at_human' => $dn->created_at->diffForHumans(),
                'status' => $dn->status,
                'status_label' => $dn->status_label,
                'items_count' => $dn->items->count(),
            ];
        });

        return view('delivery-notes.index', compact('deliveryNotes'));
    }

    public function create(Request $request)
    {
        $this->authorizeAccess();

        // Load all customers
        $customers = Customer::orderBy('name')->get()->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'address' => $c->address ?? '',
            'phone' => $c->phone ?? '',
        ]);

        // Load all finished goods (barang jadi)
        $products = Product::active()->barangJadi()->orderBy('name')->get()->map(fn($p) => [
            'id' => $p->id,
            'code' => $p->code,
            'name' => $p->name,
            'stock' => (float) $p->current_stock,
            'unit' => $p->unit,
        ]);

        return view('delivery-notes.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $this->authorizeAccess();

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'delivery_date' => 'required|date',
            'driver_name' => 'nullable|string|max:255',
            'vehicle_number' => 'nullable|string|max:50',
            'recipient_name' => 'nullable|string|max:255',
            'delivery_address' => 'required|string',
            'notes' => 'nullable|string',
            'invoice_number' => 'nullable|string|max:255',
            'invoice_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Create Delivery Note
            $deliveryNote = DeliveryNote::create([
                'customer_id' => $validated['customer_id'],
                'created_by' => auth()->id(),
                'delivery_date' => $validated['delivery_date'],
                'driver_name' => $validated['driver_name'] ?? null,
                'vehicle_number' => $validated['vehicle_number'] ?? null,
                'recipient_name' => $validated['recipient_name'] ?? null,
                'delivery_address' => $validated['delivery_address'],
                'notes' => $validated['notes'] ?? null,
                'invoice_number' => $validated['invoice_number'] ?? null,
                'invoice_date' => $validated['invoice_date'] ?? null,
                'payment_method' => $validated['payment_method'] ?? null,
                'status' => 'pending',
            ]);

            // Create items
            foreach ($validated['items'] as $item) {
                $deliveryNote->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                ]);
            }

            DB::commit();

            // Notify owner
            Notification::notifyDeliveryNoteCreated($deliveryNote);

            return redirect()
                ->route('delivery-notes.show', $deliveryNote)
                ->with('success', 'Surat Jalan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat Surat Jalan: ' . $e->getMessage());
        }
    }

    public function show(DeliveryNote $deliveryNote)
    {
        $this->authorizeAccess();

        $deliveryNote->load(['customer', 'invoice', 'items.product', 'creator', 'approver']);

        return view('delivery-notes.show', compact('deliveryNote'));
    }

    public function print(DeliveryNote $deliveryNote)
    {
        $this->authorizeAccess();

        // Can only print if approved or delivered
        if (!in_array($deliveryNote->status, ['approved', 'delivered'])) {
            return back()->with('error', 'Surat Jalan harus diapprove terlebih dahulu sebelum bisa dicetak.');
        }

        $deliveryNote->load(['customer', 'invoice', 'items.product', 'creator', 'approver']);

        return view('delivery-notes.print', compact('deliveryNote'));
    }

    public function approve(DeliveryNote $deliveryNote)
    {
        // Only Owner can approve
        if (!auth()->user()->isOwner()) {
            abort(403, 'Hanya Owner yang dapat approve Surat Jalan.');
        }

        if ($deliveryNote->status !== 'pending') {
            return back()->with('error', 'Surat Jalan sudah diproses sebelumnya.');
        }

        $deliveryNote->approve(auth()->id());

        return back()->with('success', 'Surat Jalan berhasil disetujui!');
    }

    public function markDelivered(DeliveryNote $deliveryNote)
    {
        $this->authorizeAccess();

        if ($deliveryNote->status === 'delivered') {
            return back()->with('error', 'Surat Jalan sudah ditandai sebagai terkirim.');
        }

        // Can only mark delivered if approved
        if ($deliveryNote->status === 'pending') {
            return back()->with('error', 'Surat Jalan harus diapprove terlebih dahulu.');
        }

        $deliveryNote->markAsDelivered();

        // Notify finance that goods are delivered (ready for invoice)
        Notification::notifyDeliveryNoteDelivered($deliveryNote);

        return back()->with('success', 'Surat Jalan berhasil ditandai sebagai terkirim!');
    }
}
