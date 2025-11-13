@extends('admin.layouts.master')
@section('title', 'Orders | Neeraj - Ecommerce')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-3">All Orders</h3>

        @if($orders->count() > 0)
            <div class="accordion" id="ordersAccordion">
                @foreach ($orders as $order)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $order->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $order->id }}" aria-expanded="false"
                                aria-controls="collapse{{ $order->id }}">
                                <div class="d-flex justify-content-between w-100 me-3">
                                    <div>
                                        <strong>Order Id {{ $loop->iteration }}</strong> -
                                        {{ $order->user->name }} -
                                        ₹{{ number_format($order->amount, 2) }}
                                    </div>
                                    <div>
                                        @if($order->payment_status == 'success')
                                            <span class="badge bg-success">Success</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{ $order->id }}" class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $order->id }}" data-bs-parent="#ordersAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Customer Information</h6>
                                        <p><strong>Name:</strong> {{ $order->user->name ?? 'Guest' }}</p>
                                        <p><strong>Email:</strong> {{ $order->email }}</p>
                                        <p><strong>Phone:</strong> {{ $order->phone }}</p>
                                        <p><strong>Address:</strong> {{ $order->street_address }},
                                            {{ $order->apartment_suite ? $order->apartment_suite . ', ' : '' }}
                                            {{ $order->town_city }}, {{ $order->state_city }} - {{ $order->postcode }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Order Details</h6>
                                        <p><strong>Order ID:</strong> {{ $order->razorpay_order_id }}</p>
                                        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                        <p><strong>Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                                    </div>
                                </div>

                                <hr>

                                <h6>Order Products</h6>
                                @php
                                    $cartItems = json_decode($order->cart_items, true);
                                @endphp

                                @if(is_array($cartItems) && count($cartItems) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product Image</th>
                                                    <th>Product Details</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $subtotal = 0;
                                                @endphp
                                                @foreach($cartItems as $item)
                                                    @php
                                                        // Get actual product details from database
                                                        $product = \App\Models\Product::with('pricing')->find($item['product_id'] ?? null);
                                                        $productName = $product->name ?? $item['product_name'] ?? 'Unknown Product';
                                                        $productImage = $product->image ?? $item['image'] ?? null;
                                                        $quantity = $item['quantity'] ?? 1;
                                                        $unitPrice = $product->pricing->final_price ?? $item['price'] ?? 0;
                                                        $itemTotal = $unitPrice * $quantity;
                                                        $subtotal += $itemTotal;
                                                    @endphp
                                                    <tr>
                                                        <td style="width: 80px;">
                                                            @if($productImage)
                                                                <img src="{{ asset('storage/' . $productImage) }}" 
                                                                     alt="{{ $productName }}"
                                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                                            @else
                                                                <div style="width: 60px; height: 60px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                                                    <span class="text-muted small">No Image</span>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <strong class="d-block">{{ $productName }}</strong>
                                                                <small class="text-muted">
                                                                    ID: {{ $item['product_id'] ?? 'N/A' }}
                                                                    @if($product && $product->description)
                                                                        <br>{{ Str::limit($product->description, 80) }}
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">{{ $quantity }}</td>
                                                        <td class="align-middle">₹{{ number_format($unitPrice, 2) }}</td>
                                                        <td class="align-middle">₹{{ number_format($itemTotal, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="table-active">
                                                    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                                    <td><strong>₹{{ number_format($subtotal, 2) }}</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        No product information available for this order.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">No orders found yet.</div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection