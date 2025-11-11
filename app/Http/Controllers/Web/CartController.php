<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Carts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function cart()
    {
        // dd(Auth::user());
        return view("web.cart.cart");
    }

    public function addToCart(Request $request)
    {
        $userId = Auth::user()->id;

        $productId = $request->input('product_id');

        Carts::create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        return back();
    }
}
