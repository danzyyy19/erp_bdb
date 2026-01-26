<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property \Illuminate\Support\Carbon|null $production_date
 * @property \Illuminate\Support\Carbon|null $deadline
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 */
class Spk extends Model
{
    use HasFactory;

    protected $table = 'spk';

    protected $fillable = [
        'uuid',
        'spk_number',
        'spk_type',
        'created_by',
        'approved_by',
        'status',
        'production_date',
        'deadline',
        'notes',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [
        'production_date' => 'date',
        'deadline' => 'date',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the route key name for Laravel
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($spk) {
            if (empty($spk->uuid)) {
                $spk->uuid = Str::uuid()->toString();
            }
            if (empty($spk->spk_number)) {
                $spk->spk_number = self::generateSpkNumber();
            }
        });
    }

    /**
     * Generate unique SPK number
     * Format: SPK/XXX/BDB/[Roman Month]/YY
     */
    public static function generateSpkNumber(): string
    {
        $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $year = date('y');
        $month = (int) date('n');
        $romanMonth = $romans[$month - 1];

        $lastSpk = self::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSpk) {
            // Extract sequence from format SPK/XXX/BDB/XII/24
            $parts = explode('/', $lastSpk->spk_number);
            $sequence = isset($parts[1]) ? ((int) $parts[1]) + 1 : 1;
        } else {
            $sequence = 1;
        }

        return sprintf('SPK/%03d/BDB/%s/%s', $sequence, $romanMonth, $year);
    }

    /**
     * Get the creator
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the approver
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get SPK items
     */
    public function items(): HasMany
    {
        return $this->hasMany(SpkItem::class);
    }

    /**
     * Get bahan baku items
     */
    public function bahanBakuItems(): HasMany
    {
        return $this->items()->where('item_type', 'bahan_baku');
    }

    /**
     * Get packaging items
     */
    public function packagingItems(): HasMany
    {
        return $this->items()->where('item_type', 'packaging');
    }

    /**
     * Get output items (barang jadi)
     */
    public function outputItems(): HasMany
    {
        return $this->items()->where('item_type', 'output');
    }

    /**
     * Get production logs
     */
    public function productionLogs(): HasMany
    {
        return $this->hasMany(SpkProductionLog::class);
    }

    /**
     * Get delivery notes created from this SPK
     */
    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(DeliveryNote::class);
    }

    /**
     * Get FPBs created from this SPK
     */
    public function fpbs(): HasMany
    {
        return $this->hasMany(Fpb::class);
    }

    /**
     * Scope: Base SPKs
     */
    public function scopeBase($query)
    {
        return $query->where('spk_type', 'base');
    }

    /**
     * Get overall progress percentage for output items
     */
    public function getOverallProgressAttribute(): float
    {
        $outputItems = $this->outputItems;

        if ($outputItems->isEmpty()) {
            return 0;
        }

        $totalPlanned = $outputItems->sum('quantity_planned');
        $totalUsed = $outputItems->sum('quantity_used');

        if ($totalPlanned <= 0) {
            return 100;
        }

        return min(100, round(($totalUsed / $totalPlanned) * 100, 1));
    }

    /**
     * Check if SPK can be marked as complete
     */
    public function canComplete(): bool
    {
        $outputItems = $this->outputItems;

        if ($outputItems->isEmpty()) {
            return false;
        }

        // All output items must be 100% complete
        foreach ($outputItems as $item) {
            if ($item->quantity_used < $item->quantity_planned) {
                return false;
            }
        }

        return true;
    }

    /**
     * Add production entry for an output item
     */
    public function addProductionEntry(SpkItem $item, float $quantity, User $user, ?string $notes = null): ?ProductionLog
    {
        // Validate: output item only
        if (!$item->isOutput()) {
            return null;
        }

        // Validate: not exceeding target
        $remaining = $item->remaining_quantity;
        if ($quantity > $remaining) {
            return null;
        }

        // Update quantity_used
        $item->increment('quantity_used', $quantity);

        // Create production log
        return $this->productionLogs()->create([
            'spk_item_id' => $item->id,
            'user_id' => $user->id,
            'action' => 'production_entry',
            'quantity_produced' => $quantity,
            'notes' => $notes,
        ]);
    }

    /**
     * Check if SPK needs approval
     */
    public function needsApproval(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if SPK is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved' || $this->status === 'in_progress' || $this->status === 'completed';
    }

    /**
     * Approve the SPK
     */
    public function approve(User $approver): bool
    {
        if (!$approver->isOwner()) {
            return false;
        }

        $this->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);

        return true;
    }

    /**
     * Reject the SPK
     */
    public function reject(User $approver, ?string $notes = null): bool
    {
        if (!$approver->isOwner()) {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'notes' => $notes ?: $this->notes,
        ]);

        return true;
    }

    /**
     * Start production
     */
    public function startProduction(User $user): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }

        $this->update(['status' => 'in_progress']);

        return true;
    }

    /**
     * Complete production
     */
    public function completeProduction(User $user, array $consumedMaterials = [], array $producedItems = []): bool
    {
        if ($this->status !== 'in_progress') {
            return false;
        }

        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return true;
    }

    /**
     * Scope for pending SPKs
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved SPKs
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for in progress SPKs
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope for completed SPKs
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Approval',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'in_progress' => 'Dalam Proses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'approved' => 'blue',
            'rejected' => 'red',
            'in_progress' => 'indigo',
            'completed' => 'green',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }
}
