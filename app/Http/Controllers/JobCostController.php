<?php

namespace App\Http\Controllers;

use App\Models\JobCost;
use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;

class JobCostController extends Controller
{
    /**
     * Display a listing of job costs.
     */
    public function index(Request $request)
    {
        $query = JobCost::with(['creator', 'approver']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('job_cost_number', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $jobCosts = $query->latest()->paginate(15)->withQueryString();

        return view('job-costs.index', compact('jobCosts'));
    }

    /**
     * Show pending job costs for approval
     */
    public function pending()
    {
        $jobCosts = JobCost::with(['creator', 'items.product'])
            ->pending()
            ->latest()
            ->paginate(15);

        return view('job-costs.pending', compact('jobCosts'));
    }

    /**
     * Show the form for creating a new job cost.
     */
    public function create()
    {
        $products = Product::active()->orderBy('name')->get();
        return view('job-costs.create', compact('products'));
    }

    /**
     * Store a newly created job cost.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.notes' => 'nullable|string|max:255',
        ]);

        $jobCost = JobCost::create([
            'date' => $validated['date'],
            'description' => $validated['description'],
            'notes' => $validated['notes'],
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        foreach ($validated['items'] as $item) {
            $jobCost->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('job-costs.show', $jobCost)
            ->with('success', 'Job Cost berhasil dibuat.');
    }

    /**
     * Display the specified job cost.
     */
    public function show(JobCost $jobCost)
    {
        $jobCost->load(['creator', 'approver', 'items.product']);
        return view('job-costs.show', compact('jobCost'));
    }

    /**
     * Show the form for editing the job cost.
     */
    public function edit(JobCost $jobCost)
    {
        if (!in_array($jobCost->status, ['draft', 'pending'])) {
            return back()->with('error', 'Job Cost tidak dapat diedit.');
        }

        $jobCost->load('items.product');
        $products = Product::active()->orderBy('name')->get();

        return view('job-costs.edit', compact('jobCost', 'products'));
    }

    /**
     * Update the specified job cost.
     */
    public function update(Request $request, JobCost $jobCost)
    {
        if (!in_array($jobCost->status, ['draft', 'pending'])) {
            return back()->with('error', 'Job Cost tidak dapat diedit.');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.notes' => 'nullable|string|max:255',
        ]);

        $jobCost->update([
            'date' => $validated['date'],
            'description' => $validated['description'],
            'notes' => $validated['notes'],
        ]);

        // Delete existing items and recreate
        $jobCost->items()->delete();
        foreach ($validated['items'] as $item) {
            $jobCost->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('job-costs.show', $jobCost)
            ->with('success', 'Job Cost berhasil diperbarui.');
    }

    /**
     * Submit for approval.
     */
    public function submit(JobCost $jobCost)
    {
        if ($jobCost->status !== 'draft') {
            return back()->with('error', 'Hanya draft yang dapat diajukan.');
        }

        $jobCost->update(['status' => 'pending']);

        // Notify owner
        Notification::notifyJobCostPending($jobCost);

        return back()->with('success', 'Job Cost diajukan untuk persetujuan.');
    }

    /**
     * Approve the job cost.
     */
    public function approve(JobCost $jobCost)
    {
        if ($jobCost->status !== 'pending') {
            return back()->with('error', 'Job Cost tidak dalam status pending.');
        }

        $jobCost->approve(auth()->user());

        // Notify creator
        Notification::notifyJobCostApproved($jobCost);

        return back()->with('success', 'Job Cost disetujui dan stok sudah dikurangi.');
    }

    /**
     * Reject the job cost.
     */
    public function reject(JobCost $jobCost)
    {
        if ($jobCost->status !== 'pending') {
            return back()->with('error', 'Job Cost tidak dalam status pending.');
        }

        $jobCost->reject(auth()->user());

        return back()->with('success', 'Job Cost ditolak.');
    }
}
