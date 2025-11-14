@extends('admin.layouts.master')
@section('title', 'Products | Neeraj - Ecommerce')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">All Products</h4>
                <button class="btn btn-primary view-offcanvas" data-size="600px" data-url="{{ route('product.create') }}">
                    <i class="mdi mdi-plus"></i> Add New Product
                </button>
            </div>
        </div>
    </div>

    <div class="row" id="products-table-container">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="productsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Stock Status</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr id="product-{{ $product->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                        class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ Str::limit($product->description, 50) }}</td>
                                            <td>
                                                @if($product->hasPricing())
                                                    <div class="text-end">
                                                        <div class="text-decoration-line-through text-muted small">
                                                            ₹{{ number_format($product->pricing->mrp_base_price, 2) }}
                                                        </div>
                                                        <div class="fw-bold text-success">
                                                            ₹{{ number_format($product->pricing->final_price, 2) }}
                                                        </div>
                                                        @if($product->pricing->discount_value > 0)
                                                            <div class="text-danger small">
                                                                {{ $product->pricing->discount_type === 'percentage' ? $product->pricing->discount_value . '%' : '₹' . $product->pricing->discount_value }}
                                                                off
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">Not set</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold {{ $product->quantity == 0 ? 'text-danger' : ($product->quantity <= 10 ? 'text-warning' : 'text-success') }}">
                                                    {{ $product->quantity }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $product->stock_status_badge }}">
                                                    {{ $product->stock_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $product->status ? 'success' : 'danger' }}">
                                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button
                                                    class="btn btn-sm featured-btn {{ $product->is_featured ? 'btn-warning' : 'btn-outline-warning' }}"
                                                    data-id="{{ $product->id }}" data-featured="{{ $product->is_featured }}">
                                                    <i
                                                        class="mdi {{ $product->is_featured ? 'mdi-star' : 'mdi-star-outline' }}"></i>
                                                    {{ $product->is_featured ? 'Featured' : 'Make Featured' }}
                                                </button>
                                            </td>
                                            <td>{{ $product->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-product" data-id="{{ $product->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#editProductModal">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-product" data-id="{{ $product->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <h5>No products found.</h5>
                            <p>Click the "Add New Product" button to create your first product.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.offcanvas.offcanvas')

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProductForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_product_id" name="id">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="edit_product_name" class="form-label">Product Name</label>
                            <input type="text" name="name" id="edit_product_name" class="form-control"
                                placeholder="Enter product name" required>
                            <div class="error-div"><span class="text-danger"></span></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_product_description" class="form-label">Description</label>
                            <textarea name="description" id="edit_product_description" class="form-control"
                                placeholder="Enter product description" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_product_search_tag" class="form-label">Search Tags</label>
                            <input type="text" name="search_tag" id="edit_product_search_tag" class="form-control"
                                placeholder="Enter search tags (comma separated)">
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_product_image" class="form-label">Product Image</label>
                            <input type="file" name="image" id="edit_product_image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                            <div class="mt-2" id="current-image-container"></div>
                            <div class="error-div"><span class="text-danger" id="edit-image-error"></span></div>
                        </div>

                        <!-- Quantity Field -->
                        <div class="form-group mb-3">
                            <label for="edit_quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="edit_quantity" class="form-control" 
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
                                            <label for="edit_mrp_base_price" class="form-label">MRP / Base Price (₹)</label>
                                            <input type="number" name="mrp_base_price" id="edit_mrp_base_price"
                                                class="form-control" placeholder="Enter base price" step="0.01" min="0"
                                                required>
                                            <small class="text-muted">Original price before tax/discount</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="edit_tax_percentage" class="form-label">Tax Percentage (%)</label>
                                            <input type="number" name="tax_percentage" id="edit_tax_percentage"
                                                class="form-control" placeholder="Enter tax percentage" step="0.01" min="0"
                                                max="100" required>
                                            <small class="text-muted">GST/VAT percentage</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="edit_discount_type" class="form-label">Discount Type</label>
                                            <select name="discount_type" id="edit_discount_type" class="form-control">
                                                <option value="">No Discount</option>
                                                <option value="flat">Flat Amount</option>
                                                <option value="percentage">Percentage</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="edit_discount_value" class="form-label">Discount Value</label>
                                            <input type="number" name="discount_value" id="edit_discount_value"
                                                class="form-control" placeholder="Enter discount value" step="0.01" min="0">
                                            <small class="text-muted" id="edit-discount-hint">Select discount type
                                                first</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info" id="edit-price-calculation">
                                            <strong>Price Breakdown:</strong><br>
                                            <span id="edit-price-breakdown">Enter pricing details to see calculation</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_featured" id="edit_product_featured"
                                    class="form-check-input" value="1">
                                <label for="edit_product_featured" class="form-check-label">Featured Product</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="editSubmitBtn">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('admin-assets/js/offcanvas/offcanvas.js') }}"></script>
    <script>
        $(document).ready(function () {
            // CSRF token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Toggle Featured Status
            $(document).on('click', '.featured-btn', function () {
                var productId = $(this).data('id');
                var isFeatured = $(this).data('featured');
                var button = $(this);

                $.ajax({
                    url: '/product/' + productId + '/toggle-featured',
                    method: 'POST',
                    data: {
                        _method: 'PUT'
                    },
                    success: function (response) {
                        if (response.success) {
                            showToast('success', response.message);

                            // Update button appearance
                            if (response.is_featured) {
                                button.removeClass('btn-outline-warning').addClass('btn-warning');
                                button.html('<i class="mdi mdi-star"></i> Featured');
                                button.data('featured', true);
                            } else {
                                button.removeClass('btn-warning').addClass('btn-outline-warning');
                                button.html('<i class="mdi mdi-star-outline"></i> Make Featured');
                                button.data('featured', false);
                            }
                        }
                    },
                    error: function () {
                        showToast('error', 'Error updating featured status');
                    }
                });
            });

            // Edit Product - Load data into modal
            $(document).on('click', '.edit-product', function () {
                var productId = $(this).data('id');

                // Clear previous errors
                $('#editProductForm .error-div span').text('');
                $('#edit-image-error').text('');

                // Load product data
                $.ajax({
                    url: '/product/' + productId + '/edit',
                    method: 'GET',
                    success: function (response) {
                        $('#edit_product_id').val(response.id);
                        $('#edit_product_name').val(response.name);
                        $('#edit_product_description').val(response.description);
                        $('#edit_product_search_tag').val(response.search_tag);
                        $('#edit_quantity').val(response.quantity);
                        $('#edit_product_featured').prop('checked', response.is_featured);
                        $('#editProductForm').attr('action', '/product/' + productId);

                        // Load pricing data
                        if (response.pricing) {
                            $('#edit_mrp_base_price').val(response.pricing.mrp_base_price);
                            $('#edit_tax_percentage').val(response.pricing.tax_percentage);
                            $('#edit_discount_type').val(response.pricing.discount_type);
                            $('#edit_discount_value').val(response.pricing.discount_value);

                            // Enable discount value if discount type is set
                            if (response.pricing.discount_type) {
                                $('#edit_discount_value').prop('disabled', false);
                                if (response.pricing.discount_type === 'flat') {
                                    $('#edit-discount-hint').text('Enter flat discount amount (e.g., 100 for ₹100 off)');
                                } else {
                                    $('#edit-discount-hint').text('Enter percentage discount (e.g., 10 for 10% off)');
                                }
                            }

                            // Calculate and show price breakdown
                            calculateEditPrice();
                        }

                        // Show current image if exists
                        if (response.image) {
                            $('#current-image-container').html(
                                '<p><strong>Current Image:</strong></p>' +
                                '<img src="/storage/' + response.image + '" alt="Current image" class="img-thumbnail" style="max-height: 100px;">'
                            );
                        } else {
                            $('#current-image-container').html('<p class="text-muted">No image uploaded</p>');
                        }
                    },
                    error: function () {
                        showToast('error', 'Error loading product data.');
                    }
                });
            });

            // Enable/disable discount value in edit modal
            $('#edit_discount_type').change(function () {
                const discountType = $(this).val();
                const discountValue = $('#edit_discount_value');
                const discountHint = $('#edit-discount-hint');

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
                calculateEditPrice();
            });

            // Calculate price when pricing inputs change in edit modal
            $('#edit_mrp_base_price, #edit_tax_percentage, #edit_discount_value').on('input', function () {
                calculateEditPrice();
            });

            function calculateEditPrice() {
                const basePrice = parseFloat($('#edit_mrp_base_price').val()) || 0;
                const taxPercentage = parseFloat($('#edit_tax_percentage').val()) || 0;
                const discountType = $('#edit_discount_type').val();
                const discountValue = parseFloat($('#edit_discount_value').val()) || 0;

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
                $('#edit-price-breakdown').html(breakdown);
            }

            // Update Product Form Submission (Modal)
            $('#editProductForm').on('submit', function (e) {
                e.preventDefault();

                var productId = $('#edit_product_id').val();
                var submitBtn = $('#editSubmitBtn');

                submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Updating...');

                // Create FormData for file upload
                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            showToast('success', response.message);
                            $('#editProductModal').modal('hide');
                            reloadProductsTable();
                        } else {
                            showToast('error', response.message);
                        }
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            if (errors.name) {
                                $('#editProductForm .error-div span').text(errors.name[0]);
                            }
                            if (errors.image) {
                                $('#edit-image-error').text(errors.image[0]);
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
                            showToast('error', 'An error occurred while updating product.');
                        }
                    },
                    complete: function () {
                        submitBtn.prop('disabled', false).html('Update Product');
                    }
                });
            });

            // Delete Product
            $(document).on('click', '.delete-product', function () {
                var productId = $(this).data('id');

                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        url: '/product/' + productId,
                        method: 'DELETE',
                        success: function (response) {
                            if (response.success) {
                                showToast('success', response.message);
                                reloadProductsTable();
                            }
                        },
                        error: function () {
                            showToast('error', 'Error deleting product');
                        }
                    });
                }
            });

            // Reload products table
            function reloadProductsTable() {
                $.ajax({
                    url: '{{ route("product") }}',
                    method: 'GET',
                    success: function (data) {
                        $('#products-table-container').html($(data).find('#products-table-container').html());
                    },
                    error: function () {
                        showToast('error', 'Error loading products');
                    }
                });
            }

            function showToast(type, message) {
                // You can integrate with your toast library here
                if (type === 'success') {
                    alert('Success: ' + message);
                } else {
                    alert('Error: ' + message);
                }
            }

            // Clear form when modal is hidden
            $('#editProductModal').on('hidden.bs.modal', function () {
                $('#editProductForm .error-div span').text('');
                $('#edit-image-error').text('');
                $('#current-image-container').empty();
                $('#edit-price-breakdown').html('Enter pricing details to see calculation');
            });
        });
    </script>
@endpush