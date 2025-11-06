@extends('admin.layouts.master')
@section('title', 'Neeraj | Categories')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">All Categories</h4>
                <button class="btn btn-primary view-offcanvas" data-size="400px" data-url="{{ route('category.create') }}">
                    <i class="mdi mdi-plus"></i> Add New Category
                </button>
            </div>
        </div>
    </div>

    <div class="row" id="categories-table-container">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="categoriesTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $index => $category)
                                        <tr id="category-{{ $category->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($category->image)
                                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                                        class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $category->status ? 'success' : 'danger' }}">
                                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $category->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-category" data-id="{{ $category->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-category" data-id="{{ $category->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                                <button class="btn btn-sm btn-secondary view-offcanvas" data-size="700px"
                                                    data-url="{{ route('category.subcategories', ['id' => $category->id]) }}">
                                                    <i class="mdi mdi-plus">Add Sub Categories</i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <h5>No categories found.</h5>
                            <p>Click the "Add New Category" button to create your first category.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.offcanvas.offcanvas')

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_category_id" name="id">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="edit_category_name" class="form-label">Category Name</label>
                            <input type="text" name="name" id="edit_category_name" class="form-control"
                                placeholder="Enter category name" required>
                            <div class="error-div"><span class="text-danger"></span></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_category_image" class="form-label">Category Image</label>
                            <input type="file" name="image" id="edit_category_image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                            <div class="mt-2" id="current-image-container"></div>
                            <div class="error-div"><span class="text-danger" id="edit-image-error"></span></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="editSubmitBtn">Update Category</button>
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

            // Edit Category - Load data into modal
            $(document).on('click', '.edit-category', function () {
                var categoryId = $(this).data('id');

                // Clear previous errors
                $('#editCategoryForm .error-div span').text('');
                $('#edit-image-error').text('');

                // Load category data
                $.ajax({
                    url: '/category/' + categoryId + '/edit',
                    method: 'GET',
                    success: function (response) {
                        $('#edit_category_id').val(response.category.id);
                        $('#edit_category_name').val(response.category.name);
                        $('#editCategoryForm').attr('action', '/category/' + categoryId);

                        // Show current image if exists
                        if (response.category.image) {
                            $('#current-image-container').html(
                                '<p><strong>Current Image:</strong></p>' +
                                '<img src="/storage/' + response.category.image + '" alt="Current image" class="img-thumbnail" style="max-height: 100px;">'
                            );
                        } else {
                            $('#current-image-container').html('<p class="text-muted">No image uploaded</p>');
                        }
                    },
                    error: function () {
                        showToast('error', 'Error loading category data.');
                    }
                });
            });

            // Update Category Form Submission (Modal)
            $('#editCategoryForm').on('submit', function (e) {
                e.preventDefault();

                var categoryId = $('#edit_category_id').val();
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
                            $('#editCategoryModal').modal('hide');
                            reloadCategoriesTable();
                        } else {
                            showToast('error', response.message);
                        }
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            if (errors.name) {
                                $('#editCategoryForm .error-div span').text(errors.name[0]);
                            }
                            if (errors.image) {
                                $('#edit-image-error').text(errors.image[0]);
                            }
                        } else {
                            showToast('error', 'An error occurred while updating category.');
                        }
                    },
                    complete: function () {
                        submitBtn.prop('disabled', false).html('Update Category');
                    }
                });
            });

            // Delete Category
            $(document).on('click', '.delete-category', function () {
                var categoryId = $(this).data('id');

                if (confirm('Are you sure you want to delete this category?')) {
                    $.ajax({
                        url: '/category/' + categoryId,
                        method: 'DELETE',
                        success: function (response) {
                            if (response.success) {
                                showToast('success', response.message);
                                reloadCategoriesTable();
                            }
                        },
                        error: function () {
                            showToast('error', 'Error deleting category');
                        }
                    });
                }
            });

            // Reload categories table
            function reloadCategoriesTable() {
                $.ajax({
                    url: '{{ route("category") }}',
                    method: 'GET',
                    success: function (data) {
                        $('#categories-table-container').html($(data).find('#categories-table-container').html());
                    },
                    error: function () {
                        showToast('error', 'Error loading categories');
                    }
                });
            }

            function showToast(type, message) {
                if (type === 'success') {
                    // Success message
                    console.log('Success:', message);
                } else {
                    alert('Error: ' + message);
                }
            }

            // Clear form when modal is hidden
            $('#editCategoryModal').on('hidden.bs.modal', function () {
                $('#editCategoryForm .error-div span').text('');
                $('#edit-image-error').text('');
                $('#current-image-container').empty();
            });
        });
    </script>
@endpush