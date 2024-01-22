"use strict";

var TwoFactorAuthentication = function () {
    const handleAddTwoFactor = async () => {
        // Get the modal element
        const element = document.querySelector('#kt_modal_two_factor_authentication');
        if (!element) return;

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
            let formData = new FormData(form);

            try {
                // Send a POST request to the create API endpoint
                const res = await fetch(APP_URL+'/api/users/add-2fa', {
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
                        location.reload();
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

    const handleRemoveTwoFactor = async () => {
        // Get the submit button
        const submitButton = document.querySelector('[data-kt-element="remove-submit"]');
        if (!submitButton) return;

        // Add click event listener to the submit button
        submitButton.addEventListener('click', async e => {
            e.preventDefault();
            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            try {
                // Send a POST request to the create API endpoint
                const res = await fetch(APP_URL+'/api/users/remove-2fa', {
                    method: "POST",
                    headers: {
                        'Authorization': `Bearer ${apiToken}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
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
                        location.reload();
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
        init: function () {
            // Handle forms
            handleAddTwoFactor();
            handleRemoveTwoFactor();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    TwoFactorAuthentication.init();
});
