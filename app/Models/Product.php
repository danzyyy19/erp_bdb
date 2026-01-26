<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'code',
        'name',
        'category_id',
        'spec_type',
        'current_stock',
        'min_stock',
        'unit',
        'unit_packing',
        'purchase_price',
        'selling_price',
        'supplier_type',
        'description',
        'is_active',
        'approval_status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
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

        static::creating(function ($product) {
            if (empty($product->uuid)) {
                $product->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the creator (user who added this product)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the approver (user who approved this product)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get stock movements
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get SPK items
     */
    public function spkItems(): HasMany
    {
        return $this->hasMany(SpkItem::class);
    }

    /**
     * Check if stock is low
     */
    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->min_stock;
    }

    /**
     * Check if pending approval
     */
    public function isPendingApproval(): bool
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if approved
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Approve this product
     */
    public function approve(User $user): bool
    {
        return $this->update([
            'approval_status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'is_active' => true,
        ]);
    }

    /**
     * Reject this product
     */
    public function reject(User $user): bool
    {
        return $this->update([
            'approval_status' => 'rejected',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'is_active' => false,
        ]);
    }

    /**
     * Request deletion (for Operasional role)
     */
    public function requestDeletion(User $user): bool
    {
        // Can only request deletion for approved products
        if ($this->approval_status !== 'approved') {
            return false;
        }

        return $this->update([
            'approval_status' => 'pending_deletion',
            'created_by' => $user->id, // Track who requested deletion
        ]);
    }

    /**
     * Complete deletion (Owner approves deletion request)
     */
    public function completeDeletion(User $user): bool
    {
        if (!$user->isOwner()) {
            return false;
        }

        return $this->update([
            'is_active' => false,
            'approval_status' => 'deleted',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);
    }

    /**
     * Cancel deletion request
     */
    public function cancelDeletion(User $user): bool
    {
        if (!$user->isOwner()) {
            return false;
        }

        return $this->update([
            'approval_status' => 'approved',
        ]);
    }

    /**
     * Scope for active products (approved and active)
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('approval_status', 'approved');
    }

    /**
     * Scope for pending approval
     */
    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope for pending deletion
     */
    public function scopePendingDeletion($query)
    {
        return $query->where('approval_status', 'pending_deletion');
    }

    /**
     * Scope for low stock products
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'min_stock');
    }

    /**
     * Scope for bahan baku
     */
    public function scopeBahanBaku($query)
    {
        return $query->whereHas('category', fn($q) => $q->where('type', 'bahan_baku'));
    }

    /**
     * Scope for high spec
     */
    public function scopeHighSpec($query)
    {
        return $query->where('spec_type', 'high_spec');
    }

    /**
     * Scope for medium spec
     */
    public function scopeMediumSpec($query)
    {
        return $query->where('spec_type', 'medium_spec');
    }

    /**
     * Scope for packaging
     */
    public function scopePackaging($query)
    {
        return $query->whereHas('category', fn($q) => $q->where('type', 'packaging'));
    }

    /**
     * Scope for barang jadi
     */
    public function scopeBarangJadi($query)
    {
        return $query->whereHas('category', fn($q) => $q->where('type', 'barang_jadi'));
    }

    /**
     * Add stock
     */
    public function addStock(float $quantity, int $userId, string $referenceType = 'manual', int|string|null $referenceId = null, ?string $notes = null): StockMovement
    {
        $stockBefore = $this->current_stock;
        $this->increment('current_stock', $quantity);

        return $this->stockMovements()->create([
            'user_id' => $userId,
            'type' => 'in',
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $this->current_stock,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
        ]);
    }

    /**
     * Reduce stock
     */
    public function reduceStock(float $quantity, int $userId, string $referenceType = 'manual', int|string|null $referenceId = null, ?string $notes = null): StockMovement
    {
        $stockBefore = $this->current_stock;
        $this->decrement('current_stock', $quantity);

        return $this->stockMovements()->create([
            'user_id' => $userId,
            'type' => 'out',
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $this->current_stock,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
        ]);
    }
}
