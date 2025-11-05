<form id="catform" action="{{ route('category.store') }}" method="POST">
    @csrf
    <div class="form-group mb-3">
        <label for="name">Name</label>
        <input type="text" name="name" id="category_name" class="form-control" required>
        <div class="error-div"><span></span></div>
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
            e.preventDefault(); // Prevent default form submission

            var name = $('#category_name').val();
            var submitBtn = $('#submitBtn');

            // Basic validation
            if (name.trim() === '') {
                $('.error-div span').text('Category name is required');
                return false;
            }

            // Clear previous errors
            $('.error-div span').text('');

            // Disable submit button and show loading
            submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Saving...');

            // AJAX request
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {
                        // Show success message
                        showToast('success', response.message);

                        // Clear form
                        $('#catform')[0].reset();

                        // Close offcanvas if exists
                        $('.btn-close').click();

                        // Reload categories table (you'll need to implement this)
                        reloadCategoriesTable();

                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function (xhr) {
                    var errors = xhr.responseJSON.errors;
                    if (errors && errors.name) {
                        $('.error-div span').text(errors.name[0]);
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
            // You can use Toastr or any other notification library
            // For now, using simple alert. Replace with your preferred notification system
            if (type === 'success') {
                // alert('Success: ' + message);
            } else {
                alert('Error: ' + message);
            }
        }

        function reloadCategoriesTable() {
            // This will reload the categories table without refreshing the page
            $.ajax({
                url: '{{ route("category") }}',
                method: 'GET',
                success: function (data) {
                    // Update the categories table section
                    // You'll need to have a specific container for the table
                    $('#categories-table-container').html($(data).find('#categories-table-container').html());
                }
            });
        }
    });
</script>