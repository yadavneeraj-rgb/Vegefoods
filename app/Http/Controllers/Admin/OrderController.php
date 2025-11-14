<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Events\OrderStatusChanged; // Add this
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order()
    {
        $orders = Orders::latest()->get();
        return view("admin.orders.order", compact("orders"));
    }

    // Add this method to test broadcasting
    public function updateStatus(Request $request, $orderId)
    {
        $order = Orders::findOrFail($orderId);
        $oldStatus = $order->payment_status;
        $newStatus = $request->status;

        // Update the order
        $order->update(['payment_status' => $newStatus]);

        // Manually trigger the event (booted method should handle this, but let's be sure)
        event(new OrderStatusChanged($order, $oldStatus, $newStatus));

        return response()->json(['success' => true, 'message' => 'Status updated']);
    }
}