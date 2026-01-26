<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;

class Fpb extends Model
{
    use HasUuids;

    protected $table = 'fpb';

    protected $fillable = [
        'fpb_number',
        'spk_id',
        'special_order_id',
        'created_by',
        'approved_by',
        'status',
        'request_date',
        'notes',
        'approved_at',
    ];

    protected $casts = [
        'request_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function spk(): BelongsTo
    {
        return $this->belongsTo(Spk::class);
    }

    public function specialOrder(): BelongsTo
    {
        return $this->belongsTo(SpecialOrder::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(FpbItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Helper methods
    /**
     * Generate unique FPB number
     * Format: FPB/XXX/BDB/[Roman Month]/YY
     */
    public static function generateFpbNumber(): string
    {
        $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $year = date('y');
        $month = (int) date('n');
        $romanMonth = $romans[$month - 1];

        $lastFpb = static::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastFpb) {
            $parts = explode('/', $lastFpb->fpb_number);
            $sequence = isset($parts[1]) ? ((int) $parts[1]) + 1 : 1;
        } else {
            $sequence = 1;
        }

        return sprintf('FPB/%03d/BDB/%s/%s', $sequence, $romanMonth, $year);
    }

    public function approve(int $userId): void
    {
        DB::transaction(function () use ($userId) {
            // Update FPB status
            $this->update([
                'status' => 'approved',
                'approved_by' => $userId,
                'approved_at' => now(),
            ]);

            // Reduce stock for each item
            foreach ($this->items as $item) {
                $note = "Permintaan FPB-{$this->fpb_number}";
                if ($this->spk) {
                    $note .= " untuk SPK-{$this->spk->spk_number}";
                }

                $item->product->reduceStock(
                    $item->quantity_requested,
                    $userId,
                    'fpb',
                    $this->id,
                    $note
                );
            }
        });
    }

    public function reject(int $userId): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Approval',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }
}
