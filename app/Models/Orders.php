<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Events\OrderCreated;
use App\Events\OrderUpdated;
use App\Events\OrderStatusChanged;

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

    protected static function booted()
    {
        static::created(function ($order) {
            \Log::info('Order created event fired', ['order_id' => $order->id]);
            event(new OrderCreated($order));
        });

        static::updated(function ($order) {
            $changes = $order->getChanges();
            \Log::info('Order updated event fired', [
                'order_id' => $order->id,
                'changes' => $changes
            ]);

            // Broadcast general order update
            event(new OrderUpdated($order, $changes));

            // Broadcast specific payment status change if status was updated
            if ($order->isDirty('payment_status')) {
                \Log::info('Payment status changed', [
                    'order_id' => $order->id,
                    'from' => $order->getOriginal('payment_status'),
                    'to' => $order->payment_status
                ]);
                event(new OrderStatusChanged(
                    $order,
                    $order->getOriginal('payment_status'),
                    $order->payment_status
                ));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}