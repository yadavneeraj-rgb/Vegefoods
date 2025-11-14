@extends('web.layouts.master')
@section('title', "Home | Neeraj E-Commerce")
@section('content')

	<!-- home section -->
	<section id="home-section" class="hero">
		<div class="home-slider owl-carousel">
			<div class="slider-item" style="background-image: url('{{ asset('web-assets/images/bg_1.jpg')}}');">
				<div class="overlay"></div>
				<div class="container">
					<div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">
						<div class="col-md-12 ftco-animate text-center">
							<h1 class="mb-2">We deliver the Latest &amp; Smartest Electronics</h1>
							<p><a href="#" class="btn btn-primary">View Details</a></p>
						</div>
					</div>
				</div>
			</div>

			<div class="slider-item" style="background-image: url('{{ asset('web-assets/images/bg_2.jpg')}}');">
				<div class="overlay"></div>
				<div class="container">
					<div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

						<div class="col-sm-12 ftco-animate text-center">
							<h1 class="mb-2">We serve Genuine Medicines & Health Products Foods</h1>
							<p><a href="#" class="btn btn-primary">View Details</a></p>
						</div>

					</div>
				</div>
			</div>

			<div class="slider-item" style="background-image: url('{{ asset('web-assets/images/bg_3.jpg')}}');">
				<div class="overlay"></div>
				<div class="container">
					<div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

						<div class="col-sm-12 ftco-animate text-center">
							<h1 class="mb-2">100% Fresh & Organic Foods Delivered</h1>
							<p><a href="#" class="btn btn-primary">View Details</a></p>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- cto section -->
	<section class="ftco-section">
		<div class="container">
			<div class="row no-gutters ftco-services justify-content-center">
				@foreach ($modules as $module)
					<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
						<div class="media block-6 services mb-md-0 mb-4">
							<div class=" d-flex justify-content-center align-items-center mb-2">
								@if ($module->image)
									<a href="{{ route('home', ['moduleId' => $module->id]) }}"><img
											src="{{ asset('storage/modules/' . $module->image) }}" alt="{{ $module->name }}"
											class="img-fluid mb-4 p-2 shadow-sm rounded"
											style="max-height: 180px; object-fit: contain;"></a>
								@else
									<span>NA</span>
								@endif
							</div>
							<div class="media-body">
								<h3 class="heading">{{ $module->name ?? 'NA' }}</h3>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>


	<section class="ftco-section">
		<div class="container">
			<div class="row no-gutters ftco-services">
				<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
					<div class="media block-6 services mb-md-0 mb-4">
						<div class="icon bg-color-1 active d-flex justify-content-center align-items-center mb-2">
							<span class="flaticon-shipped"></span>
						</div>
						<div class="media-body">
							<h3 class="heading">Free Shipping</h3>
							<span>On order over $100</span>
						</div>
					</div>
				</div>
				<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
					<div class="media block-6 services mb-md-0 mb-4">
						<div class="icon bg-color-2 d-flex justify-content-center align-items-center mb-2">
							<span class="flaticon-diet"></span>
						</div>
						<div class="media-body">
							<h3 class="heading">Always Fresh</h3>
							<span>Product well package</span>
						</div>
					</div>
				</div>
				<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
					<div class="media block-6 services mb-md-0 mb-4">
						<div class="icon bg-color-3 d-flex justify-content-center align-items-center mb-2">
							<span class="flaticon-award"></span>
						</div>
						<div class="media-body">
							<h3 class="heading">Superior Quality</h3>
							<span>Quality Products</span>
						</div>
					</div>
				</div>
				<div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
					<div class="media block-6 services mb-md-0 mb-4">
						<div class="icon bg-color-4 d-flex justify-content-center align-items-center mb-2">
							<span class="flaticon-customer-service"></span>
						</div>
						<div class="media-body">
							<h3 class="heading">Support</h3>
							<span>24/7 Support</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- category section -->
	<section class="ftco-section ftco-category ftco-no-pt">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<div class="row">

						<div class="col-md-6 order-md-last align-items-stretch d-flex">
							<div class="category-wrap-2 ftco-animate img align-self-stretch d-flex"
								style="background-image: url('{{ asset('web-assets/images/category.jpg') }}');">
								<div class="text text-center">
									<h2 style="color:black">DailyKart</h2>
									<p style="color:black">Protect the health of every home</p>
									<p><a href="{{ route('shop') }}" class="btn btn-primary">Shop now</a></p>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							@if(isset($categories[0]))
								<div class="category-wrap ftco-animate img mb-4 d-flex align-items-end"
									style="background-image: url('{{ $categories[0]->image ? asset('storage/' . $categories[0]->image) : asset('web-assets/images/category.jpg') }}');">
									<div class="text px-3 py-1">
										<h2 class="mb-0"><a
												href="{{ url('/shop?category=' . $categories[0]->id) }}">{{ $categories[0]->name }}</a>
										</h2>
									</div>
								</div>
							@endif
							@if(isset($categories[1]))
								<div class="category-wrap ftco-animate img d-flex align-items-end"
									style="background-image:url('{{ $categories[1]->image ? asset('storage/' . $categories[1]->image) : asset('web-assets/images/category.jpg')   }}');">
									<div class="text px-3 py-1">
										<h2 class="mb-0"><a
												href="{{ url('/shop?category=' . $categories[1]->id) }}">{{ $categories[1]->name }}</a>
										</h2>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>

				<div class="col-md-4">
					@if(isset($categories[2]))
						<div class="category-wrap ftco-animate img mb-4 d-flex align-items-end"
							style="background-image: url('{{ $categories[2]->image ? asset('storage/' . $categories[2]->image) : asset('web-assets/images/category-3.jpg') }}');">
							<div class="text px-3 py-1">
								<h2 class="mb-0"><a
										href="{{ url('/shop?category=' . $categories[2]->id) }}">{{ $categories[2]->name }}</a>
								</h2>
							</div>
						</div>
					@endif

					@if (isset($categories[3]))
						<div class="category-wrap ftco-animate img d-flex align-items-end"
							style="background-image: url('{{ $categories[3]->image ? asset('storage/' . $categories[3]->image) : asset('web-assets/images/category-4.jpg') }}');">
							<div class="text px-3 py-1">
								<h2 class="mb-0"><a
										href="{{ url('/shop?category=' . $categories[3]->id) }}">{{ $categories[3]->name }}</a>
								</h2>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</section>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center mb-3 pb-3">
				<div class="col-md-12 heading-section text-center ftco-animate">
					<span class="subheading">Featured Products</span>
					<h2 class="mb-4">Our Products</h2>
					<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				@forelse($featuredProducts as $product)
					<div class="col-md-6 col-lg-3 ftco-animate">
						<div class="product">
							<a href="#" class="img-prod">
								<img class="img-fluid"
									src="{{ $product->image ? asset('storage/' . $product->image) : asset('web-assets/images/product-1.jpg') }}"
									alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">

								{{-- Stock Status Badge --}}
								@if($product->quantity == 0)
									<span class="status out-of-stock">Out of Stock</span>
								@elseif($product->quantity <= 10)
									<span class="status low-stock">Low Stock</span>
								@elseif($product->hasPricing() && $product->pricing->discount_value > 0)
									@php
										$basePrice = $product->pricing->mrp_base_price;
										$finalPrice = $product->pricing->final_price;
										$discountPercentage = (($basePrice - $finalPrice) / $basePrice) * 100;
									@endphp
									<span class="status discount">{{ round($discountPercentage) }}% OFF</span>
								@endif

								<div class="overlay"></div>
							</a>
							<div class="text py-3 pb-4 px-3 text-center">
								<h3><a href="#" class="product-title">{{ Str::limit($product->name, 40) }}</a></h3>

								{{-- Stock Quantity --}}
								<div class="stock-info mb-2">
									@if($product->quantity == 0)
										<span class="badge badge-danger">Out of Stock</span>
									@elseif($product->quantity <= 10)
										<span class="badge badge-warning">Only {{ $product->quantity }} left</span>
									@else
										<span class="badge badge-success">In Stock</span>
									@endif
								</div>

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

								{{-- Product Actions --}}
								<div class="bottom-area d-flex px-3">
									<div class="m-auto d-flex">
										{{-- Quick View --}}
										<a href="#"
											class="btn btn-sm btn-outline-secondary d-flex justify-content-center align-items-center mx-1"
											title="Quick View" style="width: 35px; height: 35px;">
											<span><i class="ion-ios-eye"></i></span>
										</a>

										{{-- Add to Cart Button --}}
										@if($product->quantity > 0)
											<form action="{{ route('cart.add') }}" method="POST" style="display:inline;">
												@csrf
												<input type="hidden" name="product_id" value="{{ $product->id }}">
												<button type="submit"
													class="btn btn-sm btn-primary d-flex justify-content-center align-items-center mx-1"
													title="Add to Cart" style="width: 35px; height: 35px;">
													<i class="ion-ios-cart"></i>
												</button>
											</form>
										@else
											<button type="button"
												class="btn btn-sm btn-secondary d-flex justify-content-center align-items-center mx-1"
												title="Out of Stock" style="width: 35px; height: 35px;" disabled>
												<i class="ion-ios-cart"></i>
											</button>
										@endif

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
				@empty
					<div class="col-12 text-center">
						<div class="alert alert-info">
							<h4>No Featured Products Available</h4>
							<p>Check back later for our featured products!</p>
						</div>
					</div>
				@endforelse
			</div>

			{{-- Show "View More" button if there are featured products --}}
			@if($featuredProducts->count() > 0)
				<div class="row justify-content-center mt-4">
					<div class="col-md-12 text-center">
						<a href="{{ route('shop') }}" class="btn btn-primary">View All Products</a>
					</div>
				</div>
			@endif
		</div>
	</section>
	<section class="ftco-section img" style="background-image: url({{asset('web-assets/images/bg_4.jpg')}});">
		<div class="container">
			<div class="row justify-content-start">
				<div class="col-md-6 heading-section ftco-animate deal-of-the-day ftco-animate">
					<span class="subheading">Best Price For You</span>
					<h2 class="mb-4">Deal of the day</h2>
					<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
					<h3><a href="#">Spinach</a></h3>
					<span class="price">$10 <a href="#">now $5 only</a></span>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section testimony-section">
		<div class="container">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 heading-section ftco-animate text-center">
					<span class="subheading">Testimony</span>
					<h2 class="mb-4">Our satisfied customer says</h2>
					<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live
						the blind texts. Separated they live in</p>
				</div>
			</div>
			<div class="row ftco-animate">
				<div class="col-md-12">
					<div class="carousel-testimony owl-carousel">
						<div class="item">
							<div class="testimony-wrap p-4 pb-5">
								<div class="user-img mb-5"
									style="background-image: url({{asset('web-assets/images/person_1.jpg')}})">
									<span class="quote d-flex align-items-center justify-content-center">
										<i class="icon-quote-left"></i>
									</span>
								</div>
								<div class="text text-center">
									<p class="mb-5 pl-4 line">Far far away, behind the word mountains, far from the
										countries Vokalia and Consonantia, there live the blind texts.</p>
									<p class="name">Garreth Smith</p>
									<span class="position">Marketing Manager</span>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="testimony-wrap p-4 pb-5">
								<div class="user-img mb-5"
									style="background-image: url({{asset('web-assets/images/person_2.jpg')}})">
									<span class="quote d-flex align-items-center justify-content-center">
										<i class="icon-quote-left"></i>
									</span>
								</div>
								<div class="text text-center">
									<p class="mb-5 pl-4 line">Far far away, behind the word mountains, far from the
										countries Vokalia and Consonantia, there live the blind texts.</p>
									<p class="name">Garreth Smith</p>
									<span class="position">Interface Designer</span>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="testimony-wrap p-4 pb-5">
								<div class="user-img mb-5"
									style="background-image:url({{asset('web-assets/images/person_3.jpg')}})">
									<span class="quote d-flex align-items-center justify-content-center">
										<i class="icon-quote-left"></i>
									</span>
								</div>
								<div class="text text-center">
									<p class="mb-5 pl-4 line">Far far away, behind the word mountains, far from the
										countries Vokalia and Consonantia, there live the blind texts.</p>
									<p class="name">Garreth Smith</p>
									<span class="position">UI Designer</span>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="testimony-wrap p-4 pb-5">
								<div class="user-img mb-5"
									style="background-image:url({{asset('web-assets/images/person_1.jpg')}})">
									<span class="quote d-flex align-items-center justify-content-center">
										<i class="icon-quote-left"></i>
									</span>
								</div>
								<div class="text text-center">
									<p class="mb-5 pl-4 line">Far far away, behind the word mountains, far from the
										countries Vokalia and Consonantia, there live the blind texts.</p>
									<p class="name">Garreth Smith</p>
									<span class="position">Web Developer</span>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="testimony-wrap p-4 pb-5">
								<div class="user-img mb-5"
									style="background-image: url({{asset('web-assets/images/person_1.jpg')}})">
									<span class="quote d-flex align-items-center justify-content-center">
										<i class="icon-quote-left"></i>
									</span>
								</div>
								<div class="text text-center">
									<p class="mb-5 pl-4 line">Far far away, behind the word mountains, far from the
										countries Vokalia and Consonantia, there live the blind texts.</p>
									<p class="name">Garreth Smith</p>
									<span class="position">System Analyst</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<hr>

	<section class="ftco-section ftco-partner">
		<div class="container">
			<div class="row">
				<div class="col-sm ftco-animate">
					<a href="#" class="partner"><img src="{{asset('web-assets/images/partner-1.png')}}" class="img-fluid"
							alt="Colorlib Template"></a>
				</div>
				<div class="col-sm ftco-animate">
					<a href="#" class="partner"><img src="{{asset('web-assets/images/partner-2.png')}}" class="img-fluid"
							alt="Colorlib Template"></a>
				</div>
				<div class="col-sm ftco-animate">
					<a href="#" class="partner"><img src="{{asset('web-assets/images/partner-3.png')}}" class="img-fluid"
							alt="Colorlib Template"></a>
				</div>
				<div class="col-sm ftco-animate">
					<a href="#" class="partner"><img src="{{asset('web-assets/images/partner-4.png')}}" class="img-fluid"
							alt="Colorlib Template"></a>
				</div>
				<div class="col-sm ftco-animate">
					<a href="#" class="partner"><img src="{{asset('web-assets/images/partner-5.png')}}" class="img-fluid"
							alt="Colorlib Template"></a>
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