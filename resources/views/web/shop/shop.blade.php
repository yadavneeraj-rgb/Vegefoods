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
                        <li><a href="#" class="active all-category" data-category="all">All</a></li>
                        @foreach($categories as $category)
                            <li><a href="#" class="category-filter"
                                    data-category="{{ $category->id }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="row" id="products-container">
                <!-- Products will be loaded here dynamically -->
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-3 ftco-animate product-item"
                        data-categories="{{ $product->categories->pluck('id')->implode(',') }}"
                        data-main-categories="{{ $product->mainCategories->pluck('id')->implode(',') }}">
                        <div class="product">
                            <a href="#" class="img-prod">
                                @if($product->image)
                                    <img class="img-fluid" src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}">
                                @else
                                    <img class="img-fluid" src="{{ asset('web-assets/images/product-1.jpg') }}"
                                        alt="{{ $product->name }}">
                                @endif
                                @if($product->sale_price && $product->regular_price)
                                    @php
                                        $discount = (($product->regular_price - $product->sale_price) / $product->regular_price) * 100;
                                    @endphp
                                    <span class="status">{{ round($discount) }}%</span>
                                @endif
                                <div class="overlay"></div>
                            </a>
                            <div class="text py-3 pb-4 px-3 text-center">
                                <h3><a href="#">{{ $product->name }}</a></h3>
                                <div class="d-flex">
                                    <div class="pricing">
                                        <p class="price">
                                            @if($product->sale_price && $product->regular_price)
                                                <span class="mr-2 price-dc">${{ $product->regular_price }}</span>
                                                <span class="price-sale">${{ $product->sale_price }}</span>
                                            @else
                                                <span>${{ $product->regular_price ?? $product->price }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="bottom-area d-flex px-3">
                                    <div class="m-auto d-flex">
                                        <a href="#"
                                            class="add-to-cart d-flex justify-content-center align-items-center text-center">
                                            <span><i class="ion-ios-menu"></i></span>
                                        </a>
                                        <a href="#" class="buy-now d-flex justify-content-center align-items-center mx-1">
                                            <span><i class="ion-ios-cart"></i></span>
                                        </a>
                                        <a href="#" class="heart d-flex justify-content-center align-items-center ">
                                            <span><i class="ion-ios-heart"></i></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row mt-5">
                <div class="col text-center">
                    <div class="block-27">
                        <ul>
                            <li><a href="#">&lt;</a></li>
                            <li class="active"><span>1</span></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li><a href="#">&gt;</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="ftco-section ftco-no-pt ftco-no-pb py-5 bg-light">
        <div class="container py-4">
            <div class="row d-flex justify-content-center py-5">
                <div class="col-md-6">
                    <h2 style="font-size: 22px;" class="mb-0">Subcribe to our Newsletter</h2>
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

@push('scripts')
    <script>
        $(document).ready(function () {
            // Get all subcategories for each main category
            const categoryHierarchy = @json($categoryHierarchy);

            // Category filter functionality
            $('.category-filter, .all-category').click(function (e) {
                e.preventDefault();

                // Remove active class from all and add to clicked
                $('.product-category a').removeClass('active');
                $(this).addClass('active');

                const categoryId = $(this).data('category');

                if (categoryId === 'all') {
                    // Show all products
                    $('.product-item').show();
                } else {
                    // Get all subcategory IDs for this main category (including the main category itself)
                    const allCategoryIds = getAllSubcategoryIds(parseInt(categoryId));

                    // Show products that belong to this category or any of its subcategories
                    $('.product-item').each(function () {
                        const productCategories = $(this).data('categories').split(',').map(Number);

                        // Check if product has any category that matches main category or its subcategories
                        const hasMatchingCategory = productCategories.some(catId =>
                            allCategoryIds.includes(catId)
                        );

                        if (hasMatchingCategory) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });

            // Function to get all subcategory IDs including the main category
            function getAllSubcategoryIds(mainCategoryId) {
                const allIds = [mainCategoryId];

                function getSubcategories(categoryId) {
                    if (categoryHierarchy[categoryId]) {
                        categoryHierarchy[categoryId].forEach(subId => {
                            allIds.push(subId);
                            getSubcategories(subId); // Recursive for nested subcategories
                        });
                    }
                }

                getSubcategories(mainCategoryId);
                return allIds;
            }
        });
    </script>
@endpush