@extends('web.layouts.master')
@section('title', 'Shop | Neeraj E-Commerce')
@section('content')

    <div class="hero-wrap hero-bread" style="background-image: url('{{asset('web-assets/images/bg_1.jpg')}}')">
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center">
                <div class="col-md-9 ftco-animate text-center">
                    <p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Products</span>
                    </p>
                    <h1 class="mb-0 bread">Products</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 mb-5 text-center">
                    <ul class="product-category">
                        <li><a href="{{ url('/shop') }}" class="{{ !request('category') ? 'active' : '' }}">All</a></li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ url('/shop?category=' . $category->id) }}"
                                    class="{{ request('category') == $category->id ? 'active' : '' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-3 ftco-animate">
                        <div class="product">
                            <a href="#" class="img-prod">
                                @if($product->image)
                                    <img class="img-fluid" src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <img class="img-fluid" src="{{ asset('web-assets/images/product-1.jpg') }}"
                                        alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                @endif

                                @if($product->hasPricing() && $product->pricing->discount_value > 0)
                                    @php
                                        $basePrice = $product->pricing->mrp_base_price;
                                        $finalPrice = $product->pricing->final_price;
                                        $discountPercentage = (($basePrice - $finalPrice) / $basePrice) * 100;
                                    @endphp
                                    <span class="status">{{ round($discountPercentage) }}% OFF</span>
                                @endif

                                <div class="overlay"></div>
                            </a>
                            <div class="text py-3 pb-4 px-3 text-center">
                                <h3><a href="#" class="product-title">{{ Str::limit($product->name, 40) }}</a></h3>

                                <div class="flipkart-style-pricing">
                                    @if($product->hasPricing())
                                        @php
                                            $pricing = $product->pricing;
                                            $hasDiscount = $pricing->discount_value > 0;
                                            $discountPercentage = $hasDiscount ?
                                                round((($pricing->mrp_base_price - $pricing->final_price) / $pricing->mrp_base_price) * 100) : 0;
                                        @endphp

                                        <div class="final-price-flipkart">
                                            ₹{{ number_format($pricing->final_price, 2) }}
                                        </div>

                                        <div class="price-details">
                                            <span class="mrp-flipkart">
                                                M.R.P.: <s>₹{{ number_format($pricing->mrp_base_price, 2) }}</s>
                                            </span>

                                            @if($hasDiscount)
                                                <span class="discount-flipkart">
                                                    {{ $discountPercentage }}% off
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="no-price">
                                            <span class="text-muted">Price to be announced</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="bottom-area d-flex px-3">
                                    <div class="m-auto d-flex">
                                        <a href="#"
                                            class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center mx-1"
                                            title="Quick View">
                                            <span><i class="ion-ios-eye"></i></span>
                                        </a>
                                        <a href="#"
                                            class="btn btn-sm btn-primary d-flex justify-content-center align-items-center mx-1 add-to-cart-btn"
                                            title="Add to Cart" data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ $product->hasPricing() ? $product->pricing->final_price : 0 }}"
                                            data-product-image="{{ $product->image ? asset('storage/' . $product->image) : asset('web-assets/images/product-1.jpg') }}">
                                            <span><i class="ion-ios-cart"></i></span>
                                        </a>
                                        <a href="#"
                                            class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center mx-1"
                                            title="Add to Wishlist">
                                            <span><i class="ion-ios-heart"></i></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->isEmpty())
                <div class="row">
                    <div class="col-12 text-center py-5">
                        <div class="empty-state">
                            <i class="icon ion-ios-cart-outline" style="font-size: 4rem; color: #ccc;"></i>
                            <h4 class="mt-3 text-muted">No products found</h4>
                            <p class="text-muted">We couldn't find any products matching your criteria.</p>
                            <a href="{{ url('/shop') }}" class="btn btn-primary mt-3">View All Products</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="ftco-section ftco-no-pt ftco-no-pb py-5 bg-light">
        <div class="container py-4">
            <div class="row d-flex justify-content-center py-5">
                <div class="col-md-6">
                    <h2 style="font-size: 22px;" class="mb-0">Subscribe to our Newsletter</h2>
                    <span>Get e-mail updates about our latest shops and special offers</span>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <form action="#" class="subscribe-form">
                        <div class="form-group d-flex">
                            <input type="text" class="form-control" placeholder="Enter email address">
                            <input type="submit" value="Subscribe" class="submit px-3">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
    <style>
        .product {
            border: 1px solid #e6e6e6;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #c49b63;
        }

        .img-prod {
            position: relative;
            display: block;
            overflow: hidden;
        }

        .img-prod img {
            transition: transform 0.3s ease;
            width: 100%;
        }

        .product:hover .img-prod img {
            transform: scale(1.05);
        }

        .status {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #ff4d4d;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            z-index: 2;
        }

        .featured-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ffc107;
            color: #000;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            z-index: 2;
        }

        .product .text {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .product-title:hover {
            color: #c49b63;
            text-decoration: none;
        }

        .product-description {
            font-size: 13px;
            color: #666;
            line-height: 1.4;
            margin-bottom: 12px;
            flex: 1;
        }

        .pricing {
            margin-bottom: 15px;
        }

        .price {
            margin-bottom: 5px;
        }

        .price-final {
            font-size: 18px;
            font-weight: 700;
            color: #c49b63;
        }

        .price-original {
            font-size: 14px;
            color: #999;
            text-decoration: line-through;
            margin-left: 8px;
        }

        .tax-info {
            font-size: 11px;
            display: block;
            margin-top: -2px;
        }

        .savings {
            font-size: 12px;
            margin-top: 3px;
        }

        .savings i {
            margin-right: 3px;
        }

        .bottom-area {
            margin-top: auto;
        }

        .add-to-cart-btn {
            background: #c49b63;
            border-color: #c49b63;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background: #b08c5a;
            border-color: #b08c5a;
            transform: scale(1.05);
        }

        .stock-status .badge {
            font-size: 11px;
            padding: 4px 8px;
        }

        .empty-state {
            padding: 40px 20px;
        }

        .btn-outline-secondary,
        .btn-outline-danger {
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            border-color: #6c757d;
        }

        .btn-outline-danger:hover {
            background: #dc3545;
            border-color: #dc3545;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .col-md-6.col-lg-3 {
                margin-bottom: 30px;
            }

            .product-title {
                font-size: 15px;
            }

            .price-final {
                font-size: 16px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Add to cart functionality
            $('.add-to-cart-btn').on('click', function (e) {
                e.preventDefault();

                const productId = $(this).data('product-id');
                const productName = $(this).data('product-name');
                const productPrice = $(this).data('product-price');
                const productImage = $(this).data('product-image');

                // Simple add to cart implementation
                addToCart(productId, productName, productPrice, productImage);
            });

            function addToCart(productId, productName, productPrice, productImage) {
                // Get existing cart from localStorage
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                // Check if product already in cart
                const existingItem = cart.find(item => item.id === productId);

                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        image: productImage,
                        quantity: 1
                    });
                }

                // Save back to localStorage
                localStorage.setItem('cart', JSON.stringify(cart));

                // Update cart count in header (if you have one)
                updateCartCount();

                // Show success message
                showToast('Success', `${productName} added to cart!`, 'success');
            }

            function updateCartCount() {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

                // Update cart count badge (adjust selector based on your layout)
                $('.cart-count').text(totalItems);
            }

            function showToast(title, message, type = 'success') {
                // You can integrate with a toast library or use simple alert
                alert(`${title}: ${message}`);

                // For better UX, consider using Toastr or similar:
                // toastr[type](message, title);
            }

            // Initialize cart count on page load
            updateCartCount();
        });
    </script>
@endpush