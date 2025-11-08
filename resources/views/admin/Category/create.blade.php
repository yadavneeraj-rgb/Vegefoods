<form id="catform" action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group mb-3">
        <label for="name">Name</label>
        <input type="text" name="name" id="category_name" class="form-control" required>
        <div class="error-div"><span></span></div>
    </div>

    <!-- Module Selection Dropdown -->
    <div class="form-group mb-3">
        <label for="module_id">Select Module</label>
        <select name="module_id" id="module_id" class="form-control" required>
            <option value="">-- Select Module --</option>
            @foreach($modules as $module)
                <option value="{{ $module->id }}">{{ $module->name }}</option>
            @endforeach
        </select>
        <div class="error-div"><span></span></div>
    </div>

    <div class="form-group mb-3">
        <label for="image">Category Image</label>
        <input type="file" name="image" id="image" class="form-control" accept="image/*">
        <small class="text-muted">Allowed formats: jpeg, png, jpg, gif, webp. Max size: 2MB</small>
        <div class="error-div"><span class="text-danger" id="image-error"></span></div>
    </div>

    <div class="d-flex justify-content-between">
        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="submitBtn">Save Category</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#catform').on('submit', function (e) {
            e.preventDefault();

            var name = $('#category_name').val();
            var moduleId = $('#module_id').val();
            var submitBtn = $('#submitBtn');

            // Basic validation
            if (name.trim() === '') {
                $('.error-div span').first().text('Category name is required');
                return false;
            }

            if (!moduleId) {
                $('#module_id').siblings('.error-div').find('span').text('Please select a module');
                return false;
            }

            // Clear previous errors
            $('.error-div span').text('');
            $('#image-error').text('');

            // Disable submit button and show loading
            submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Saving...');

            // Create FormData from the form element - THIS IS THE KEY FIX
            var formData = new FormData(this);

            // Debug: Check what's in FormData
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // AJAX request
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        // Show success message
                        showToast('success', response.message);

                        // Clear form
                        $('#catform')[0].reset();

                        // Close offcanvas if exists
                        $('.btn-close').click();

                        // Reload categories table
                        reloadCategoriesTable();

                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function (xhr) {
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        if (errors.name) {
                            $('.error-div span').first().text(errors.name[0]);
                        }
                        if (errors.module_id) {
                            $('#module_id').siblings('.error-div').find('span').text(errors.module_id[0]);
                        }
                        if (errors.image) {
                            $('#image-error').text(errors.image[0]);
                        }
                        
                        // Log the errors for debugging
                        console.log('Validation errors:', errors);
                    } else {
                        showToast('error', 'An error occurred while creating category.');
                    }
                },
                complete: function () {
                    // Re-enable submit button
                    submitBtn.prop('disabled', false).html('Save Category');
                }
            });
        });

        function showToast(type, message) {
            if (type === 'success') {
                // Success message - you can replace with toast library
                alert('Success: ' + message);
            } else {
                alert('Error: ' + message);
            }
        }

        function reloadCategoriesTable() {
            $.ajax({
                url: '{{ route("category") }}',
                method: 'GET',
                success: function (data) {
                    $('#categories-table-container').html($(data).find('#categories-table-container').html());
                }
            });
        }
    });
</script>