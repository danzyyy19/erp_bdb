<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'purchase_number',
        'supplier_id',
        'purchase_date',
        'subtotal',
        'tax',
        'discount',
        'total_amount',
        'status',
        'payment_terms',
        'created_by',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'approved_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            if (empty($purchase->uuid)) {
                $purchase->uuid = Str::uuid()->toString();
            }
            if (empty($purchase->purchase_number)) {
                $purchase->purchase_number = self::generateNumber();
            }
        });
    }

    /**
     * Get the route key name
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Generate purchase number
     */
    public static function generateNumber(): string
    {
        $prefix = 'PO-' . date('Ym');
        $lastPurchase = self::where('purchase_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastPurchase ? (int) substr($lastPurchase->purchase_number, -4) + 1 : 1;
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get supplier
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get creator
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get approver
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get items
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Calculate totals
     */
    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('total');
        $this->total_amount = $this->subtotal + $this->tax - $this->discount;
        $this->save();
    }

    /**
     * Approve purchase
     */
    public function approve(User $user): bool
    {
        return $this->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);
    }

    /**
     * Mark as received and update stock
     */
    public function markReceived(User $user): bool
    {
        $this->update(['status' => 'received']);

        // Update stock for each item
        foreach ($this->items as $item) {
            $item->product->addStock(
                $item->quantity,
                $user->id,
                'purchase',
                $this->purchase_number,
                'Penerimaan dari ' . $this->supplier->name
            );
        }

        return true;
    }

    /**
     * Scopes
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    /**
     * Status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'received' => 'Diterima',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }
    /**
     * Get tax percentage accessor
     */
    public function getTaxPercentageAttribute(): float
    {
        return ($this->subtotal > 0) ? round(($this->tax / $this->subtotal * 100), 2) : 0;
    }
}
