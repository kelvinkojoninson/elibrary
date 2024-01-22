"use strict";
var InitBook = function () {
    /**
     * Handle the creation of a record.
     */
    const handleUpdateRecord = async () => {
        if (permissions.modCreate == 0) return;

        // Get the form element
        const form = document.querySelector('#edit-book-form');
        if (!form) return;

        // Get the submit button
        const submitButton = document.querySelector('[data-action="submit"]');
        if (!submitButton) return;

        // Get the select options
        const selectOptions = form.querySelectorAll('select');

        // Add click event listener to the submit button
        submitButton.addEventListener('click', async e => {
            e.preventDefault();
            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            // Create a new FormData object and append form data
            let formData = new FormData(form);
            formData.append("modAction", "modUpdate"); // Append Extra
            formData.append("modID", permissions.modID); // Append Extra

            Swal.fire({
                text: "Updating Book",
                showConfirmButton: false,
                allowEscapeKey: false,
                allowOutsideClick: false
            });

            try {
                // Send a POST request to the create API endpoint
                const res = await fetch(APP_URL + '/api/books/update', {
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
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Continue editing book",
                    cancelButtonText: "Return to books",
                    customClass: {
                        confirmButton: "btn btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(result => {
                    if (result.value) {
                       return;
                    } else {
                        window.location = form.getAttribute("data-kt-redirect");
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

    /**
     * Handles the toggling of a checkbox and shows or hides the corresponding form.
     * @param {string} checkbox - The ID of the checkbox element.
     * @param {string} checkform - The ID of the form element to be shown or hidden.
     */
    const handleCheckboxToggle = (checkbox, checkform) => {
        const option = document.getElementById(checkbox);
        const checkForm = document.getElementById(checkform);

        option.addEventListener('change', e => {
            const value = e.target.checked;

            if (value) {
                // Show the form
                checkForm.classList.remove('d-none');
            } else {
                // Hide the form
                checkForm.classList.add('d-none');
            }
        });
    }

    return {
        // Public functions  
        init: function () {
            // Initialize table
            handleCreateRecord();

            handleCheckboxToggle('is_ebook', 'ebook_checkbox_form');
            handleCheckboxToggle('use_external_url', 'is_ebook_url');
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    InitBook.init();
});
