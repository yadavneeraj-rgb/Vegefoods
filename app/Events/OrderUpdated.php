<?php

namespace App\Events;

use App\Models\Orders;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $changes;

    public function __construct(Orders $order, array $changes = [])
    {
        $this->order = $order;
        $this->changes = $changes;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('orders'),
        ];
    }
    public function broadcastAs(): string
    {
        return 'order.updated'; 
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
                'updated_at' => $this->order->updated_at,
            ],
            'changes' => $this->changes,
            'message' => 'Order has been updated',
            'timestamp' => now()->toISOString(),
        ];
    }
}