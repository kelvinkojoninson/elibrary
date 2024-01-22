<div class="list-loader mb-10">
    <div class="d-flex flex-column flex-center mt-4 p-4 h-xl-100 empty-list">
        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
            <img src="{{ asset('assets/images/empty.png') }}"
                style="width: 300px !important; height: 300px !important;">
        </div>
        <div class="fs-1 fw-bolder text-dark mb-4 mt-4">No items found.</div>
         <div class="fs-6 mt-4 text-dark text-center">
                <p>If you're using a custom filter, try adjusting the filters.</p>
                <p>Otherwise create some data!</p>
            </div>
    </div>

    <div class="row g-6 mb-6 g-xl-9 mb-6 mb-xl-9 list-container"></div>

    <div class="d-flex flex-center flex-column mt-6">
        <i class="text-danger fs-7 me-3 mb-3 error-message" style="display: none">
            Failed to fetch items. Please try again later.
        </i>
        <button class="btn btn-danger load-more-button">
            <span class="indicator-label">
                Show more
            </span>
            <span class="indicator-progress">
                Please wait... 
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</div>
