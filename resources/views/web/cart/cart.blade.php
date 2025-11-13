@extends('web.layouts.master')
@section('title', 'Cart | Neeraj - Commerce')
@section('content')

	<div class="hero-wrap hero-bread" style="background-image: url('{{asset('web-assets/images/bg_1.jpg')}}');">
		<div class="container">
			<div class="row no-gutters slider-text align-items-center justify-content-center">
				<div class="col-md-9 ftco-animate text-center">
					<p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Cart</span></p>
					<h1 class="mb-0 bread">My Cart</h1>
				</div>
			</div>
		</div>
	</div>

	<section class="ftco-section ftco-cart">
		<div class="container">
			<div class="row">
				<div class="col-md-12 ftco-animate">
					<div class="cart-list">
						<table class="table">
							<thead class="thead-primary">
								<tr class="text-center">
									<th>&nbsp;</th>
									<th>&nbsp;</th>
									<th>Product name</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
							<tbody>
								@forelse($carts as $cart)
									@php
										$product = $cart->product;
										$pricing = $product?->pricing;
									@endphp

									<tr class="text-center">
										<td class="product-remove">
											<form action="{{ route('cart.remove', $cart->id) }}" method="POST"
												style="display:inline;">
												@csrf
												@method('DELETE')
												<button type="submit" class="remove-item" data-id="{{ $cart->id }}"><span
														class="ion-ios-close"></span></button>
											</form>
										</td>

										<td class="image-prod">
											@if($product->image)
												<img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
													width="60" height="60" style="object-fit: cover; border-radius: 5px;">
											@else
												<span>NA</span>
											@endif
										</td>

										<td class="product-name">
											<h3>{{ $product->name ?? 'Unknown Product' }}</h3>
											<p>{{ Str::limit($product->description ?? '', 50) }}</p>
										</td>

										<td class="price">
											₹{{ number_format($pricing->final_price ?? 0, 2) }}
										</td>

										<td class="quantity">
											<div class="input-group mb-3">
												<input type="text" name="quantity" class="quantity form-control input-number"
													value="1" min="1" max="100">
											</div>
										</td>

										<td class="total">
											₹{{ number_format($pricing->final_price ?? 0, 2) }}
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="text-center">Your cart is empty.</td>
									</tr>
								@endforelse
							</tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row justify-content-end">
				<div class="col-lg-4 mt-5 cart-wrap ftco-animate">
					<div class="cart-total mb-3">
						<h3>Coupon Code</h3>
						<p>Enter your coupon code if you have one</p>
						<form action="#" class="info">
							<div class="form-group">
								<label for="">Coupon code</label>
								<input type="text" class="form-control text-left px-3" placeholder="">
							</div>
						</form>
					</div>
					<p><a href="{{ route("checkout") }}" class="btn btn-primary py-3 px-4">Apply Coupon</a></p>
				</div>
				<div class="col-lg-4 mt-5 cart-wrap ftco-animate">
					<div class="cart-total mb-3">
						<h3>Estimate shipping and tax</h3>
						<p>Enter your destination to get a shipping estimate</p>
						<form action="#" class="info">
							<div class="form-group">
								<label for="">Country</label>
								<input type="text" class="form-control text-left px-3" placeholder="">
							</div>
							<div class="form-group">
								<label for="country">State/Province</label>
								<input type="text" class="form-control text-left px-3" placeholder="">
							</div>
							<div class="form-group">
								<label for="country">Zip/Postal Code</label>
								<input type="text" class="form-control text-left px-3" placeholder="">
							</div>
						</form>
					</div>
					<p><a href="{{ route("checkout") }}" class="btn btn-primary py-3 px-4">Estimate</a></p>
				</div>
				<div class="col-lg-4 mt-5 cart-wrap ftco-animate">
					<div class="cart-total mb-3">
						<h3>Cart Totals</h3>
						<p class="d-flex">
							<span>Subtotal</span>
							<span>₹{{ number_format($subtotal, 2) }}</span>
						</p>
						<p class="d-flex">
							<span>Delivery</span>
							<span>₹{{ number_format($delivery, 2) }}</span>
						</p>
						<hr>
						<p class="d-flex total-price">
							<span>Total</span>
							<span>₹{{ number_format($total, 2) }}</span>
						</p>
					</div>
					<p><a href="{{ route('checkout') }}" class="btn btn-primary py-3 px-4">Proceed to Checkout</a></p>
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
@section('scripts')
	<script>
		$(document).ready(function () {
			$(document).on('click', '.remove-item', function (e) {
				e.preventDefault();
				const id = $(this).data('id');
				const row = $(this).closest('tr');

				$.ajax({
					url: '/cart/remove/' + id,
					type: 'DELETE',
					data: { _token: '{{ csrf_token() }}' },
					success: function (res) {
						row.fadeOut(300, function () { $(this).remove(); });
					},
					error: function (xhr) {
						console.log(xhr.responseText);
					}
				});
			});
		});
	</script>
@endsection