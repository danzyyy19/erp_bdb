<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class JobCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'job_cost_number',
        'date',
        'description',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobCost) {
            if (empty($jobCost->uuid)) {
                $jobCost->uuid = Str::uuid()->toString();
            }
            if (empty($jobCost->job_cost_number)) {
                $jobCost->job_cost_number = self::generateNumber();
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
     * Generate job cost number
     */
    public static function generateNumber(): string
    {
        $prefix = 'JC-' . date('Ym');
        $lastJobCost = self::where('job_cost_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastJobCost ? (int) substr($lastJobCost->job_cost_number, -4) + 1 : 1;
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
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
        return $this->hasMany(JobCostItem::class);
    }

    /**
     * Approve job cost and deduct stock
     */
    public function approve(User $user): bool
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        // Deduct stock for each item
        foreach ($this->items as $item) {
            $item->product->reduceStock(
                $item->quantity,
                $user->id,
                'job_cost',
                $this->job_cost_number,
                $this->description . ($item->notes ? ': ' . $item->notes : '')
            );
        }

        return true;
    }

    /**
     * Reject job cost
     */
    public function reject(User $user): bool
    {
        return $this->update(['status' => 'rejected']);
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

    /**
     * Status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'pending' => 'Pending',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => $this->status,
        };
    }
}
