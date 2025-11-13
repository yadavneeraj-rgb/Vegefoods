<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function order()
    {
        // Fetch all orders (latest first)
        $orders = Orders::latest()->get();

        // Pass to view
        return view("admin.orders.order", compact("orders"));
    }
}
