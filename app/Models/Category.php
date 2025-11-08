<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'module_id',
        'parent_id',
        'status',
        'image'
    ];

    protected $attributes = [
        'parent_id' => 0,
        'status' => 1
    ];

    /**
     * Get the parent category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the subcategories
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function module()
    {
        return $this->belongsTo(ShopingModule::class, 'module_id');
    }
    public function scopeMainCategories($query)
    {
        return $query->where('parent_id', 0);
    }

    /**
     * Scope for subcategories
     */
    public function scopeSubCategories($query)
    {
        return $query->where('parent_id', '!=', 0);
    }

    /**
     * Check if category has children
     */
    public function getHasChildrenAttribute()
    {
        return $this->children()->exists();
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

}