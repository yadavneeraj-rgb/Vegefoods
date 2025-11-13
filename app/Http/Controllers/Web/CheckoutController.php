<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout()
    {
        $userId = Auth::id();

        $carts = Carts::with(['product.pricing'])
            ->where('user_id', $userId)
            ->get();

        $subtotal = $carts->sum(function ($cart) {
            return optional($cart->product->pricing)->final_price ?? 0;
        });

        $delivery = 0;
        $discount = 0;

        $total = ($subtotal + $delivery) - $discount;

        return view('web.checkout.checkout', compact('carts', 'subtotal', 'delivery', 'discount', 'total'));
    }

    public function saveAddress(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'state_city' => 'required',
            'street_address' => 'required',
            'town_city' => 'required',
            'postcode' => 'required',
            'phone' => 'required',
            'email' => 'required|email'
        ]);

        // Store in session
        session([
            'billing_address' => $request->all()
        ]);

        return response()->json(['success' => true]);
    }
}