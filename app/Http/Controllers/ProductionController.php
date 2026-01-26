<?php

namespace App\Http\Controllers;

use App\Models\Spk;
use App\Models\ProductionLog;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function report(Request $request)
    {
        $query = Spk::with(['creator', 'items.product'])
            ->whereIn('status', ['in_progress', 'completed']);

        if ($request->filled('date_from')) {
            $query->whereDate('production_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('production_date', '<=', $request->date_to);
        }

        if ($request->filled('month')) {
            $query->whereMonth('production_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('production_date', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $spks = $query->latest('production_date')->paginate(15)->withQueryString();

        // Summary stats
        $summary = [
            'total' => $spks->total(),
            'in_progress' => Spk::inProgress()->count(),
            'completed' => Spk::completed()->count(),
        ];

        return view('production.report', compact('spks', 'summary'));
    }

    public function history(Request $request)
    {
        $query = ProductionLog::with(['spk', 'user']);

        if ($request->filled('spk_id')) {
            $query->where('spk_id', $request->spk_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(20)->withQueryString();
        $spks = Spk::orderBy('spk_number', 'desc')->get();

        return view('production.history', compact('logs', 'spks'));
    }
}
