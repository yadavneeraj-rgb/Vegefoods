<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopingModule extends Model
{
    protected $table = 'shoping_modules';

    protected $fillable = [
        'name',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Relationship with categories
    public function categories()
    {
        return $this->hasMany(Category::class, 'module_id');
    }
}