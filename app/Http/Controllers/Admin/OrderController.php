<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order()
    {
        // Fetch all orders (latest first)
        $orders = Order::latest()->get();

        // Pass to view
        return view("admin.orders.order", compact("orders"));
    }
}
