<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get products in this category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope for bahan baku
     */
    public function scopeBahanBaku($query)
    {
        return $query->where('type', 'bahan_baku');
    }

    /**
     * Scope for packaging
     */
    public function scopePackaging($query)
    {
        return $query->where('type', 'packaging');
    }

    /**
     * Scope for barang jadi
     */
    public function scopeBarangJadi($query)
    {
        return $query->where('type', 'barang_jadi');
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
