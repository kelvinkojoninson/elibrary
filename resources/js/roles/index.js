"use strict";
/**
InitRole is a module that handles the initialization and functionality of a role management CRUD.
It provides methods for initializing the table, handling record creation and update, toggling toolbars,
selecting checkboxes, and exporting table data. The module also checks for user permissions before enabling certain features.
*/
var InitRole = function () {
    var table; // Holds the reference to the table element
    var toolbarBase; // Holds the reference to the base toolbar element
    var toolbarSelected; // Holds the reference to the selected toolbar element
    var selectedCount; // Holds the reference to the element displaying the count of selected items
    var datatable; // Holds the reference to the datatable object

    const FETCH_API_ENPOINT = APP_URL + '/api/roles'; // API endpoint for fetching roles
    const CREATE_API_ENPOINT = APP_URL + '/api/roles'; // API endpoint for creating a role
    const UPDATE_API_ENPOINT = APP_URL + '/api/roles/update'; // API endpoint for updating a role
    const DELETE_API_ENDPOINT = APP_URL + '/api/roles/delete'; // API endpoint for deleting a role
    const EXPORT_API_ENPOINT = APP_URL + '/api/export'; // API endpoint for exporting data

    /**
     * Initialize the datatable.
     */
    const initTable = () => {
        var filterModal;
        var filterForm;
        var reloadButton;
        var resetButton;
        var searchInput = document.querySelector('.search-input');
        const filterElement = document.querySelector('#kt_modal_filter');
       
        if (filterElement) {
            filterModal = new bootstrap.Modal(filterElement);
            filterForm = $('#kt_modal_filter_form');
            reloadButton = filterElement.querySelector('[data-kt-table-filter="reload"]');
            resetButton = filterElement.querySelector('[data-kt-table-filter="reset"]');
        }

        // Initialize the datatable
        datatable = $(table).DataTable({
            "info": true,
            "processing": false, // Set to true to enable serverside processing 
            "serverSide": false, // Set to true to enable serverside processing 
            'order': [],
            "pageLength": 20,
            'columnDefs': [{
                    orderable: false,
                    targets: 0
                },
                {
                    orderable: false,
                    targets: 5,
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
                },
                complete: function () {
                    if (reloadButton) {
                        reloadButton.removeAttribute('data-kt-indicator'); // Remove loading indication
                        reloadButton.disabled = false; // Enable button
                        filterModal.hide();
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
                        // Render the title column
                        return `<span class="text-gray-800">${data.title}</span>`;
                    },
                },
                {
                    data: "status",
                    className: "text-center",
                    render: function (data, type, full, meta) {
                        // Render the status column
                        return data == 'ACTIVE' ? `<div class="bg-success">Active</div>` : `<div class="bg-danger text-white">Inactive</div>`;
                    },
                },
                {
                    data: "users",
                    className: "text-center",
                },
                {
                    data: "dateCreated"
                },
                {
                    data: null,
                    className: "text-center",
                    render: function (data, type, full, meta) {
                        // Render the actions column
                        var actions = ``;
                        if (permissions.modUpdate == 1) {
                            actions += `<span style="cursor:pointer" class="text-primary me-3" data-kt-table-filter="update_row">
                                            Edit
                                        </span>`;
                        }

                        if (permissions.modDelete == 1) {
                            actions += `<span style="cursor:pointer" class="text-danger me-3 delete-btn" data-action="delete" data-id="${data.id}" data-kt-table-filter="delete">
                                            Delete
                                        </span>`;
                        }
                        return actions;
                    }
                }
            ],
            // Define the datatable buttons
            buttons: [{
                    extend: 'print',
                    exportOptions: {
                        columns: [1, 2, 3, 4]
                    }
                },
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [1, 2, 3, 4]
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1, 2, 3, 4]
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
            handleUpdateRows(); // Handle updating of rows
            toggleToolbars(); // Toggle toolbars based on permissions
        });

        if (searchInput) {
            // Attach keyup event listener to the search input
            searchInput.addEventListener('keyup', function (e) {
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
     * Handle the creation of a record.
     */
    const handleCreateRecord = async () => {
        if (permissions.modCreate == 0) return;

        // Get the modal element
        const element = document.querySelector('#kt_modal_add_common');
        if (!element) return;

        // Create a modal instance
        const modal = new bootstrap.Modal(element);

        // Get the form element
        const form = element.querySelector('form');
        if (!form) return;

        // Get the submit button
        const submitButton = element.querySelector('[data-action="submit"]');
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
            formData.append("modAction", "modCreate"); // Append Extra
            formData.append("modID", permissions.modID); // Append Extra

            // Handle permission table data
            const permissionTable = form.querySelector('#kt_table_add');
            if (permissionTable) {
                const rows = permissionTable.querySelectorAll('[data-kt-row="permission-item"]');
                var permission = [];

                rows.forEach(row => {
                    permission.push({
                        modID: row.getAttribute('data-kt-id'),
                        modRead: row.querySelector('[name="modRead"]').checked ? 1 : 0,
                        modCreate: row.querySelector('[name="modCreate"]').checked ? 1 : 0,
                        modUpdate: row.querySelector('[name="modUpdate"]').checked ? 1 : 0,
                        modDelete: row.querySelector('[name="modDelete"]').checked ? 1 : 0,
                        modReport: row.querySelector('[name="modReport"]').checked ? 1 : 0,
                    });
                });
                formData.append('permissions', JSON.stringify(permission));
            }

            try {
                // Send a POST request to the create API endpoint
                const res = await fetch(CREATE_API_ENPOINT, {
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
                        datatable.ajax.reload();
                        selectOptions.forEach(select => $(select).val('').trigger('change'));
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
     * Handle the update of a record.
     */
    const handleUpdateRecord = async () => {
        if (permissions.modUpdate == 0) return;

        // Get the modal element
        const element = document.querySelector('#kt_modal_update_common');
        if (!element) return;

        // Create a modal instance
        const modal = new bootstrap.Modal(element);

        // Get the form element
        const form = element.querySelector('form');
        if (!form) return;

        // Get the submit button
        const submitButton = element.querySelector('[data-action="submit"]');
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

            // Handle permission table data
            const permissionTable = form.querySelector('#kt_table_update');
            if (permissionTable) {
                const rows = permissionTable.querySelectorAll('[data-kt-row="permission-item"]');
                var permission = [];

                rows.forEach(row => {
                    permission.push({
                        modID: row.getAttribute('data-kt-id'),
                        modRead: row.querySelector('[name="modRead"]').checked ? 1 : 0,
                        modCreate: row.querySelector('[name="modCreate"]').checked ? 1 : 0,
                        modUpdate: row.querySelector('[name="modUpdate"]').checked ? 1 : 0,
                        modDelete: row.querySelector('[name="modDelete"]').checked ? 1 : 0,
                        modReport: row.querySelector('[name="modReport"]').checked ? 1 : 0,
                    });
                });
                formData.append('permissions', JSON.stringify(permission));
            }

            try {
                // Send a POST request to the update API endpoint
                const res = await fetch(UPDATE_API_ENPOINT, {
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
                        datatable.ajax.reload();
                        selectOptions.forEach(select => $(select).val('').trigger('change'));
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
     * Handle the update of rows.
     */
    const handleUpdateRows = () => {
        if (permissions.modUpdate == 0) return;

        // Get all buttons with the specified data attribute
        const buttons = table.querySelectorAll('[data-kt-table-filter="update_row"]');

        buttons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                // Get the data associated with the clicked row
                const data = datatable.row($(e.target.closest('tbody tr'))).data();

                // Show the update modal and populate the form fields with data
                $("#kt_modal_update_common").modal("show");
                $("#update-code").val(data.id);
                $("#update-title").val(data.title);
                $("#update-status").val(data.status).trigger('change');

                // Update the permission checkboxes based on the data
                const rows = document.querySelector('#kt_table_update').querySelectorAll('[data-kt-row="permission-item"]');
                rows.forEach(row => {
                    data.permissions.forEach(element => {
                        if (element.modID == row.getAttribute('data-kt-id')) {
                            element.modRead == 1 ? row.querySelector('[name="modRead"]').checked = true : row.querySelector('[name="modRead"]').checked = false;
                            element.modCreate == 1 ? row.querySelector('[name="modCreate"]').checked = true : row.querySelector('[name="modCreate"]').checked = false;
                            element.modUpdate == 1 ? row.querySelector('[name="modUpdate"]').checked = true : row.querySelector('[name="modUpdate"]').checked = false;
                            element.modDelete == 1 ? row.querySelector('[name="modDelete"]').checked = true : row.querySelector('[name="modDelete"]').checked = false;
                            element.modReport == 1 ? row.querySelector('[name="modReport"]').checked = true : row.querySelector('[name="modReport"]').checked = false;
                        }
                    });
                });
            })
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
                    text: "Are you sure you want to delete selected record(s)? All users with deleted roles will no longer have access to the system.",
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
                text: "Are you sure you want to delete selected record(s)? All users with deleted roles will no longer have access to the system.",
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
            toolbarBase.classList.add('d-none');
            toolbarSelected.classList.remove('d-none');
        } else {
            toolbarBase.classList.remove('d-none');
            toolbarSelected.classList.add('d-none');
        }
    }

    /**
     * Handle the selection of all children checkboxes based on the parent checkbox state.
     * @param {string} formElement - The selector for the parent form element containing the checkboxes.
     */
    const handleSelectAllChildren = (formElement) => {
        const formEle = document.querySelector(formElement);

        // Get all the kt-select-mod checkboxes
        const ktSelectModCheckboxes = formEle.querySelectorAll('input[kt-select-mod]');

        // Loop through each checkbox and add an event listener
        ktSelectModCheckboxes.forEach(ktSelectModCheckbox => {
            ktSelectModCheckbox.addEventListener('change', () => {
                // Get the value of the kt-select-mod checkbox
                const ktSelectModValue = ktSelectModCheckbox.getAttribute('kt-select-mod');

                // Get all the child checkboxes with the same child-mod value as the kt-select-mod value
                const childCheckboxes = formEle.querySelectorAll(`input[child-mod="${ktSelectModValue}"]`);

                // Loop through each child checkbox and check/uncheck it based on the parent kt-select-mod checkbox state
                childCheckboxes.forEach(childCheckbox => {
                    childCheckbox.checked = ktSelectModCheckbox.checked;
                });
            });
        });

        // Add an event listener to uncheck all child checkboxes when the parent kt-select-mod checkbox is unchecked
        formEle.addEventListener('change', (event) => {
            const clickedEle = event.target;
            if (clickedEle.getAttribute('kt-select-mod')) {
                const ktSelectModValue = clickedEle.getAttribute('kt-select-mod');
                const childCheckboxes = formEle.querySelectorAll(`input[child-mod="${ktSelectModValue}"]`);
                if (!clickedEle.checked) {
                    childCheckboxes.forEach(childCheckbox => {
                        childCheckbox.checked = false;
                    });
                }
            }
        });
    };

    /**
     * Handle the selection of "Select All" checkbox to toggle the check state of all checkboxes within a form.
     * @param {string} formElement - The selector for the parent form element containing the checkboxes.
     */
    const handleSelectAll = (formElement) => {
        const formEle = document.querySelector(formElement);

        // Define variables
        const selectAll = formEle.querySelector('.kt_roles_select_all');
        const allCheckboxes = formEle.querySelectorAll('[type="checkbox"]');

        // Handle check state
        selectAll.addEventListener('change', e => {

            // Apply check state to all checkboxes
            allCheckboxes.forEach(c => {
                c.checked = e.target.checked;
            });
        });
    }

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

            // Handle create record functionality
            handleCreateRecord();

            // Handle update record functionality
            handleUpdateRecord();

            // Initialize toggle toolbar functionality
            initToggleToolbar();

            // Export table functionality
            exportTable();

            // Handle select all children checkboxes for adding records
            handleSelectAllChildren('#kt_table_add');

            // Handle select all children checkboxes for updating records
            handleSelectAllChildren('#kt_table_update');

            // Handle select all checkbox for adding records
            handleSelectAll('#kt_table_add');

            // Handle select all checkbox for updating records
            handleSelectAll('#kt_table_update');
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    InitRole.init();
});
