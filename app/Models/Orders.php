<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'amount',
        'currency',
        'payment_status',
        'cart_items',
        'payment_method',
        'first_name',
        'last_name',
        'state_city',
        'street_address',
        'apartment_suite',
        'town_city',
        'postcode',
        'phone',
        'email'
    ];

    protected $casts = [
        'cart_items' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}