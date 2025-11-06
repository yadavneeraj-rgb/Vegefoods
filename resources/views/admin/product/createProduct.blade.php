<form id="productForm" action="{{ route('product.store') }}" method="POST">
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

    <div class="d-flex justify-content-between">
        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="submitBtn">Save Product</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#productForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            var name = $('#product_name').val();
            var submitBtn = $('#submitBtn');

            // Basic validation
            if (name.trim() === '') {
                $('#productForm .error-div span').text('Product name is required');
                return false;
            }

            // Clear previous errors
            $('#productForm .error-div span').text('');

            // Disable submit button and show loading
            submitBtn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Saving...');

            // AJAX request
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {
                        showToast('success', response.message);
                        $('#productForm')[0].reset();
                        $('.btn-close').click(); // Close offcanvas
                        reloadProductsTable();
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function (xhr) {
                    var errors = xhr.responseJSON.errors;
                    if (errors && errors.name) {
                        $('#productForm .error-div span').text(errors.name[0]);
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
            if (type === 'success') {
                // Success message
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