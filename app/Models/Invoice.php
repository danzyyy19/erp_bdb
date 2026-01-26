<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property \Illuminate\Support\Carbon|null $invoice_date
 * @property \Illuminate\Support\Carbon|null $due_date
 */
class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'invoice_number',
        'customer_id',
        'delivery_note_id',
        'created_by',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_percent',
        'tax_amount',
        'discount',
        'total',
        'paid_amount',
        'status',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'approved_at' => 'datetime',
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

        static::creating(function ($invoice) {
            if (empty($invoice->uuid)) {
                $invoice->uuid = Str::uuid()->toString();
            }
            if (empty($invoice->invoice_number)) {
                $customer = $invoice->customer_id ? Customer::find($invoice->customer_id) : null;
                $invoice->invoice_number = self::generateInvoiceNumber($customer);
            }
        });
    }

    /**
     * Generate unique invoice number
     * Format: [CustCode]/XXX/BDB/[Roman Month]/YYYY
     */
    public static function generateInvoiceNumber(?Customer $customer = null): string
    {
        $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $year = date('Y');
        $month = (int) date('n');
        $romanMonth = $romans[$month - 1];

        $customerCode = $customer?->code ?? 'XXX';

        $lastInvoice = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $parts = explode('/', $lastInvoice->invoice_number);
            $sequence = isset($parts[1]) ? ((int) $parts[1]) + 1 : 1;
        } else {
            $sequence = 1;
        }

        return sprintf('%s/%03d/BDB/%s/%s', $customerCode, $sequence, $romanMonth, $year);
    }

    /**
     * Get the customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the delivery note
     */
    public function deliveryNote(): BelongsTo
    {
        return $this->belongsTo(DeliveryNote::class);
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
     * Get invoice items
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Calculate totals
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->items()->sum('subtotal');
        $taxAmount = $subtotal * ($this->tax_percent / 100);
        $total = $subtotal + $taxAmount - $this->discount;

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total,
        ]);
    }

    /**
     * Check if overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !in_array($this->status, ['paid', 'cancelled']);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'pending_approval' => 'Menunggu Persetujuan',
            'sent' => 'Terkirim',
            'paid' => 'Lunas',
            'partial' => 'Sebagian',
            'overdue' => 'Jatuh Tempo',
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
            'draft' => 'gray',
            'pending_approval' => 'orange',
            'sent' => 'blue',
            'paid' => 'green',
            'partial' => 'yellow',
            'overdue' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Check if pending approval
     */
    public function isPendingApproval(): bool
    {
        return $this->status === 'pending_approval';
    }

    /**
     * Approve the invoice
     */
    public function approve(User $user): void
    {
        $this->update([
            'status' => 'sent',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject the invoice
     */
    public function reject(): void
    {
        $this->update([
            'status' => 'cancelled',
        ]);
    }

    /**
     * Scope for pending approval
     */
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending_approval');
    }

    /**
     * Scope for status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for overdue
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['paid', 'cancelled']);
    }

    /**
     * Get remaining amount to pay
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float) $this->total - (float) $this->paid_amount);
    }

    /**
     * Check if fully paid
     */
    public function isFullyPaid(): bool
    {
        return $this->remaining_amount <= 0;
    }

    /**
     * Add payment to invoice
     */
    public function addPayment(float $amount): void
    {
        $newPaidAmount = (float) $this->paid_amount + $amount;

        if ($newPaidAmount >= (float) $this->total) {
            $this->update([
                'paid_amount' => $this->total,
                'status' => 'paid'
            ]);
        } else {
            $this->update([
                'paid_amount' => $newPaidAmount,
                'status' => 'partial'
            ]);
        }
    }
}
