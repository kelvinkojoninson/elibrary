"use strict";

var InitBooks = function () {
    var table; // Holds the reference to the table element
    var toolbarBase; // Holds the reference to the base toolbar element
    var toolbarSelected; // Holds the reference to the selected toolbar element
    var selectedCount; // Holds the reference to the element displaying the count of selected items
    var datatable; // Holds the reference to the datatable object

    const FETCH_API_ENPOINT = APP_URL + '/api/books'; // API endpoint for fetching roles
    const DELETE_API_ENDPOINT = APP_URL + '/api/probooksducts/delete'; // API endpoint for deleting
    const EXPORT_API_ENPOINT = APP_URL + '/api/export'; // API endpoint for exporting data

    /**
     * Initialize the datatable.
     */
    const initTable = () => {
        var filterModal;
        var filterForm;
        var reloadButton;
        var resetButton;
        const filterElement = document.querySelector('#kt_modal_filter');
        var searchInput = document.querySelector('.search-input');
        var searchLoader = document.querySelector('[data-kt-table-filter="search-loader"]');

        if (filterElement) {
            filterModal = new bootstrap.Modal(filterElement);
            filterForm = $('#kt_modal_filter_form');
            reloadButton = filterElement.querySelector('[data-kt-table-filter="reload"]');
            resetButton = filterElement.querySelector('[data-kt-table-filter="reset"]');
        }

        // Initialize the datatable
        datatable = $(table).DataTable({
            "info": true,
            "processing": true, // Set to true to enable serverside processing 
            "serverSide": true, // Set to true to enable serverside processing 
            'order': [],
            "pageLength": 50,
            'columnDefs': [{
                    orderable: false,
                    targets: [0]
                },
                {
                    orderable: false,
                    targets: 15,
                    visible: permissions.modUpdate == 1 || permissions.modDelete == 1 ? true : false
                },
            ],
            // Configure AJAX settings for data retrieval
            ajax: {
                url: FETCH_API_ENPOINT,
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Display error message on AJAX request failure
                    Swal.fire({
                        title: "Request Timeout",
                        text: "Oops :( request timed out. This could be as a result of an internal server error or slow or no internet connection. In the meantime, you can check your internet connection, refresh the page or wait a few minutes. Please contact admin if it persists.",
                        icon: "info",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    return;
                },
                data: function (d) {
                    return 'modAction=modRead&modID=' + permissions.modID + '&dt_start=' + d.start + '&dt_length=' + d.length + '&dt_draw=' + d.draw + '&dt_search=' + searchInput.value + (filterForm ? '&' + filterForm.serialize() : '');
                },
                beforeSend: function () {
                    if (reloadButton) {
                        reloadButton.setAttribute('data-kt-indicator', 'on'); // Show loading indication 
                        reloadButton.disabled = true; // Disable button to avoid multiple click    
                    }

                    if (searchLoader) {
                        searchLoader.setAttribute('data-kt-indicator', 'on'); // Show loading indication 
                        searchLoader.disabled = true; // Disable button to avoid multiple click    
                    }
                },
                complete: function () {
                    if (reloadButton) {
                        reloadButton.removeAttribute('data-kt-indicator'); // Remove loading indication
                        reloadButton.disabled = false; // Enable button
                        filterModal.hide();
                    }

                    if (searchLoader) {
                        searchLoader.removeAttribute('data-kt-indicator', 'on'); // Show loading indication 
                        searchLoader.disabled = false; // Disable button to avoid multiple click    
                    }
                },
            },
            // Define the table columns
            columns: [{
                    className: "p-4",
                    defaultContent: `<div class="form-check form-check-sm form-check-custom">
                                    <input class="form-check-input" type="checkbox" value="1" />
                                </div>`,
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        return `<a href="${APP_URL}/book/${data.id}" class="text-gray-800 me-3">
                                   <u>${data.bookId}</u>
                               </a>`;
                    },
                },
                {
                    data: null,
                    render: function (data, type, full, meta) {
                        return `<a href="${APP_URL}/book/${data.id}" class="text-gray-800 me-3">
                                   <u>${data.title}</u>
                               </a>`;
                    }
                },
                {
                    data: "author",
                },
                {
                    data: "categoryName",
                },
                {
                    data: "genres",
                },
                {
                    data: "publicationYear",
                },
                {
                    data: "pages",
                },
                {
                    data: "isEbook",
                    render: function (data, type, full, meta) {
                        let status = '';
                        if (data == 'YES') {
                            status = `<div class="bg-success fw-bolder p-2 text-white">Yes</div>`;
                        } else if (data == 'NO') {
                            status = `<div class="bg-warning fw-bolder p-2 text-white">No</div>`;
                        }
                        return status;
                    },
                },
                {
                    data: "ebookFormat",
                },
                {
                    data: "shelfName",
                },
                {
                    data: "callNumber",
                },
                {
                    data: "condition",
                },
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, full, meta) {
                        let status = '';
                        if (data.status == 'ACTIVE') {
                            status = `<div class="bg-success fw-bolder p-2 text-white">Active</div>`;
                        } else if (data.status == 'INACTIVE') {
                            status = `<div class="bg-warning fw-bolder p-2 text-white">Inactive</div>`;
                        }
                        return status;
                    },
                },
                {
                    data: "dateCreated",
                },
                {
                    data: null,
                    className: "text-center text-nowrap",
                    render: function (data, type, full, meta) {
                        var actions = ``;

                        if (permissions.modUpdate == 1) {
                            actions += `<a href="${APP_URL + '/book/' + data.id}" class="btn btn-icon btn-info me-3">
                                             <i class="ki-outline ki-eye fs-1"></i>
                                        </a>`;
                        }


                        if (permissions.modDelete == 1) {
                            actions += `<button type="button" class="btn btn-icon btn-danger delete-btn me-3" data-action="delete" data-id="${data.id}" data-kt-table-filter="delete">
                                            <i class="ki-outline ki-eye fs-1"></i>
                                        </button>`;
                        }
                        return actions
                    }
                }
            ],
            // Define the datatable buttons
            buttons: [{
                    extend: 'print',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                    }
                },
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                    }
                },
                {
                    text: "Refresh",
                    action: function (e, dt, node, config) {
                        dt.ajax.reload(false, null);
                    }
                },
            ]
        });

        datatable.on('draw', function () {
            // Perform necessary actions after each draw event
            initToggleToolbar(); // Initialize toggle toolbar functionality
            handleDeleteRows(); // Handle deletion of rows
            toggleToolbars(); // Toggle toolbars based on permissions
        });

        if (searchInput) {
            // Attach keyup event listener to the search input
            searchInput.addEventListener('keyup', function () {
                // Perform search and redraw the datatable
                datatable.search(searchInput.value).draw();
            });
        }

        // Attach event listeners to the filter form's reload and reset buttons.
        // The reload button triggers datatable ajax reload.
        // The reset button resets the filter form and triggers datatable ajax reload.
        if (filterForm) {
            if (reloadButton) {
                reloadButton.addEventListener('click', function (e) {
                    datatable.ajax.reload();
                });
            }

            if (resetButton) {
                resetButton.addEventListener('click', function () {
                    const filterForm = document.querySelector('[data-kt-table-filter="form"]'); // Select filter options
                    const selectOptions = filterForm.querySelectorAll('select');

                    selectOptions.forEach(select => {
                        $(select).val('').trigger('change');
                    });

                    filterForm.reset();

                    datatable.ajax.reload();
                });
            }
        }
    };

    /**
     * Export the table data based on the selected format.
     * Requires appropriate permissions.
     */
    const exportTable = function () {
        if (permissions.modReport == 0) return;

        const element = document.getElementById('kt_modal_export');
        if (!element) return;

        const form = element.querySelector('#kt_modal_export_form');
        const modal = new bootstrap.Modal(element);

        // Initialize form validation
        var validator = FormValidation.formValidation(
            form, {
                fields: {
                    'format': {
                        validators: {
                            notEmpty: {
                                message: 'File format is required'
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Submit button handler
        const submitButton = element.querySelector('[data-kt-modal-action="submit"]');
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    if (status == 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        var exportFormat = document.getElementById('export-format').value;

                        // Disable submit button whilst loading
                        submitButton.setAttribute('data-kt-indicator', 'on'); // Show loading indication 
                        submitButton.disabled = true;

                        fetch(EXPORT_API_ENPOINT + '?modAction=modReport&modID=' + permissions.modID, {
                            method: "POST",
                            headers: {
                                'Authorization': `Bearer ${apiToken}`,
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                        }).then(function (res) {
                            return res.json()
                        }).then(function (data) {
                            if (!data.ok) {
                                submitButton.removeAttribute('data-kt-indicator'); // Remove loading indication
                                submitButton.disabled = false; // Enable button

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

                            submitButton.removeAttribute('data-kt-indicator'); // Remove loading indication
                            submitButton.disabled = false; // Enable button
                            modal.hide();

                            // Show popup confirmation 
                            Swal.fire({
                                text: "Data is ready to be exported!",
                                icon: "success",
                                allowOutsideClick: false,
                                buttonsStyling: false,
                                confirmButtonText: "Ok, Export!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function (result) {
                                if (exportFormat === 'print') datatable.button('.buttons-print').trigger();
                                else if (exportFormat === 'excel') datatable.button('.buttons-excel').trigger();
                                else if (exportFormat === 'csv') datatable.button('.buttons-csv').trigger();
                                else if (exportFormat === 'pdf') datatable.button('.buttons-pdf').trigger();
                            });
                        }).catch(function (err) {
                            if (err) {
                                submitButton.removeAttribute('data-kt-indicator'); // Remove loading indication
                                submitButton.disabled = false; // Enable button

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
                        });
                    } else {
                        Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            }
        });
    }

    /**
     * Handle the deletion of rows.
     */
    const handleDeleteRows = async () => {
        if (permissions.modDelete == 0) return;

        // Get all buttons with the specified data attribute
        const buttons = table.querySelectorAll('[data-action="delete"]');

        buttons.forEach(button => {
            button.addEventListener('click', async e => {
                e.preventDefault();

                // Get the ID of the selected record to be deleted
                let selected = JSON.stringify([e.currentTarget.getAttribute('data-id')]);

                // Display a confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure you want to delete selected record(s)?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(async (result) => {
                    if (result.value) {
                        // Show a loading message while the record(s) is being deleted
                        Swal.fire({
                            text: "Deleting record(s)...",
                            showConfirmButton: false,
                            allowEscapeKey: false,
                            allowOutsideClick: false
                        });

                        try {
                            // Send a request to delete the record(s)
                            const res = await fetch(DELETE_API_ENDPOINT + '?modAction=modDelete&modID=' + permissions.modID + '&selected=' + selected, {
                                method: "DELETE",
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
                                // Show an error message if the deletion is unsuccessful
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

                            // Show a success message after successful deletion and reload the table
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
                                datatable.ajax.reload();
                            });
                        } catch (error) {
                            // Show an error message if there is an error during the deletion process
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
                            // Enable the button after deletion is complete (if necessary)
                            // button.removeAttribute('data-kt-indicator');
                            // button.disabled = false;
                        }
                    } else if (result.dismiss === 'cancel') {
                        // Show a message if the deletion is canceled
                        Swal.fire({
                            text: "Record was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            });
        });
    }

    /**
     * Initialize the toggle toolbar functionality.
     */
    const initToggleToolbar = () => {
        if (permissions.modDelete == 0) return;

        // Select all checkboxes
        const checkboxes = table.querySelectorAll('[type="checkbox"]');

        // Select elements
        toolbarBase = document.querySelector('[data-kt-table-toolbar="base"]');
        toolbarSelected = document.querySelector('[data-kt-table-toolbar="selected"]');
        selectedCount = document.querySelector('[data-kt-table-select="selected_count"]');
        const deleteSelected = document.querySelector('[data-kt-table-select="delete_selected"]');

        // Toggle delete selected toolbar based on checkbox selection
        checkboxes.forEach(c => {
            c.addEventListener('click', function () {
                setTimeout(function () {
                    toggleToolbars(table);
                }, 50);
            });
        });

        // Delete selected rows
        deleteSelected.addEventListener('click', function () {
            Swal.fire({
                text: "Are you sure you want to delete selected record(s)?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.value) {
                    var arr = [];

                    checkboxes.forEach(c => {
                        const parent = c.closest('tr');

                        // Get the ID of the record if the checkbox is checked
                        var id = parent.querySelector('.delete-btn');
                        if (Boolean(id) && c.checked) {
                            arr.push(id.getAttribute('data-id'));
                        }
                    });

                    // Convert the IDs to JSON string
                    var selected = JSON.stringify(arr);

                    Swal.fire({
                        text: "Deleting record(s)...",
                        showConfirmButton: false,
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    });

                    fetch(DELETE_API_ENDPOINT + '?modAction=modDelete&modID=' + permissions.modID + '&selected=' + selected, {
                        method: "DELETE",
                        headers: {
                            'Authorization': `Bearer ${apiToken}`,
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                    }).then(function (res) {
                        return res.json()
                    }).then(function (data) {
                        if (!data.ok) {
                            // Show an error message if the deletion is unsuccessful
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

                        // Show a success message after successful deletion
                        Swal.fire({
                            text: "Record(s) deleted!.",
                            icon: "success",
                            buttonsStyling: false,
                            allowOutsideClick: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            datatable.ajax.reload();
                            table.querySelectorAll('[type="checkbox"]')[0].checked = false;
                        }).then(function () {
                            toggleToolbars(); // Detect checked checkboxes
                            initToggleToolbar(); // Re-init toolbar to recalculate checkboxes
                        });
                    }).catch(function (err) {
                        if (err) {
                            // Show an error message if there is an error during the deletion process
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
                } else if (result.dismiss === 'cancel') {
                    // Show a message if the deletion is canceled
                    Swal.fire({
                        text: "Selected record(s) not deleted.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }
            });
        });
    }

    /**
     * Toggle the visibility of toolbars based on checkbox selection.
     */
    const toggleToolbars = () => {
        // Select refreshed checkbox DOM elements 
        const allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');

        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;

        // Count checked boxes
        allCheckboxes.forEach(c => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        // Toggle toolbars based on checkbox selection
        if (checkedState) {
            selectedCount.innerHTML = count;
            if (toolbarBase) toolbarBase.classList.add('d-none');
            if (toolbarSelected) toolbarSelected.classList.remove('d-none');
        } else {
            if (toolbarBase) toolbarBase.classList.remove('d-none');
            if (toolbarSelected) toolbarSelected.classList.add('d-none');
        }
    }

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

    /**
     * Initialize the date picker for the specified element.
     * @param {string} element - The ID or class selector of the element.
     */
    const initDatePicker = function (element) {
        // Set the initial month and get the current year dynamically
        let month = 1;
        let year = moment().year();

        // Set the start and end dates using the month and year values
        var start = moment([year, month - 1]);
        var end = moment();

        // Callback function to update the displayed date range
        function cb(start, end) {
            $(element).html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
        }

        // Initialize the date range picker
        $(element).daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                "Today": [moment(), moment()],
                "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            },
        }, cb);

        // Update the displayed date range
        cb(start, end);
    }

    return {
        // Public functions  
        init: function () {
            table = document.getElementById('kt_table_common');
            if (!table) {
                return;
            }

            // Check if user has read permissions
            if (permissions.modRead == 0) {
                return;
            }

            // Initialize table
            initTable();

            // Initialize toggle toolbar functionality
            initToggleToolbar();

            // Export table functionality
            exportTable();

            // Toggle the checkbox and corresponding form
            handleCheckboxToggle('date_checkbox', 'date_checkbox_form');

            // Initialize the date picker
            initDatePicker('#filter_datepicker');
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    InitBooks.init();
});
