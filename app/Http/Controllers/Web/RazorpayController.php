<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
use App\Models\Orders;
use App\Models\Carts;
use Illuminate\Support\Facades\Log;

class RazorpayController extends Controller
{
    public function createOrder(Request $request)
    {
        Log::info('Razorpay order creation started', ['user_id' => Auth::id(), 'total' => $request->total]);

        // Add validation
        $request->validate([
            'total' => 'required|numeric|min:1'
        ]);

        try {
            // Check Razorpay credentials
            $razorpayKey = env('RAZORPAY_KEY');
            $razorpaySecret = env('RAZORPAY_SECRET');
            
            Log::info('Razorpay credentials check', [
                'key_exists' => !empty($razorpayKey),
                'secret_exists' => !empty($razorpaySecret)
            ]);

            if (empty($razorpayKey) || empty($razorpaySecret)) {
                Log::error('Razorpay credentials missing');
                return response()->json([
                    'error' => 'Payment gateway not configured properly'
                ], 500);
            }

            $api = new Api($razorpayKey, $razorpaySecret);

            // Validate amount (convert to paise)
            $amount = $request->input('total');
            $amountInPaise = (int)($amount * 100);
            
            if ($amountInPaise < 100) { // Minimum ₹1
                Log::error('Amount too low', ['amount' => $amount, 'paise' => $amountInPaise]);
                return response()->json(['error' => 'Amount must be at least ₹1'], 400);
            }

            Log::info('Creating Razorpay order', ['amount' => $amountInPaise]);

            // Create Razorpay order
            $order = $api->order->create([
                'receipt' => 'order_rcptid_' . time(),
                'amount' => $amountInPaise,
                'currency' => 'INR'
            ]);

            Log::info('Razorpay order created', ['order_id' => $order['id']]);

            // Get cart items
            $cartItems = Carts::with('product')->where('user_id', Auth::id())->get();
            
            Log::info('Cart items found', ['count' => $cartItems->count()]);

            // Create order in database
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
                'order_id' => $order['id'],
                'razorpay_key' => $razorpayKey,
                'amount' => $amountInPaise,
                'name' => Auth::user()->name ?? 'Guest User',
                'email' => Auth::user()->email ?? 'guest@example.com',
                'contact' => Auth::user()->phone ?? '9999999999'
            ]);

        } catch (\Razorpay\Api\Errors\Error $e) {
            Log::error('Razorpay API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Razorpay Error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Order creation failed: ' . $e->getMessage()
            ], 500);
        }
    }


    public function verifyPayment(Request $request)
    {
        $signature = $request->input('razorpay_signature');
        $paymentId = $request->input('razorpay_payment_id');
        $orderId = $request->input('razorpay_order_id');

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ]);

            // Update order record
            $order = Orders::where('razorpay_order_id', $orderId)->first();
            if ($order) {
                $order->update([
                    'razorpay_payment_id' => $paymentId,
                    'razorpay_signature' => $signature,
                    'payment_status' => 'success'
                ]);
            }

            // Clear cart
            Carts::where('user_id', Auth::id())->delete();

            return response()->json(['success' => true, 'message' => 'Payment successful!']);

        } catch (\Exception $e) {
            \Log::error('Payment verification error: ' . $e->getMessage());
            
            Orders::where('razorpay_order_id', $orderId)
                ->update(['payment_status' => 'failed']);

            return response()->json([
                'success' => false, 
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ]);
        }
    }
}