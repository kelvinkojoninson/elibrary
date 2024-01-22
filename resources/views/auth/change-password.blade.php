<div class="modal fade" id="change-password-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
           <div class="modal-header" id="kt_modal_add_header">
                <h2 class="fw-bolder">Change Password</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
           <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="change-password-form" class="form">
                    @csrf
                    <div class="d-flex flex-column scroll-y me-n7 pe-7">
                        <div class="fv-row mb-7">
                            <label class="fw-bold fs-6 mb-2">Current Password</label>
                            <input name="current_password" type="password" class="form-control" required />
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fw-bold fs-6 mb-2">New Password</label>
                            <input name="password" type="password" class="form-control" required />
                         </div>
                        <div class="fv-row mb-7">
                             <label class="required fw-bold fs-6 mb-2">Confirm Password</label>
                            <input name="password_confirmation" type="password" class="form-control" required />
                        </div>
                    </div>
                    <div class="text-center pt-15">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
                        <button type="button" class="btn btn-primary" data-action="submit">
                            <span class="indicator-label">Save</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
