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
        'is_featured'
    ];

    protected $attributes = [
        'status' => 1,
        'is_featured' => 0
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean'
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
}