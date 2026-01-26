<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpkItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_id',
        'product_id',
        'item_type',
        'quantity_planned',
        'quantity_used',
        'unit',
        'notes',
    ];

    protected $casts = [
        'quantity_planned' => 'decimal:2',
        'quantity_used' => 'decimal:2',
    ];

    /**
     * Get the SPK
     */
    public function spk(): BelongsTo
    {
        return $this->belongsTo(Spk::class);
    }

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get production logs for this item
     */
    public function productionLogs(): HasMany
    {
        return $this->hasMany(SpkProductionLog::class);
    }

    /**
     * Get total produced quantity from logs
     */
    public function getTotalProducedAttribute(): float
    {
        return (float) $this->productionLogs()->sum('quantity');
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->quantity_planned <= 0) {
            return 100;
        }
        return min(100, round(($this->quantity_used / $this->quantity_planned) * 100, 1));
    }

    /**
     * Get remaining quantity
     */
    public function getRemainingQuantityAttribute(): float
    {
        return max(0, $this->quantity_planned - $this->quantity_used);
    }

    /**
     * Check if completed
     */
    public function isCompleted(): bool
    {
        return $this->quantity_used >= $this->quantity_planned;
    }

    /**
     * Check if this is bahan baku
     */
    public function isBahanBaku(): bool
    {
        return $this->item_type === 'bahan_baku';
    }

    /**
     * Check if this is packaging
     */
    public function isPackaging(): bool
    {
        return $this->item_type === 'packaging';
    }

    /**
     * Check if this is output
     */
    public function isOutput(): bool
    {
        return $this->item_type === 'output';
    }

    /**
     * Scope for bahan baku
     */
    public function scopeBahanBaku($query)
    {
        return $query->where('item_type', 'bahan_baku');
    }

    /**
     * Scope for packaging
     */
    public function scopePackaging($query)
    {
        return $query->where('item_type', 'packaging');
    }

    /**
     * Scope for output
     */
    public function scopeOutput($query)
    {
        return $query->where('item_type', 'output');
    }
}
