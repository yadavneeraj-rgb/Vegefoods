@extends('web.layouts.master')
@section('title', 'Checkout | Neeraj - Ecommerce')
@section('content')


	<div class="hero-wrap hero-bread" style="background-image: url('{{asset('web-assets/images/bg_1.jpg')}}');">
		<div class="container">
			<div class="row no-gutters slider-text align-items-center justify-content-center">
				<div class="col-md-9 ftco-animate text-center">
					<p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Checkout</span></p>
					<h1 class="mb-0 bread">Checkout</h1>
				</div>
			</div>
		</div>
	</div>

	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xl-7 ftco-animate">
					<form id="billingForm" class="billing-form">
						@csrf
						<h3 class="mb-4 billing-heading">Billing Details</h3>
						<div class="row align-items-end">
							<div class="col-md-6">
								<div class="form-group">
									<label for="first_name">First Name *</label>
									<input type="text" class="form-control" id="first_name" name="first_name"
										placeholder="First Name" required
										value="{{ Auth::user()->name ? explode(' ', Auth::user()->name)[0] : '' }}">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="last_name">Last Name *</label>
									<input type="text" class="form-control" id="last_name" name="last_name"
										placeholder="Last Name" required
										value="{{ Auth::user()->name ? (explode(' ', Auth::user()->name)[1] ?? '') : '' }}">
								</div>
							</div>
							<div class="w-100"></div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="state_city">State / City *</label>
									<div class="select-wrap">
										<div class="icon"><span class="ion-ios-arrow-down"></span></div>
										<select name="state_city" id="state_city" class="form-control" required>
											<option value="">Select State/City</option>
											<option value="Dehradun">Dehradun</option>
											<option value="Roorkee">Roorkee</option>
											<option value="Rishikesh">Rishikesh</option>
											<option value="Delhi">Delhi</option>
											<option value="Haridwar">Haridwar</option>
										</select>
									</div>
								</div>
							</div>
							<div class="w-100"></div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="street_address">Street Address *</label>
									<input type="text" class="form-control" id="street_address" name="street_address"
										placeholder="House number and street name" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="apartment_suite">Apartment, Suite, etc. (Optional)</label>
									<input type="text" class="form-control" id="apartment_suite" name="apartment_suite"
										placeholder="Appartment, suite, unit etc: (optional)">
								</div>
							</div>
							<div class="w-100"></div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="town_city">Town / City *</label>
									<input type="text" class="form-control" id="town_city" name="town_city"
										placeholder="Town / City" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="postcode">Postcode / ZIP *</label>
									<input type="text" class="form-control" id="postcode" name="postcode"
										placeholder="Postcode / ZIP" required>
								</div>
							</div>
							<div class="w-100"></div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="phone">Phone *</label>
									<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone"
										required value="{{ Auth::user()->phone ?? '' }}">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="email">Email Address *</label>
									<input type="email" class="form-control" id="email" name="email"
										placeholder="Email Address" required value="{{ Auth::user()->email ?? '' }}">
								</div>
							</div>
						</div>
					</form><!-- END -->
				</div>
				<div class="col-xl-5">
					<div class="row mt-5 pt-3">
						<div class="col-md-12 d-flex mb-5">
							<div class="cart-detail cart-total p-3 p-md-4">
								<h3 class="billing-heading mb-4">Cart Total</h3>
								<p class="d-flex">
									<span>Subtotal</span>
									<span>₹{{ number_format($subtotal, 2) }}</span>
								</p>
								<p class="d-flex">
									<span>Delivery</span>
									<span>₹{{ number_format($delivery, 2) }}</span>
								</p>
								<p class="d-flex">
									<span>Discount</span>
									<span>₹{{ number_format($discount, 2) }}</span>
								</p>
								<hr>
								<p class="d-flex total-price">
									<span>Total</span>
									<span>₹{{ number_format($total, 2) }}</span>
								</p>

							</div>
						</div>
						<div class="col-md-12">
							<div class="cart-detail p-3 p-md-4">
								<h3 class="billing-heading mb-4">Payment Method</h3>

								{{-- Only Razorpay --}}
								<div class="form-group">
									<div class="col-md-12">
										<div class="radio">
											<label>
												<input type="radio" name="payment_method" value="razorpay" class="mr-2"
													checked>
												Pay securely with Razorpay
											</label>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-12">
										<div class="checkbox">
											<label>
												<input type="checkbox" id="termsCheck" class="mr-2">
												I agree to the <a href="#">terms and conditions</a>.
											</label>
										</div>
									</div>
								</div>

								<p>
									<button id="placeOrderBtn" class="btn btn-primary py-3 px-4">
										Proceed to Pay
									</button>
								</p>
							</div>
						</div>

					</div>
				</div> <!-- .col-md-8 -->
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
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		document.getElementById('placeOrderBtn').onclick = function (e) {
			e.preventDefault();

			console.log('Place order button clicked');

			// Validate form
			const form = document.getElementById('billingForm');
			if (!form.checkValidity()) {
				alert("⚠️ Please fill all required fields correctly.");
				form.reportValidity();
				return;
			}

			// Ensure user accepted terms
			if (!document.getElementById('termsCheck').checked) {
				alert("⚠️ Please accept the terms & conditions before proceeding.");
				return;
			}

			// Collect all form data
			const formData = {
				first_name: document.getElementById('first_name').value,
				last_name: document.getElementById('last_name').value,
				state_city: document.getElementById('state_city').value,
				street_address: document.getElementById('street_address').value,
				apartment_suite: document.getElementById('apartment_suite').value,
				town_city: document.getElementById('town_city').value,
				postcode: document.getElementById('postcode').value,
				phone: document.getElementById('phone').value,
				email: document.getElementById('email').value
			};

			console.log('Form data collected:', formData);

			// Proceed with Razorpay payment (only send total as before)
			const total = "{{ $total }}";
			console.log('Total amount:', total);

			// Show loading state
			const button = document.getElementById('placeOrderBtn');
			const originalText = button.innerHTML;
			button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
			button.disabled = true;

			fetch("{{ route('razorpay.order') }}", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					"X-CSRF-TOKEN": "{{ csrf_token() }}",
					"Accept": "application/json"
				},
				body: JSON.stringify({
					total: total
				})
			})
				.then(res => {
					console.log('Response status:', res.status);

					if (!res.ok) {
						return res.text().then(text => {
							throw new Error(`HTTP ${res.status}: ${text}`);
						});
					}
					return res.json();
				})
				.then(data => {
					console.log('Razorpay response data:', data);

					if (!data.success) {
						throw new Error(data.message || 'Order creation failed');
					}

					if (!data.order_id) {
						throw new Error('No order ID received from server');
					}

					var options = {
						"key": data.razorpay_key,
						"amount": data.amount,
						"currency": data.currency,
						"name": "Neeraj Ecommerce",
						"description": "Order Payment",
						"order_id": data.order_id,
						"handler": function (response) {
							console.log('Payment successful, response:', response);

							// Show processing state
							button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';

							// Combine payment response with form data
							const verificationData = {
								razorpay_payment_id: response.razorpay_payment_id,
								razorpay_order_id: response.razorpay_order_id,
								razorpay_signature: response.razorpay_signature,
								// Add all form data
								first_name: formData.first_name,
								last_name: formData.last_name,
								state_city: formData.state_city,
								street_address: formData.street_address,
								apartment_suite: formData.apartment_suite,
								town_city: formData.town_city,
								postcode: formData.postcode,
								phone: formData.phone,
								email: formData.email
							};

							console.log('Sending verification data:', verificationData);

							fetch("{{ route('razorpay.verify') }}", {
								method: "POST",
								headers: {
									"Content-Type": "application/json",
									"X-CSRF-TOKEN": "{{ csrf_token() }}",
									"Accept": "application/json"
								},
								body: JSON.stringify(verificationData)
							})
								.then(verifyRes => {
									if (!verifyRes.ok) {
										return verifyRes.text().then(text => {
											throw new Error(`Verification failed: ${text}`);
										});
									}
									return verifyRes.json();
								})
								.then(result => {
									console.log('Verification result:', result);
									if (result.success) {
										alert("✅ Payment Successful! Order #" + result.order_id + " has been created.");
										// Redirect to success page or home
										window.location.href = "{{ route('home') }}";
									} else {
										alert("❌ Payment Verification Failed: " + result.message);
										button.innerHTML = originalText;
										button.disabled = false;
									}
								})
								.catch(error => {
									console.error('Verification error:', error);
									alert("❌ Verification request failed: " + error.message);
									button.innerHTML = originalText;
									button.disabled = false;
								});
						},
						"prefill": {
							"name": formData.first_name + ' ' + formData.last_name,
							"email": formData.email,
							"contact": formData.phone
						},
						"theme": {
							"color": "#007bff"
						},
						"modal": {
							"ondismiss": function () {
								console.log('Payment modal closed by user');
								button.innerHTML = originalText;
								button.disabled = false;
							}
						},
						"retry": {
							"enabled": true,
							"max_count": 3
						}
					};

					var rzp = new Razorpay(options);
					rzp.open();
				})
				.catch(error => {
					console.error('Error creating order:', error);
					alert("❌ Error creating order: " + error.message);
					button.innerHTML = originalText;
					button.disabled = false;
				});
		};
	});
</script>