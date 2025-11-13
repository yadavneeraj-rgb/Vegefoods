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
                    'success' => false,
                    'message' => 'Payment gateway not configured properly'
                ], 500);
            }

            $api = new Api($razorpayKey, $razorpaySecret);

            // Validate amount (convert to paise)
            $amount = $request->input('total');
            $amountInPaise = (int) ($amount * 100);

            if ($amountInPaise < 100) { // Minimum ₹1
                Log::error('Amount too low', ['amount' => $amount, 'paise' => $amountInPaise]);
                return response()->json([
                    'success' => false,
                    'message' => 'Amount must be at least ₹1'
                ], 400);
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

            // Return consistent success response
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
            return response()->json([
                'success' => false,
                'message' => 'Razorpay Error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Order creation failed: ' . $e->getMessage()
            ], 500);
        }
    }
    public function verifyPayment(Request $request)
    {
        \Log::info('=== PAYMENT VERIFICATION STARTED ===');
        \Log::info('Verification request data:', $request->all());

        $signature = $request->input('razorpay_signature');
        $paymentId = $request->input('razorpay_payment_id');
        $orderId = $request->input('razorpay_order_id');

        // Get form data from request
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

        \Log::info('Form data received:', $formData);

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            // Verify payment signature
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ]);

            \Log::info('Payment signature verified successfully');

            // Update order record with ALL data
            $order = Orders::where('razorpay_order_id', $orderId)->first();

            if ($order) {
                $updateData = [
                    'razorpay_payment_id' => $paymentId,
                    'razorpay_signature' => $signature,
                    'payment_status' => 'success',
                    'first_name' => $formData['first_name'] ?? 'Not Provided',
                    'last_name' => $formData['last_name'] ?? 'Not Provided',
                    'state_city' => $formData['state_city'] ?? 'Not Provided',
                    'street_address' => $formData['street_address'] ?? 'Not Provided',
                    'apartment_suite' => $formData['apartment_suite'] ?? '',
                    'town_city' => $formData['town_city'] ?? 'Not Provided',
                    'postcode' => $formData['postcode'] ?? 'Not Provided',
                    'phone' => $formData['phone'] ?? 'Not Provided',
                    'email' => $formData['email'] ?? Auth::user()->email ?? 'Not Provided'
                ];

                $order->update($updateData);

                \Log::info('Order updated successfully with form data', [
                    'order_id' => $order->id,
                    'razorpay_order_id' => $orderId,
                    'form_data_stored' => true
                ]);
            } else {
                \Log::error('Order not found for Razorpay order ID: ' . $orderId);
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found in our system'
                ]);
            }

            // Clear cart
            $cartItemsDeleted = Carts::where('user_id', Auth::id())->delete();
            \Log::info('Cart cleared. Items deleted: ' . $cartItemsDeleted);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful! Your order has been placed.',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment verification error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Mark order as failed
            Orders::where('razorpay_order_id', $orderId)
                ->update(['payment_status' => 'failed']);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ]);
        }
    }
}