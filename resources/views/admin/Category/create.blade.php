<form id="catform" action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group mb-3">
        <label for="name">Name</label>
        <input type="text" name="name" id="category_name" class="form-control" required>
        <div class="error-div"><span></span></div>
    </div>

    {{-- <div class="form-group mb-3">
        <label for="parent_id">Parent Category (Optional)</label>
        <select name="parent_id" id="parent_id" class="form-control">
            <option value="">Select Parent Category</option>
            @foreach($mainCategories as $mainCategory)
            <option value="{{ $mainCategory->id }}">{{ $mainCategory->name }}</option>
            @endforeach
        </select>
    </div>
    --}}

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
            var submitBtn = $('#submitBtn');

            // Basic validation
            if (name.trim() === '') {
                $('.error-div span').text('Category name is required');
                return false;
            }

            // Clear previous errors
            $('.error-div span').text('');
            $('#image-error').text('');

            // Disable submit button and show loading
            submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Saving...');

            // Create FormData for file upload
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
                            $('.error-div span').text(errors.name[0]);
                        }
                        if (errors.image) {
                            $('#image-error').text(errors.image[0]);
                        }
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
                // Success message
                console.log('Success:', message);
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