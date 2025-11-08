<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Subcategories of {{ $category->name }}</h4>
        </div>
    </div>
</div>

<!-- Add Subcategory Form -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Add New Subcategory</h5>
            </div>
            <div class="card-body">
                <form id="subcategoryForm" method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $category->id }}">
                    <!-- Inherit module_id from parent category -->
                    <input type="hidden" name="module_id" value="{{ $category->module_id }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Subcategory Name</label>
                                <input type="text" name="name" id="subcategory_name" class="form-control"
                                    placeholder="Enter subcategory name" required>
                                <div class="error-div"><span class="text-danger"></span></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="image" class="form-label">Subcategory Image</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                    <i class="mdi mdi-plus"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Show module information -->
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <small>
                                    <strong>Module:</strong> 
                                    @if($category->module)
                                        <span class="badge bg-info">{{ $category->module->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">Not Assigned</span>
                                    @endif
                                    - Subcategory will automatically inherit this module
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Subcategories Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Existing Subcategories</h5>
            </div>
            <div class="card-body">
                @if($subcategories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="subcategoriesTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Module</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subcategories as $subcategory)
                                    <tr id="subcategory-{{ $subcategory->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($subcategory->image)
                                                <img src="{{ asset('storage/' . $subcategory->image) }}" 
                                                     alt="{{ $subcategory->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $subcategory->name }}</td>
                                        <td>
                                            @if($subcategory->module_id && $subcategory->module)
                                                <span class="badge bg-info">{{ $subcategory->module->name }}</span>
                                            @else
                                                <span class="badge bg-secondary">Not Assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $subcategory->status ? 'success' : 'danger' }}">
                                                {{ $subcategory->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $subcategory->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-category"
                                                data-id="{{ $subcategory->id }}" data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModal">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-category"
                                                data-id="{{ $subcategory->id }}">
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
                        <h5>No subcategories found.</h5>
                        <p>Use the form above to add subcategories for {{ $category->name }}.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Subcategory Form Submission
        $('#subcategoryForm').on('submit', function (e) {
            e.preventDefault();

            var submitBtn = $('#submitBtn');

            submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Adding...');

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
                        // Show success message
                        alert('Success: ' + response.message);

                        // Clear form
                        $('#subcategoryForm')[0].reset();

                        // Reload the offcanvas content to show new subcategory
                        reloadSubcategoriesList();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function (xhr) {
                    var errors = xhr.responseJSON.errors;
                    if (errors && errors.name) {
                        $('#subcategoryForm .error-div span').text(errors.name[0]);
                    } else {
                        alert('An error occurred while creating subcategory.');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false).html('<i class="mdi mdi-plus"></i> Add');
                }
            });
        });

        function reloadSubcategoriesList() {
            // Get the current category ID from the hidden field
            var categoryId = $('input[name="parent_id"]').val();

            // Reload the offcanvas content
            $.ajax({
                url: '{{ url('subcategories') }}/' + categoryId,
                method: 'GET',
                success: function (data) {
                    // Replace the offcanvas body content
                    $('.offcanvas-body').html(data);
                },
                error: function () {
                    alert('Error reloading subcategories');
                }
            });
        }

        // Edit and Delete functionality
        $(document).on('click', '.edit-category', function () {
            var categoryId = $(this).data('id');
            // Your edit logic here - this will open the main edit modal
        });

        $(document).on('click', '.delete-category', function () {
            var categoryId = $(this).data('id');
            if (confirm('Are you sure you want to delete this subcategory?')) {
                $.ajax({
                    url: '/category/' + categoryId,
                    method: 'DELETE',
                    success: function (response) {
                        if (response.success) {
                            alert('Success: ' + response.message);
                            reloadSubcategoriesList();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            }
        });
    });
</script>