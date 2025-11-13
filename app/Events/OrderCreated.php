<?php

namespace App\Events;

use App\Models\Orders;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Orders $order)
    {
        $this->order = $order;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('orders'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.created';
    }

    public function broadcastWith(): array
    {
        return [
            'order' => [
                'id' => $this->order->id,
                'user_id' => $this->order->user_id,
                'amount' => $this->order->amount,
                'payment_status' => $this->order->payment_status,
                'first_name' => $this->order->first_name,
                'last_name' => $this->order->last_name,
                'email' => $this->order->email,
                'phone' => $this->order->phone,
                'razorpay_order_id' => $this->order->razorpay_order_id,
                'payment_method' => $this->order->payment_method,
                'created_at' => $this->order->created_at,
            ],
            'message' => 'New order has been created',
            'timestamp' => now()->toISOString(),
        ];
    }
}