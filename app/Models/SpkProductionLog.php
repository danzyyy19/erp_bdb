<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpkProductionLog extends Model
{
    protected $fillable = [
        'spk_id',
        'spk_item_id',
        'quantity',
        'work_date',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'work_date' => 'date',
    ];

    public function spk(): BelongsTo
    {
        return $this->belongsTo(Spk::class);
    }

    public function spkItem(): BelongsTo
    {
        return $this->belongsTo(SpkItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
