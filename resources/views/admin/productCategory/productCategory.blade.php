@extends('admin.layouts.master')
@section('title', 'Product Category | Neeraj - Ecommerce')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Assign Products to Categories</h4>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Assignment Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Assign Categories to Product</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('productCategory.assign') }}" method="POST" id="assignmentForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_id" class="form-label">Select Product</label>
                                <select class="form-select" id="product_id" name="product_id" required>
                                    <option value="">Choose Product...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_ids" class="form-label">Select Categories</label>
                                <select class="form-select" id="category_ids" name="category_ids[]" multiple required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->parent ? $category->parent->name . ' -> ' : '' }}{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Assign Categories</button>
                </form>
            </div>
        </div>

        <!-- Assigned Products Table -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Currently Assigned Products</h5>
            </div>
            <div class="card-body">
                @if($assignedProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Assigned Categories</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignedProducts as $productAssignments)
                                    @php
                                        $product = $productAssignments->first()->product;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                        </td>
                                        <td>
                                            @foreach($productAssignments as $assignment)
                                                @if($assignment->category)
                                                    <span class="badge bg-primary me-1 mb-1">
                                                        {{ $assignment->category->parent ? $assignment->category->parent->name . ' -> ' : '' }}
                                                        {{ $assignment->category->name }}
                                                        <form action="{{ route('productCategory.remove', $assignment->id) }}" method="POST"
                                                            class="d-inline" onsubmit="return confirm('Remove this category assignment?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-link text-white p-0 ms-1">
                                                                ×
                                                            </button>
                                                        </form>
                                                    </span>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-assignment"
                                                data-product-id="{{ $product->id }}">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        No products have been assigned to categories yet.
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Initialize select2 for better UX
            $('#product_id').select2({
                placeholder: "Select a product",
                allowClear: true
            });

            $('#category_ids').select2({
                placeholder: "Select categories",
                allowClear: true
            });

            // Load categories when product is selected for editing
            $('#product_id').change(function () {
                var productId = $(this).val();
                if (productId) {
                    $.ajax({
                        url: "{{ url('admin/product-categories') }}/" + productId,
                        type: 'GET',
                        success: function (data) {
                            $('#category_ids').val(data).trigger('change');
                        },
                        error: function (xhr) {
                            console.log('Error loading categories');
                        }
                    });
                } else {
                    $('#category_ids').val(null).trigger('change');
                }
            });

            // Edit assignment
            $('.edit-assignment').click(function () {
                var productId = $(this).data('product-id');
                $('#product_id').val(productId).trigger('change');

                // Scroll to form
                $('html, body').animate({
                    scrollTop: $("#assignmentForm").offset().top - 100
                }, 500);
            });
        });
    </script>

    <style>
        .badge {
            font-size: 0.875em;
            padding: 0.5em 0.75em;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }
    </style>
@endsection