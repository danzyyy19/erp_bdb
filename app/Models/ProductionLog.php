<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_id',
        'spk_item_id',
        'user_id',
        'action',
        'quantity_produced',
        'notes',
        'consumed_materials',
        'produced_items',
    ];

    protected $casts = [
        'consumed_materials' => 'array',
        'produced_items' => 'array',
        'quantity_produced' => 'decimal:2',
    ];

    /**
     * Get the SPK
     */
    public function spk(): BelongsTo
    {
        return $this->belongsTo(Spk::class);
    }

    /**
     * Get the SPK Item
     */
    public function spkItem(): BelongsTo
    {
        return $this->belongsTo(SpkItem::class);
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get action label
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'started' => 'Produksi Dimulai',
            'paused' => 'Produksi Dijeda',
            'resumed' => 'Produksi Dilanjutkan',
            'completed' => 'Produksi Selesai',
            'cancelled' => 'Produksi Dibatalkan',
            'production_entry' => 'Input Produksi',
            default => $this->action,
        };
    }

    /**
     * Get action color
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'started' => 'blue',
            'paused' => 'yellow',
            'resumed' => 'indigo',
            'completed' => 'green',
            'cancelled' => 'red',
            'production_entry' => 'purple',
            default => 'gray',
        };
    }

    /**
     * Scope by action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for production entries
     */
    public function scopeProductionEntries($query)
    {
        return $query->where('action', 'production_entry');
    }
}
