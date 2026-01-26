<?php

namespace App\Http\Controllers;

use App\Models\Spk;
use App\Models\SpkItem;
use App\Models\SpkProductionLog;
use App\Models\Product;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SpkController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Spk::with(['creator', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('spk_number', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->filled('customer')) {
            if ($request->customer === 'base') {
                $query->where('spk_type', 'base');
            }
        }

        $spks = $query->latest()->paginate(10)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('spk.partials.spk-table', compact('spks'))->render(),
                'mobile_html' => view('spk.partials.spk-cards', compact('spks'))->render(),
                'pagination' => $spks->links()->toHtml(),
            ]);
        }

        return view('spk.index', compact('spks'));
    }

    public function pending()
    {
        $this->authorize('viewPending', Spk::class);

        // Load all pending SPKs for client-side instant filtering (no reload)
        $spksCollection = Spk::with(['creator', 'items.product'])
            ->pending()
            ->latest()
            ->get();

        // Map data to avoid Blade arrow function syntax issues
        $spks = $spksCollection->map(function ($spk) {
            return [
                'id' => $spk->id,
                'uuid' => $spk->uuid,
                'spk_number' => $spk->spk_number,
                'spk_type' => $spk->spk_type,
                'creator_name' => $spk->creator->name ?? '-',
                'production_date' => $spk->production_date?->format('d M Y'),
                'deadline' => $spk->deadline?->format('d M Y'),
                'created_at_human' => $spk->created_at->diffForHumans(),
                'items_count' => $spk->items->count(),
                'notes' => $spk->notes,
            ];
        });

        return view('spk.pending', compact('spks'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Spk::class);

        $bahanBaku = Product::active()->bahanBaku()->get()->map(fn($p) => [
            'id' => $p->id,
            'code' => $p->code,
            'name' => $p->name,
            'stock' => number_format((float) $p->current_stock) . ' ' . $p->unit
        ]);
        $packaging = Product::active()->packaging()->get()->map(fn($p) => [
            'id' => $p->id,
            'code' => $p->code,
            'name' => $p->name,
            'stock' => number_format((float) $p->current_stock) . ' ' . $p->unit
        ]);
        $barangJadi = Product::active()->barangJadi()->get()->map(fn($p) => [
            'id' => $p->id,
            'code' => $p->code,
            'name' => $p->name,
            'unit' => $p->unit
        ]);

        return view('spk.create', compact('bahanBaku', 'packaging', 'barangJadi'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Spk::class);

        // Validation rules - SPK is now always base type
        $request->validate([
            'production_date' => 'required|date',
            'deadline' => 'nullable|date|after_or_equal:production_date',
            'notes' => 'nullable|string',
            'output' => 'required|array|min:1',
            'output.*.product_id' => 'required|exists:products,id',
            'output.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $user = auth()->user();

        DB::transaction(function () use ($request, $user) {
            // Create SPK
            $spk = Spk::create([
                'spk_type' => 'base',
                'created_by' => $user->id,
                'status' => $user->isOwner() ? 'approved' : 'pending',
                'approved_by' => $user->isOwner() ? $user->id : null,
                'approved_at' => $user->isOwner() ? now() : null,
                'production_date' => $request->production_date,
                'deadline' => $request->deadline,
                'notes' => $request->notes,
            ]);

            // Add output items
            foreach ($request->output as $item) {
                if (!empty($item['product_id']) && !empty($item['quantity'])) {
                    $product = Product::find($item['product_id']);
                    $spk->items()->create([
                        'product_id' => $item['product_id'],
                        'item_type' => 'output',
                        'quantity_planned' => $item['quantity'],
                        'unit' => $product->unit,
                    ]);
                }
            }

            // Notify owner if created by operasional
            if (!$user->isOwner()) {
                Notification::notifySpkPending($spk);
            }
        });

        return redirect()->route('spk.index')->with('success', 'SPK berhasil dibuat.');
    }

    public function show(Spk $spk)
    {
        $spk->load(['creator', 'approver', 'items.product', 'productionLogs.spkItem.product', 'productionLogs.creator']);

        return view('spk.show', compact('spk'));
    }

    public function edit(Spk $spk)
    {
        $this->authorize('update', $spk);

        // Only allow edit if status is pending
        if ($spk->status !== 'pending') {
            return redirect()->route('spk.show', $spk)
                ->with('info', 'SPK yang sudah diapprove tidak bisa diedit. Silakan buat SPK baru.');
        }

        // Load existing SPK with items
        $spk->load(['items.product']);

        // Load products for dropdowns
        $bahanBaku = Product::active()->bahanBaku()->get()->map(fn($p) => [
            'id' => $p->id,
            'code' => $p->code,
            'name' => $p->name,
            'stock' => number_format((float) $p->current_stock) . ' ' . $p->unit
        ]);
        $packaging = Product::active()->packaging()->get()->map(fn($p) => [
            'id' => $p->id,
            'code' => $p->code,
            'name' => $p->name,
            'stock' => number_format((float) $p->current_stock) . ' ' . $p->unit
        ]);
        $barangJadi = Product::active()->barangJadi()->get()->map(fn($p) => [
            'id' => $p->id,
            'code' => $p->code,
            'name' => $p->name,
            'unit' => $p->unit
        ]);

        // Prepare items data for JavaScript
        $outputItemsData = $spk->items->where('item_type', 'output')->map(fn($item) => [
            'product_id' => $item->product_id,
            'quantity' => $item->quantity_planned,
            'unit' => $item->unit ?? $item->product->unit ?? 'pcs',
            'search' => ($item->product->name ?? '') . ' (' . ($item->product->code ?? '') . ')',
            'showDropdown' => false
        ])->values()->toArray();

        if (empty($outputItemsData)) {
            $outputItemsData = [['product_id' => '', 'quantity' => '', 'unit' => '', 'search' => '', 'showDropdown' => false]];
        }

        return view('spk.edit', compact(
            'spk',
            'bahanBaku',
            'packaging',
            'barangJadi',
            'outputItemsData'
        ));
    }

    public function update(Request $request, Spk $spk)
    {
        $this->authorize('update', $spk);

        $request->validate([
            'production_date' => 'required|date',
            'deadline' => 'nullable|date|after_or_equal:production_date',
            'notes' => 'nullable|string',
        ]);

        $spk->update($request->only(['production_date', 'deadline', 'notes']));

        return redirect()->route('spk.show', $spk)->with('success', 'SPK berhasil diperbarui.');
    }

    public function destroy(Spk $spk)
    {
        $this->authorize('delete', $spk);

        $spk->delete();

        return redirect()->route('spk.index')->with('success', 'SPK berhasil dihapus.');
    }

    public function approve(Request $request, Spk $spk)
    {
        $this->authorize('approve', $spk);

        $user = auth()->user();

        if ($spk->approve($user)) {
            Notification::notifySpkApproved($spk);
            return redirect()->back()->with('success', 'SPK berhasil disetujui.');
        }

        return redirect()->back()->with('error', 'Gagal menyetujui SPK.');
    }

    public function reject(Request $request, Spk $spk)
    {
        $this->authorize('approve', $spk);

        $request->validate([
            'rejection_notes' => 'nullable|string',
        ]);

        $user = auth()->user();

        if ($spk->reject($user, $request->rejection_notes)) {
            Notification::notifySpkRejected($spk);
            return redirect()->back()->with('success', 'SPK berhasil ditolak.');
        }

        return redirect()->back()->with('error', 'Gagal menolak SPK.');
    }

    public function start(Spk $spk)
    {
        $this->authorize('start', $spk);

        $user = auth()->user();

        if ($spk->startProduction($user)) {
            Notification::notifySpkStarted($spk);
            return redirect()->back()->with('success', 'Produksi SPK dimulai. Silakan input log produksi harian.');
        }

        return redirect()->back()->with('error', 'Gagal memulai produksi.');
    }

    public function complete(Request $request, Spk $spk)
    {
        $this->authorize('complete', $spk);

        // Get output items with their production logs
        $outputItems = $spk->items()->where('item_type', 'output')->get();

        // Check if there are any production logs
        $hasLogs = false;
        foreach ($outputItems as $item) {
            if ($item->productionLogs()->count() > 0) {
                $hasLogs = true;
                break;
            }
        }

        if (!$hasLogs) {
            return redirect()->back()->with('error', 'SPK belum bisa diselesaikan. Belum ada log produksi.');
        }

        $user = auth()->user();

        DB::transaction(function () use ($spk, $user) {
            // NOTE: Material stock (bahan baku, packaging) is now reduced when FPB is approved
            // Here we only track quantity_used for reference
            foreach ($spk->items()->whereIn('item_type', ['bahan_baku', 'packaging'])->get() as $item) {
                $item->update(['quantity_used' => $item->quantity_planned]);
            }

            // 2. Add stock for output items based on ACTUAL produced (from logs)
            $producedItems = [];
            foreach ($spk->items()->where('item_type', 'output')->get() as $item) {
                $actualProduced = $item->total_produced; // Sum from production logs

                if ($actualProduced > 0) {
                    // Update quantity_used to actual produced
                    $item->update(['quantity_used' => $actualProduced]);

                    // Add all produced to stock
                    $note = "Hasil produksi SPK {$spk->spk_number}";

                    $item->product->addStock(
                        $actualProduced,
                        $user->id,
                        'spk',
                        $spk->id,
                        $note
                    );

                    $producedItems[] = [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => $actualProduced,
                    ];
                }
            }

            // 3. Complete SPK
            $spk->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Notify owner
            Notification::notifySpkCompleted($spk);
        });

        return redirect()->back()->with('success', 'Produksi SPK selesai! Stok bahan baku dikurangi dan stok barang jadi ditambahkan.');
    }

    public function addProductionLog(Request $request, Spk $spk)
    {
        // Only allow for in_progress SPK
        if ($spk->status !== 'in_progress') {
            return redirect()->back()->with('error', 'SPK tidak dalam status produksi.');
        }

        $request->validate([
            'spk_item_id' => 'required|exists:spk_items,id',
            'quantity' => 'required|numeric|min:0.01',
            'work_date' => 'required|date',
        ]);

        $item = SpkItem::findOrFail($request->spk_item_id);

        // Verify item belongs to this SPK and is output type
        if ($item->spk_id !== $spk->id || !$item->isOutput()) {
            return redirect()->back()->with('error', 'Item tidak valid. Hanya output yang bisa dicatat.');
        }

        $user = auth()->user();

        // Create production log
        SpkProductionLog::create([
            'spk_id' => $spk->id,
            'spk_item_id' => $item->id,
            'quantity' => $request->quantity,
            'work_date' => $request->work_date,
            'created_by' => $user->id,
        ]);

        // Calculate new total
        $totalProduced = $item->fresh()->total_produced;
        $target = $item->quantity_planned;
        $percentage = $target > 0 ? round(($totalProduced / $target) * 100, 1) : 0;

        return redirect()->back()->with('success', "Log produksi berhasil ditambahkan. Total: {$totalProduced} / {$target} ({$percentage}%)");
    }

    public function deleteProductionLog(SpkProductionLog $log)
    {
        $spk = $log->spk;

        // Only allow deletion if SPK is still in_progress
        if ($spk->status !== 'in_progress') {
            return redirect()->back()->with('error', 'Tidak bisa menghapus log. SPK sudah selesai.');
        }

        $log->delete();

        return redirect()->back()->with('success', 'Log produksi berhasil dihapus.');
    }

    public function print(Spk $spk)
    {
        $spk->load(['creator', 'approver', 'items.product']);

        return view('spk.print', compact('spk'));
    }
}
