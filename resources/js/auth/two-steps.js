"use strict";

// Class Definition
var SigninTwoSteps = function () {
    // Elements
    var form;
    var submitButton, resendButton;

    // Handle form
    var handleForm = function (e) {
        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            var validated = true;
            var token = '';
            var inputs = [].slice.call(form.querySelectorAll('.token-input'));
            inputs.map(function (input) {
                if (input.value === '' || input.value.length === 0) {
                    validated = false;
                }
            });

            if (validated === true) {
                inputs.map(function (input) {
                    token += input.value;
                });

                // Show loading indication
                submitButton.setAttribute('data-kt-indicator', 'on');

                // Disable button to avoid multiple click 
                submitButton.disabled = true;

                // Get form data
                var formData = new FormData(form);
                formData.append("token", token);

                // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                fetch(`${APP_URL}/auth/2fa/verify`, {
                    method: "POST",
                    body: formData,
                }).then(function (res) {
                    return res.json()
                }).then(function (data) {
                    if (!data.ok) {
                        // Remove loading indication
                        submitButton.removeAttribute('data-kt-indicator');

                        // Enable button
                        submitButton.disabled = false;

                        Swal.fire({
                            text: data.msg,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        return;
                    }
                    // Remove loading indication
                    submitButton.removeAttribute('data-kt-indicator');

                    // Enable button
                    submitButton.disabled = false;

                    Swal.fire({
                        text: "You have been successfully verified!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            inputs.map(function (input) {
                                input.value = '';
                            });

                            var redirectUrl = form.getAttribute('data-kt-redirect-url');
                            if (redirectUrl) {
                                window.location.replace(redirectUrl);
                            }
                        }
                    });
                }).catch(function (err) {
                    if (err) {
                        // Remove loading indication
                        submitButton.removeAttribute('data-kt-indicator');

                        // Enable button
                        submitButton.disabled = false;

                        Swal.fire({
                            text: err,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                })
            } else {
                swal.fire({
                    text: "Please enter valid security code and try again.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-light-primary"
                    }
                }).then(function () {
                    KTUtil.scrollTop();
                });
            }
        });
    }

    var resendCode = function (e) {
        // Handle form submit
        resendButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Show loading indication
            submitButton.setAttribute('data-kt-indicator', 'on');

            // Disable button to avoid multiple click 
            submitButton.disabled = true;

            // Get form data
            var formdata = new FormData(form);

            // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
            fetch(`${APP_URL}/auth/2fa/resend`, {
                method: "POST",
                body: formdata,
            }).then(function (res) {
                return res.json()
            }).then(function (data) {
                if (!data.ok) {
                    // Remove loading indication
                    submitButton.removeAttribute('data-kt-indicator');

                    // Enable button
                    submitButton.disabled = false;

                    Swal.fire({
                        text: data.msg,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    return;
                }
                // Remove loading indication
                submitButton.removeAttribute('data-kt-indicator');

                // Enable button
                submitButton.disabled = false;

                Swal.fire({
                    text: "Two Factor Authentication code resent!",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }).catch(function (err) {
                if (err) {
                    // Remove loading indication
                    submitButton.removeAttribute('data-kt-indicator');

                    // Enable button
                    submitButton.disabled = false;

                    Swal.fire({
                        text: err,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            })

        });
    }

    // Public functions
    return {
        // Initialization
        init: function () {
            form = document.querySelector('#kt_sing_in_two_steps_form');
            submitButton = document.querySelector('#kt_sing_in_two_steps_submit');
            resendButton = document.querySelector('#kt_resend_button');

            handleForm();
            resendCode();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    SigninTwoSteps.init();
});
