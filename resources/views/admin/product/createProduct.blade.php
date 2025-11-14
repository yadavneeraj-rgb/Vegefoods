<form id="productForm" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group mb-3">
        <label for="name">Product Name</label>
        <input type="text" name="name" id="product_name" class="form-control" placeholder="Enter product name" required>
        <div class="error-div"><span class="text-danger"></span></div>
    </div>

    <div class="form-group mb-3">
        <label for="description">Description</label>
        <textarea name="description" id="product_description" class="form-control"
            placeholder="Enter product description" rows="3"></textarea>
    </div>

    <div class="form-group mb-3">
        <label for="search_tag">Search Tags</label>
        <input type="text" name="search_tag" id="product_search_tag" class="form-control"
            placeholder="Enter search tags (comma separated)">
        <small class="text-muted">Separate multiple tags with commas</small>
    </div>

    <div class="form-group mb-3">
        <label for="image">Product Image</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        <small class="text-muted">Allowed formats: jpeg, png, jpg, gif, webp. Max size: 2MB</small>
        <div class="error-div"><span class="text-danger" id="image-error"></span></div>
    </div>

    <!-- Quantity Field -->
    <div class="form-group mb-3">
        <label for="quantity" class="form-label">Quantity</label>
        <input type="number" name="quantity" id="quantity" class="form-control" 
            placeholder="Enter available quantity" min="0" required>
        <small class="text-muted">Number of items available in stock</small>
    </div>

    <!-- Pricing Section -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Pricing Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="mrp_base_price" class="form-label">MRP / Base Price (₹)</label>
                        <input type="number" name="mrp_base_price" id="mrp_base_price" class="form-control" 
                            placeholder="Enter base price" step="0.01" min="0" required>
                        <small class="text-muted">Original price before tax/discount</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="tax_percentage" class="form-label">Tax Percentage (%)</label>
                        <input type="number" name="tax_percentage" id="tax_percentage" class="form-control" 
                            placeholder="Enter tax percentage" step="0.01" min="0" max="100" required>
                        <small class="text-muted">GST/VAT percentage</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="discount_type" class="form-label">Discount Type</label>
                        <select name="discount_type" id="discount_type" class="form-control">
                            <option value="">No Discount</option>
                            <option value="flat">Flat Amount</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="discount_value" class="form-label">Discount Value</label>
                        <input type="number" name="discount_value" id="discount_value" class="form-control" 
                            placeholder="Enter discount value" step="0.01" min="0" disabled>
                        <small class="text-muted" id="discount-hint">Select discount type first</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info" id="price-calculation">
                        <strong>Price Breakdown:</strong><br>
                        <span id="price-breakdown">Enter pricing details to see calculation</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <div class="form-check">
            <input type="checkbox" name="is_featured" id="product_featured" class="form-check-input" value="1">
            <label for="product_featured" class="form-check-label">Featured Product</label>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="submitBtn">Save Product</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        // Enable/disable discount value based on discount type
        $('#discount_type').change(function() {
            const discountType = $(this).val();
            const discountValue = $('#discount_value');
            const discountHint = $('#discount-hint');

            if (discountType) {
                discountValue.prop('disabled', false);
                if (discountType === 'flat') {
                    discountHint.text('Enter flat discount amount (e.g., 100 for ₹100 off)');
                } else {
                    discountHint.text('Enter percentage discount (e.g., 10 for 10% off)');
                }
            } else {
                discountValue.prop('disabled', true);
                discountValue.val('');
                discountHint.text('Select discount type first');
            }
            calculateFinalPrice();
        });

        // Calculate final price when pricing inputs change
        $('#mrp_base_price, #tax_percentage, #discount_value').on('input', function() {
            calculateFinalPrice();
        });

        function calculateFinalPrice() {
            const basePrice = parseFloat($('#mrp_base_price').val()) || 0;
            const taxPercentage = parseFloat($('#tax_percentage').val()) || 0;
            const discountType = $('#discount_type').val();
            const discountValue = parseFloat($('#discount_value').val()) || 0;

            // Calculate tax amount
            const taxAmount = (basePrice * taxPercentage) / 100;
            const priceAfterTax = basePrice + taxAmount;

            // Calculate discount amount
            let discountAmount = 0;
            if (discountType === 'flat') {
                discountAmount = discountValue;
            } else if (discountType === 'percentage') {
                discountAmount = (priceAfterTax * discountValue) / 100;
            }

            // Calculate final price
            const finalPrice = Math.max(0, priceAfterTax - discountAmount);

            // Update price breakdown
            let breakdown = `
                MRP: ₹${basePrice.toFixed(2)}<br>
                Tax (${taxPercentage}%): ₹${taxAmount.toFixed(2)}<br>
                ${discountType ? `Discount: ₹${discountAmount.toFixed(2)}<br>` : ''}
                <strong>Final Price: ₹${finalPrice.toFixed(2)}</strong>
            `;
            $('#price-breakdown').html(breakdown);
        }

        // Form submission handling
        $('#productForm').on('submit', function (e) {
            e.preventDefault();

            var name = $('#product_name').val();
            var quantity = $('#quantity').val();
            var submitBtn = $('#submitBtn');

            // Basic validation
            if (name.trim() === '') {
                $('#productForm .error-div span').text('Product name is required');
                return false;
            }

            if (quantity < 0) {
                showToast('error', 'Quantity cannot be negative');
                return false;
            }

            // Clear previous errors
            $('#productForm .error-div span').text('');
            $('#image-error').text('');

            // Disable submit button and show loading
            submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Saving...');

            // Create FormData object to handle file upload
            var formData = new FormData(this);

            // AJAX request
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message);
                        $('#productForm')[0].reset();
                        $('.btn-close').click();
                        reloadProductsTable();
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function (xhr) {
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        if (errors.name) {
                            $('#productForm .error-div span').text(errors.name[0]);
                        }
                        if (errors.image) {
                            $('#image-error').text(errors.image[0]);
                        }
                        if (errors.quantity) {
                            showToast('error', errors.quantity[0]);
                        }
                        if (errors.mrp_base_price) {
                            showToast('error', errors.mrp_base_price[0]);
                        }
                        if (errors.tax_percentage) {
                            showToast('error', errors.tax_percentage[0]);
                        }
                    } else {
                        showToast('error', 'An error occurred while creating product.');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false).html('Save Product');
                }
            });
        });

        function showToast(type, message) {
            // You can integrate with your toast library here
            if (type === 'success') {
                alert('Success: ' + message);
            } else {
                alert('Error: ' + message);
            }
        }

        function reloadProductsTable() {
            $.ajax({
                url: '{{ route("product") }}',
                method: 'GET',
                success: function (data) {
                    $('#products-table-container').html($(data).find('#products-table-container').html());
                }
            });
        }
    });
</script>