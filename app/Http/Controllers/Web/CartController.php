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
        $userId = Auth::id();
        $carts = Carts::with(['product.pricing'])
            ->where('user_id', $userId)
            ->get();

        $subtotal = $carts->sum(function ($cart) {
            return $cart->product && $cart->product->pricing
                ? $cart->product->pricing->final_price
                : 0;
        });

        $delivery = 0;
        $total = $subtotal + $delivery;

        return view('web.cart.cart', compact('carts', 'subtotal', 'delivery', 'total'));
    }

    public function addToCart(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->input('product_id');

        $exists = Carts::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            return back()->with('info', 'This product is already in your cart.');
        }

        Carts::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return back()->with('success', 'Product added to cart!');
    }
    public function remove($id)
    {
        $cartItem = Carts::find($id);

        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->delete();
            return back()->with('success', 'Item removed from cart!');
        }

        return back()->with('error', 'Unable to remove item.');
    }

}
