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
					<form action="#" class="billing-form">
						<h3 class="mb-4 billing-heading">Billing Details</h3>
						<div class="row align-items-end">
							<div class="col-md-6">
								<div class="form-group">
									<label for="firstname">Firt Name</label>
									<input type="text" class="form-control" placeholder="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="lastname">Last Name</label>
									<input type="text" class="form-control" placeholder="">
								</div>
							</div>
							<div class="w-100"></div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="country">State / Country</label>
									<div class="select-wrap">
										<div class="icon"><span class="ion-ios-arrow-down"></span></div>
										<select name="" id="" class="form-control">
											<option value="">France</option>
											<option value="">Italy</option>
											<option value="">Philippines</option>
											<option value="">South Korea</option>
											<option value="">Hongkong</option>
											<option value="">Japan</option>
										</select>
									</div>
								</div>
							</div>
							<div class="w-100"></div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="streetaddress">Street Address</label>
									<input type="text" class="form-control" placeholder="House number and street name">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="text" class="form-control"
										placeholder="Appartment, suite, unit etc: (optional)">
								</div>
							</div>
							<div class="w-100"></div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="towncity">Town / City</label>
									<input type="text" class="form-control" placeholder="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="postcodezip">Postcode / ZIP *</label>
									<input type="text" class="form-control" placeholder="">
								</div>
							</div>
							<div class="w-100"></div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="phone">Phone</label>
									<input type="text" class="form-control" placeholder="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="emailaddress">Email Address</label>
									<input type="text" class="form-control" placeholder="">
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
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('placeOrderBtn').onclick = function (e) {
        e.preventDefault();

        console.log('Button clicked'); // Debug log

        // Ensure user accepted terms
        if (!document.getElementById('termsCheck').checked) {
            alert("⚠️ Please accept the terms & conditions before proceeding.");
            return;
        }

        // Proceed with Razorpay
        const total = "{{ $total }}";
        console.log('Total:', total); // Debug log

        fetch("{{ route('razorpay.order') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ total: total })
        })
        .then(res => {
            console.log('Response status:', res.status);
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(data => {
            console.log('Razorpay data:', data); // Debug log
            
            if (!data.order_id) {
                throw new Error('No order ID received');
            }

            var options = {
                "key": data.razorpay_key,
                "amount": data.amount,
                "currency": "INR",
                "name": "Neeraj Ecommerce",
                "description": "Order Payment",
                "order_id": data.order_id,
                "handler": function (response) {
                    console.log('Payment response:', response); // Debug log
                    
                    fetch("{{ route('razorpay.verify') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(response)
                    })
                    .then(res => res.json())
                    .then(result => {
                        console.log('Verification result:', result); // Debug log
                        if (result.success) {
                            alert("✅ Payment Successful!");
                        } else {
                            alert("❌ Payment Verification Failed: " + result.message);
                        }
                    })
                    .catch(error => {
                        console.error('Verification error:', error);
                        alert("❌ Verification request failed");
                    });
                },
                "prefill": {
                    "name": data.name,
                    "email": data.email,
                    "contact": data.contact
                },
                "theme": {
                    "color": "#007bff"
                },
                "modal": {
                    "ondismiss": function() {
                        console.log('Payment modal closed');
                    }
                }
            };
            
            var rzp = new Razorpay(options);
            rzp.open();
        })
        .catch(error => {
            console.error('Error:', error);
            alert("❌ Error creating order: " + error.message);
        });
    };
});
</script>