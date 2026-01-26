<?php

namespace App\Http\Controllers;

use App\Models\Fpb;
use App\Models\FpbItem;
use App\Models\Spk;
use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FpbController extends Controller
{
    /**
     * Check if user can access FPB (Owner or Operasional)
     */
    private function authorizeAccess(): void
    {
        if (!in_array(auth()->user()->role, ['owner', 'operasional'])) {
            abort(403, 'Anda tidak memiliki akses ke FPB.');
        }
    }

    /**
     * Check if user can approve/reject FPB (Owner only)
     */
    private function authorizeApproval(): void
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Hanya Owner yang dapat approve/reject FPB.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAccess();

        $query = Fpb::with(['spk', 'creator', 'items'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fpb_number', 'like', "%{$search}%")
                    ->orWhereHas('spk', fn($q) => $q->where('spk_number', 'like', "%{$search}%"));
            });
        }

        $fpbs = $query->paginate(15)->withQueryString();

        $stats = [
            'pending' => Fpb::pending()->count(),
            'approved' => Fpb::approved()->count(),
            'total' => Fpb::count(),
        ];

        return view('fpb.index', compact('fpbs', 'stats'));
    }

    public function create(Request $request)
    {
        $this->authorizeAccess();

        // Only show SPKs that are approved or in_progress (not completed)
        // Also exclude SPKs that already have an FPB
        $spks = Spk::whereIn('status', ['approved', 'in_progress'])
            ->doesntHave('fpbs')
            ->with(['items.product'])
            ->latest()
            ->get();

        // Get bahan baku and packaging products for manual selection
        $materials = Product::where(function ($q) {
            $q->whereHas('category', fn($c) => $c->where('type', 'bahan_baku'))
                ->orWhereHas('category', fn($c) => $c->where('type', 'packaging'));
        })
            ->active()
            ->orderBy('name')
            ->get();

        $selectedSpk = null;
        if ($request->filled('spk_id')) {
            $selectedSpk = Spk::with(['items.product'])->find($request->spk_id);
        }

        return view('fpb.create', compact('spks', 'materials', 'selectedSpk'));
    }

    public function createFromSpk(Spk $spk)
    {
        $this->authorizeAccess();

        // Check if SPK is in valid status
        if (!in_array($spk->status, ['approved', 'in_progress'])) {
            return redirect()->route('spk.show', $spk)
                ->with('error', 'SPK harus dalam status approved atau in_progress untuk membuat FPB.');
        }

        // Check if SPK already has FPB
        if ($spk->fpbs()->exists()) {
            return redirect()->route('spk.show', $spk)
                ->with('error', 'SPK ini sudah memiliki FPB. Tidak dapat membuat FPB baru.');
        }

        $spk->load(['items.product']);

        // Get materials from SPK items (bahan_baku and packaging)
        $spkMaterials = $spk->items->whereIn('item_type', ['bahan_baku', 'packaging']);

        // Get all available materials for additional items
        $materials = Product::where(function ($q) {
            $q->whereHas('category', fn($c) => $c->where('type', 'bahan_baku'))
                ->orWhereHas('category', fn($c) => $c->where('type', 'packaging'));
        })
            ->active()
            ->orderBy('name')
            ->get();

        return view('fpb.create', [
            'selectedSpk' => $spk,
            'spkMaterials' => $spkMaterials,
            'materials' => $materials,
            'spks' => collect([$spk]),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAccess();

        $request->validate([
            'spk_id' => 'required|exists:spk,id',
            'request_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $spk = Spk::findOrFail($request->spk_id);

        // Check if SPK already has FPB
        if ($spk->fpbs()->exists()) {
            return redirect()->back()
                ->with('error', 'SPK ini sudah memiliki FPB. Tidak dapat membuat FPB baru.');
        }

        DB::transaction(function () use ($request, $spk) {
            $fpb = Fpb::create([
                'fpb_number' => Fpb::generateFpbNumber(),
                'spk_id' => $spk->id,
                'created_by' => auth()->id(),
                'status' => 'pending',
                'request_date' => $request->request_date,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                FpbItem::create([
                    'fpb_id' => $fpb->id,
                    'product_id' => $item['product_id'],
                    'quantity_requested' => $item['quantity'],
                    'unit' => $product->unit ?? 'pcs',
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            // Notify owner
            Notification::notifyFpbPending($fpb);
        });

        return redirect()->route('fpb.index')
            ->with('success', 'FPB berhasil dibuat dan menunggu approval.');
    }

    public function show(Fpb $fpb)
    {
        $this->authorizeAccess();

        $fpb->load(['spk.items.product', 'creator', 'approver', 'items.product']);

        return view('fpb.show', compact('fpb'));
    }

    public function approve(Fpb $fpb)
    {
        $this->authorizeApproval();

        if ($fpb->status !== 'pending') {
            return redirect()->back()->with('error', 'FPB sudah diproses sebelumnya.');
        }

        // Check stock availability
        foreach ($fpb->items as $item) {
            if ($item->product->current_stock < $item->quantity_requested) {
                return redirect()->back()
                    ->with('error', "Stok {$item->product->name} tidak mencukupi. Tersedia: {$item->product->current_stock}, Diminta: {$item->quantity_requested}");
            }
        }

        $fpb->approve(auth()->id());

        // Notify creator
        Notification::notifyFpbApproved($fpb);

        return redirect()->back()
            ->with('success', 'FPB disetujui! Stok material telah dikurangi.');
    }

    public function reject(Request $request, Fpb $fpb)
    {
        $this->authorizeApproval();

        if ($fpb->status !== 'pending') {
            return redirect()->back()->with('error', 'FPB sudah diproses sebelumnya.');
        }

        $fpb->reject(auth()->id());

        // Notify creator
        Notification::notifyFpbRejected($fpb);

        return redirect()->back()
            ->with('success', 'FPB ditolak.');
    }

    public function print(Fpb $fpb)
    {
        $this->authorizeAccess();

        $fpb->load(['spk', 'creator', 'approver', 'items.product']);

        return view('fpb.print', compact('fpb'));
    }
}
