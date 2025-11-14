<?php

namespace App\Events;

use App\Models\Orders;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;

    public function __construct(Orders $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('orders'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.status.changed'; // REMOVED THE DOT PREFIX
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'order' => [
                'id' => $this->order->id,
                'amount' => $this->order->amount,
                'payment_status' => $this->order->payment_status,
                'first_name' => $this->order->first_name,
                'last_name' => $this->order->last_name,
            ],
            'message' => "Order status changed from {$this->oldStatus} to {$this->newStatus}",
            'timestamp' => now()->toISOString(),
        ];
    }
}