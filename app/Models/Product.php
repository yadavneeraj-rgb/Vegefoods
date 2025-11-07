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
        'sale_price'
    ];

    protected $attributes = [
        'status' => 1
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    // Add this method to get main categories through relationships
    public function mainCategories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')
            ->where('parent_id', 0);
    }
}