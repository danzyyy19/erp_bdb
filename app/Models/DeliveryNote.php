<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property \Illuminate\Support\Carbon|null $delivery_date
 * @property \Illuminate\Support\Carbon|null $delivered_at
 * @property \Illuminate\Support\Carbon|null $approved_at
 */
class DeliveryNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'sj_number',
        'customer_id',
        'invoice_id',
        'invoice_number',
        'payment_method',
        'invoice_date',
        'created_by',
        'approved_by',
        'delivery_date',
        'driver_name',
        'vehicle_number',
        'recipient_name',
        'delivery_address',
        'notes',
        'status',
        'delivered_at',
        'approved_at',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'invoice_date' => 'date',
        'delivered_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
            if (empty($model->sj_number)) {
                $model->sj_number = self::generateSjNumber();
            }
        });
    }

    /**
     * Get the route key name for Laravel
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
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
        return $this->hasMany(DeliveryNoteItem::class);
    }

    // Methods
    /**
     * Generate unique Surat Jalan number
     * Format: SJ/XXX/BDB/[Roman Month]/YY
     */
    public static function generateSjNumber(): string
    {
        $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $year = date('y');
        $month = (int) date('n');
        $romanMonth = $romans[$month - 1];

        $lastSj = self::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSj) {
            $parts = explode('/', $lastSj->sj_number);
            $sequence = isset($parts[1]) ? ((int) $parts[1]) + 1 : 1;
        } else {
            $sequence = 1;
        }

        return sprintf('SJ/%03d/BDB/%s/%s', $sequence, $romanMonth, $year);
    }

    public function approve(int $userId): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
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

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Approval',
            'approved' => 'Disetujui',
            'in_transit' => 'Dalam Perjalanan',
            'delivered' => 'Terkirim',
            'returned' => 'Dikembalikan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'approved' => 'blue',
            'in_transit' => 'indigo',
            'delivered' => 'green',
            'returned' => 'red',
            default => 'gray',
        };
    }
}

