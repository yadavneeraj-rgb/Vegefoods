{{-- admin/product/product.blade.php --}}
@extends('admin.layouts.master')
@section('title', 'Neeraj | Products')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">All Products</h4>
                <button class="btn btn-primary view-offcanvas" data-size="400px" data-url="{{ route('product.create') }}">
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
                                        <th>Status</th>
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
                                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                                         alt="{{ $product->name }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                          
                                            <td>{{ Str::limit($product->description, 50) }}</td>
                                           
                                            <td>
                                                <span class="badge bg-{{ $product->status ? 'success' : 'danger' }}">
                                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                                </span>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProductForm" method="POST" enctype="multipart/form-data"> {{-- Added enctype --}}
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
                        $('#editProductForm').attr('action', '/product/' + productId);
                        
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
                if (type === 'success') {
                    // Success message (you can use Toastr here)
                    console.log('Success:', message);
                } else {
                    alert('Error: ' + message);
                }
            }

            // Clear form when modal is hidden
            $('#editProductModal').on('hidden.bs.modal', function () {
                $('#editProductForm .error-div span').text('');
                $('#edit-image-error').text('');
                $('#current-image-container').empty();
            });
        });
    </script>
@endpush