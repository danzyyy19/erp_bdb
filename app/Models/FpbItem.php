<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FpbItem extends Model
{
    protected $fillable = [
        'fpb_id',
        'product_id',
        'quantity_requested',
        'unit',
        'notes',
    ];

    protected $casts = [
        'quantity_requested' => 'decimal:2',
    ];

    public function fpb(): BelongsTo
    {
        return $this->belongsTo(Fpb::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
