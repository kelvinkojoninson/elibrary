"use strict";

// Class definition
var PassReset = function () {
    /**
     * Handle the password reset
     */
    const handlePasswordReset = async () => {
        // Get the modal element
        const element = document.querySelector('#change-password-modal');
        if (!element) return;

        // Create a modal instance
        const modal = new bootstrap.Modal(element);

        // Get the form element
        const form = element.querySelector('form');
        if (!form) return;

        // Get the submit button
        const submitButton = element.querySelector('[data-action="submit"]');
        if (!submitButton) return;

        // Add click event listener to the submit button
        submitButton.addEventListener('click', async e => {
            e.preventDefault();
            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            // Create a new FormData object and append form data
            let formData = new FormData(form);
         
            try {
                // Send a POST request to the create API endpoint
                const res = await fetch(`${APP_URL}/api/users/reset-password`, {
                    method: "POST",
                    headers: {
                        'Authorization': `Bearer ${apiToken}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                const {
                    ok,
                    msg
                } = await res.json();
                if (!ok) {
                    // Display error message if request is not successful
                    Swal.fire({
                        text: msg,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    return;
                }

                // Display success message
                Swal.fire({
                    text: msg,
                    icon: "success",
                    allowOutsideClick: false,
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        // Reset form and close modal on confirmation
                        form.reset();
                        modal.hide();
                     }
                });
            } catch (error) {
                // Display error message on request failure
                Swal.fire({
                    text: error.message,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            } finally {
                submitButton.removeAttribute('data-kt-indicator');
                submitButton.disabled = false;
            }
        });
    };

    return {
        // Public functions
        init: function () {
            handlePasswordReset();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    PassReset.init();
});
