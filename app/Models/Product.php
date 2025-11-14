<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'search_tag',
        'status',
        'image',
        'regular_price',
        'sale_price',
        'is_featured',
        'quantity'
    ];

    protected $attributes = [
        'status' => 1,
        'is_featured' => 0,
        'quantity' => 0
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'quantity' => 'integer'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function mainCategories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')
            ->where('parent_id', 0);
    }

     public function pricing()
    {
        return $this->hasOne(ProductPricing::class);
    }

    /**
     * Get final price with fallback
     */
    public function getFinalPriceAttribute()
    {
        return $this->pricing ? $this->pricing->final_price : 0;
    }

    /**
     * Get formatted final price
     */
    public function getFormattedFinalPriceAttribute()
    {
        return '₹' . number_format($this->final_price, 2);
    }

    /**
     * Get formatted base price
     */
    public function getFormattedBasePriceAttribute()
    {
        return '₹' . number_format($this->pricing->mrp_base_price ?? 0, 2);
    }

    /**
     * Check if product has pricing
     */
    public function hasPricing()
    {
        return !is_null($this->pricing);
    }

    /**
     * Check if product is in stock
     */
    public function getInStockAttribute()
    {
        return $this->quantity > 0;
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute()
    {
        if ($this->quantity > 10) {
            return 'In Stock';
        } elseif ($this->quantity > 0) {
            return 'Low Stock';
        } else {
            return 'Out of Stock';
        }
    }

    /**
     * Get stock status badge class
     */
    public function getStockStatusBadgeAttribute()
    {
        if ($this->quantity > 10) {
            return 'success';
        } elseif ($this->quantity > 0) {
            return 'warning';
        } else {
            return 'danger';
        }
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for in-stock products
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Scope for low stock products
     */
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('quantity', '>', 0)
                    ->where('quantity', '<=', $threshold);
    }

    /**
     * Scope for out of stock products
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }
}