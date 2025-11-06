$(document).on('click', '.view-offcanvas', function(e){
    e.preventDefault();

    var offcanvas = $('#common-offcanvas');
    var size = $(this).data('size');
    if (size) {
        offcanvas.css('width', size);
    } else {
        offcanvas.css('width', '');
    }
    var offcanvasBody = offcanvas.find('.offcanvas-body');
    var url = $(this).data('url');
    var bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvas[0]) || new bootstrap.Offcanvas(offcanvas[0]);

    // Show offcanvas if not already visible
    bsOffcanvas.show();

    // Show loader
    offcanvasBody.html(`
        <div class="text-center p-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3">Loading content, please wait...</p>
        </div>
    `);

    // Load content
    $.get(url, function(response){
        offcanvasBody.html(response);
    }).fail(function(){
        offcanvasBody.html(`
            <div class="text-center p-5">
                <p class="text-danger mb-3">Failed to load offcanvas content.</p>
                <button type="button" class="btn btn-primary reload-offcanvas mb-2" data-url="${url}">Reload</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Close</button>
            </div>
        `);
    });
});

// Reload functionality
$(document).on('click', '.reload-offcanvas', function(){
    var offcanvas = $('#common-offcanvas');
    var offcanvasBody = offcanvas.find('.offcanvas-body');
    var url = $(this).data('url');

    offcanvasBody.html(`
        <div class="text-center p-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3">Reloading content, please wait...</p>
        </div>
    `);

    $.get(url, function(response){
        offcanvasBody.html(response);
    }).fail(function(){
        offcanvasBody.html(`
            <div class="text-center p-5">
                <p class="text-danger mb-3">Failed to reload offcanvas content.</p>
                <button type="button" class="btn btn-primary reload-offcanvas mb-2" data-url="${url}">Reload</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Close</button>
            </div>
        `);
    });
});

