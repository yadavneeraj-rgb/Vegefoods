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
                                        {{-- Quick View --}}
                                        <a href="#"
                                            class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center mx-1"
                                            title="Quick View" style="width: 35px; height: 35px;">
                                            <span><i class="ion-ios-eye"></i></span>
                                        </a>

                                        {{-- Add to Cart Form --}}
                                        <form action="{{ route('cart.add') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit"
                                                class="btn btn-sm btn-primary d-flex justify-content-center align-items-center mx-1"
                                                title="Add to Cart" style="width: 35px; height: 35px;">
                                                <i class="ion-ios-cart"></i>
                                            </button>
                                        </form>

                                        {{-- Wishlist --}}
                                        <a href="#"
                                            class="btn btn-sm btn-outline-danger d-flex justify-content-center align-items-center mx-1"
                                            title="Add to Wishlist" style="width: 35px; height: 35px;">
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

        .bottom-area {
            margin-top: auto;
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
        }

        /* Flipkart style pricing */
        .flipkart-style-pricing {
            margin: 10px 0;
        }

        .final-price-flipkart {
            font-size: 18px;
            font-weight: 700;
            color: #c49b63;
            margin-bottom: 5px;
        }

        .price-details {
            font-size: 12px;
            color: #666;
        }

        .mrp-flipkart {
            margin-right: 8px;
        }

        .discount-flipkart {
            color: #388e3c;
            font-weight: 500;
        }

        .no-price {
            font-size: 14px;
            color: #999;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
           
            $('form[action="{{ route("cart.add") }}"]').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const button = form.find('button[type="submit"]');
                const productId = form.find('input[name="product_id"]').val();
             
                button.prop('disabled', true).html('<i class="ion-ios-refresh"></i>');
              
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        showToast('Success', response.success || 'Product added to cart!', 'success');
                        
                        updateCartCount();
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        showToast('Error', response.message || 'Something went wrong!', 'error');
                    },
                    complete: function() {
                        button.prop('disabled', false).html('<i class="ion-ios-cart"></i>');
                    }
                });
            });
        });
    </script>
@endpush