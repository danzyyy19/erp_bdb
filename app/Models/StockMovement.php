<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'reference_type',
        'reference_id',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'stock_before' => 'decimal:2',
        'stock_after' => 'decimal:2',
    ];

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reference model
     */
    public function reference()
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        return match ($this->reference_type) {
            'spk' => Spk::find($this->reference_id),
            'invoice' => Invoice::find($this->reference_id),
            default => null,
        };
    }

    /**
     * Check if this is stock in
     */
    public function isStockIn(): bool
    {
        return in_array($this->type, ['in', 'production_in', 'adjustment']) && $this->stock_after > $this->stock_before;
    }

    /**
     * Check if this is stock out
     */
    public function isStockOut(): bool
    {
        return in_array($this->type, ['out', 'production_out']) || $this->stock_after < $this->stock_before;
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'in' => 'Masuk',
            'out' => 'Keluar',
            'adjustment' => 'Penyesuaian',
            'production_in' => 'Hasil Produksi',
            'production_out' => 'Konsumsi Produksi',
            default => $this->type,
        };
    }

    /**
     * Scope for stock in
     */
    public function scopeStockIn($query)
    {
        return $query->whereIn('type', ['in', 'production_in']);
    }

    /**
     * Scope for stock out
     */
    public function scopeStockOut($query)
    {
        return $query->whereIn('type', ['out', 'production_out']);
    }

    /**
     * Scope by reference
     */
    public function scopeByReference($query, string $type, int $id)
    {
        return $query->where('reference_type', $type)->where('reference_id', $id);
    }
}
