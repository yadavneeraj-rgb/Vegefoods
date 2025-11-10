<form action="{{ route('module.update', $module->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="edit_module_id" name="id">
    <div class="modal-body">
        <div class="form-group mb-3">
            <label for="edit_module_name" class="form-label">Module Name</label>
            <input type="text" name="name" id="edit_module_name" value="{{ $module->name ?? '' }}" class="form-control"
                placeholder="Enter module name" required>
            <div class="error-div"><span class="text-danger"></span></div>
        </div>
        <div class="form-group mb-3">
            <label for="edit_module_image" class="form-label">module Image</label>
            <input type="file" name="image" id="edit_module_image" value="{{ $module->image ?? ''}}" class="form-control"
                accept="image/*">
            <small class="text-muted">Leave empty to keep current image</small>
            <div class="mt-2" id="current-image-container"></div>
            <div class="error-div"><span class="text-danger" id="edit-image-error"></span></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="editSubmitBtn">Update Module</button>
    </div>
</form>