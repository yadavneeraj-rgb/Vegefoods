<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\AdminOrderNotification;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
use App\Models\Orders;
use App\Models\Carts;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;

class RazorpayController extends Controller
{
    /**
     * Create Razorpay order and local DB entry
     */
    public function createOrder(Request $request)
    {
        Log::info('Razorpay order creation started', ['user_id' => Auth::id(), 'total' => $request->total]);

        $request->validate([
            'total' => 'required|numeric|min:1'
        ]);

        try {
            $razorpayKey = env('RAZORPAY_KEY');
            $razorpaySecret = env('RAZORPAY_SECRET');

            if (empty($razorpayKey) || empty($razorpaySecret)) {
                Log::error('Razorpay credentials missing');
                return response()->json([
                    'success' => false,
                    'message' => 'Payment gateway not configured properly'
                ], 500);
            }

            $api = new Api($razorpayKey, $razorpaySecret);

            $amount = $request->input('total');
            $amountInPaise = (int) ($amount * 100);

            if ($amountInPaise < 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amount must be at least ₹1'
                ], 400);
            }

            // Create Razorpay order
            $order = $api->order->create([
                'receipt' => 'order_rcptid_' . time(),
                'amount' => $amountInPaise,
                'currency' => 'INR'
            ]);

            // Get user's cart items
            $cartItems = Carts::with('product')->where('user_id', Auth::id())->get();

            // Create order in DB
            $dbOrder = Orders::create([
                'user_id' => Auth::id(),
                'razorpay_order_id' => $order['id'],
                'amount' => $amount,
                'currency' => 'INR',
                'cart_items' => $cartItems->toJson(),
                'payment_status' => 'pending',
                'payment_method' => 'razorpay',
            ]);

            Log::info('Database order created', ['order_id' => $dbOrder->id]);

            return response()->json([
                'success' => true,
                'order_id' => $order['id'],
                'razorpay_key' => $razorpayKey,
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'name' => Auth::user()->name ?? 'Guest User',
                'email' => Auth::user()->email ?? 'guest@example.com',
                'contact' => Auth::user()->phone ?? '9999999999'
            ]);
        } catch (\Razorpay\Api\Errors\Error $e) {
            Log::error('Razorpay API Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Razorpay Error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Order creation failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify Razorpay payment and update order
     */
    public function verifyPayment(Request $request)
    {
        Log::info('=== PAYMENT VERIFICATION STARTED ===', $request->all());

        $signature = $request->input('razorpay_signature');
        $paymentId = $request->input('razorpay_payment_id');
        $orderId = $request->input('razorpay_order_id');

        $formData = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'state_city' => $request->input('state_city'),
            'street_address' => $request->input('street_address'),
            'apartment_suite' => $request->input('apartment_suite'),
            'town_city' => $request->input('town_city'),
            'postcode' => $request->input('postcode'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email')
        ];

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            // Verify Razorpay signature
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ]);

            $order = Orders::where('razorpay_order_id', $orderId)->first();

            if (!$order) {
                Log::error('Order not found for Razorpay order ID: ' . $orderId);
                return response()->json(['success' => false, 'message' => 'Order not found in our system']);
            }

            // Update order record
            $order->update([
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
                'payment_status' => 'success',
                'first_name' => $formData['first_name'] ?? '',
                'last_name' => $formData['last_name'] ?? '',
                'state_city' => $formData['state_city'] ?? '',
                'street_address' => $formData['street_address'] ?? '',
                'apartment_suite' => $formData['apartment_suite'] ?? '',
                'town_city' => $formData['town_city'] ?? '',
                'postcode' => $formData['postcode'] ?? '',
                'phone' => $formData['phone'] ?? '',
                'email' => $formData['email'] ?? Auth::user()->email ?? ''
            ]);

            Log::info('Order updated successfully', ['order_id' => $order->id]);

            // Clear cart after successful order
            Carts::where('user_id', Auth::id())->delete();
            Log::info('Cart cleared for user: ' . Auth::id());

            try {
                Mail::to($order->email)->send(new OrderPlacedMail($order)); // customer mail
                Mail::to('yadav.neeraj@pearlorganisation.com')->send(new AdminOrderNotification($order));
                Log::info('Order confirmation emails sent successfully');
            } catch (\Exception $mailError) {
                Log::error('Email sending failed: ' . $mailError->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment successful! Your order has been placed.',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            Orders::where('razorpay_order_id', $orderId)->update(['payment_status' => 'failed']);
            return response()->json(['success' => false, 'message' => 'Payment verification failed: ' . $e->getMessage()]);
        }
    }
}
