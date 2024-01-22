"use strict";
var initProfileSettings = function () {
    /**
     * Handles the form submission using an asynchronous request.
     * @param {string} API_ENDPOINT - The API endpoint URL.
     * @param {string} formElement - The selector for the form element.
     * @param {boolean} formReset - Whether to reset the form after successful submission.
     * @param {string} redirectTo - The URL to redirect to after successful submission.
     */
    const handlePost = async (API_ENDPOINT, formElement, formReset, redirectTo) => {
        const element = document.querySelector(formElement);
        if (!element) return;

        const form = element.querySelector('form');
        if (!form) return;

        const submitButton = element.querySelector('[data-action="submit"]');
        if (!submitButton) return;

        const selectOptions = form.querySelectorAll('select');

        submitButton.addEventListener('click', async e => {
            e.preventDefault();
            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            let formData = new FormData(form);
            formData.append("modAction", "modUpdate"); // Append Extra
            formData.append("modID", permissions.modID); // Append Extra
            
            try {
                const res = await fetch(API_ENDPOINT, {
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
                        formReset && form.reset();
                        !formReset && redirectTo && (window.location = redirectTo);
                        selectOptions.forEach(select => $(select).val('').trigger('change'));
                    }
                });
            } catch (error) {
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

    var initSettings = function () {
        var passwordMainEl = document.getElementById('kt_signin_password');
        var passwordEditEl = document.getElementById('change-password');
        var profileMainEl = document.getElementById('kt_view_profile');
        var profileEditEl = document.getElementById('update-profile-form');

        // button elements
        var passwordChange = document.getElementById('kt_signin_password_button');
        var passwordCancel = document.getElementById('kt_password_cancel');
        var profileChange = document.getElementById('kt_view_profile_button');
        var profileCancel = document.getElementById('kt_profile_cancel');

        // toggle UI
        passwordChange.querySelector('button').addEventListener('click', function () {
            toggleChangePassword();
        })

        profileChange.addEventListener('click', function () {
            toggleChangeProfile();
        });

        passwordCancel.addEventListener('click', function () {
            toggleChangePassword();
        });

        profileCancel.addEventListener('click', function () {
            toggleChangeProfile();
        });

        var toggleChangePassword = function () {
            passwordMainEl.classList.toggle('d-none');
            passwordChange.classList.toggle('d-none');
            passwordEditEl.classList.toggle('d-none');
        }

        var toggleChangeProfile = function () {
            profileMainEl.classList.toggle('d-none');
            profileChange.classList.toggle('d-none');
            profileEditEl.classList.toggle('d-none');
        }
    }

    return {
        // Public functions  
        init: function () {
            // Initializes the settings.
            initSettings();

            // Handles the form submission for updating the user profile.
            handlePost(
                `${APP_URL}/api/users/update`,
                '#update-profile',
                false,
                `${APP_URL}/profile`
            );

            // Handles the form submission for resetting the user's password.
            handlePost(
                `${APP_URL}/api/users/reset-password`,
                '#change-password',
                true,
                null,
            );

            // Handles the form submission for deleting a user.
            handlePost(
                `${APP_URL}/api/users/delete`,
                '#kt_modal_add_common',
                true,
                null
            );
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    initProfileSettings.init();
});
