/**
 * app-ecommerce-product-list
 */

'use strict';
const validationMessages = $('#validation-messages');

// Datatable (jquery)
$(function () {
    // Variable declaration for table
    var dt_product_table = $('.datatables-areas'),
        categoryView = baseUrl + '/admin/api-areas',
        offCanvasForm = $('#offcanvasAddArea'),
        select2 = $('.select2');

    if (select2.length) {
        var $this = select2;
        $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Select City',
            dropdownParent: $this.parent()
        });
    }

    if (dt_product_table.length) {
        var dt_category = dt_product_table.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: baseUrl + '/admin/api-areas',
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
                type: 'GET',
                data: {
                    filter_1 : filter_1,
                },
            },
            columns: [
                // columns according to JSON
                {data: ''},
                {data: 'id'},
                {data: 'name'},
                {data: 'city'},
                {data: 'created_at'},
                {data: 'action'}
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: 'control',
                    searchable: false,
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    searchable: false,
                    orderable: false,
                    targets: 1,
                    render: function (data, type, full, meta) {
                        return `<span>${full.fake_id}</span>`;
                    }
                },
                {
                    // Category name
                    targets: 2,
                    render: function (data, type, full, meta) {
                        var $name = full['name'];

                        return '<span class="category-name">' + $name + '</span>';
                    }
                },
                {
                    // Country
                    targets: 3,
                    render: function (data, type, full, meta) {
                        var $city = full['city'];

                        return '<span class="category-city">' + $city + '</span>';
                    }
                },
                {
                    // Created at
                    targets: 4,
                    render: function (data, type, full, meta) {
                        var $created_at = full['created_at'];

                        return '<span class="category-created_at">' + $created_at + '</span>';
                    }
                },
                {
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            '<div class="d-inline-block text-nowrap">' +
                            `<button class="btn btn-sm btn-icon edit-record" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddArea"><i class="ti ti-edit"></i></button>` +
                            `<button class="btn btn-sm btn-icon delete-record" data-id="${full['id']}"><i class="ti ti-trash"></i></button>` +
                            // '<button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>' +
                            // '<div class="dropdown-menu dropdown-menu-end m-0">' +
                            // '<a href="' +
                            // userView +
                            // '" class="dropdown-item">View</a>' +
                            // '<a href="javascript:;" class="dropdown-item">Suspend</a>' +
                            // '</div>' +
                            '</div>'
                        );
                    }
                }
            ],
            order: [2, 'asc'], //set any columns order asc/desc
            dom:
                '<"row mx-2"' +
                '<"col-md-2"<"me-3"l>>' +
                '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
                '>t' +
                '<"row mx-2"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            language: {
                sLengthMenu: '_MENU_',
                search: '',
                searchPlaceholder: 'Search..'
            },
            // Buttons with Dropdown
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle mx-3',
                    text: '<i class="ti ti-logout rotate-n90 me-2"></i>Export',
                    buttons: [
                        {
                            extend: 'print',
                            title: 'Area',
                            text: '<i class="ti ti-printer me-2" ></i>Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4],
                                // prevent avatar to be print
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            // if (item.classList !== undefined && item.classList.contains('user-name')) {
                                            //     result = result + item.lastChild.textContent;
                                            // } else result = result + item.innerText;
                                            if (item && item.classList && item.classList.contains('category-name')) {
                                                result = result + item.lastChild.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            },
                            customize: function (win) {
                                //customize print view for dark
                                $(win.document.body)
                                    .css('color', config.colors.headingColor)
                                    .css('border-color', config.colors.borderColor)
                                    .css('background-color', config.colors.body);
                                $(win.document.body)
                                    .find('table')
                                    .addClass('compact')
                                    .css('color', 'inherit')
                                    .css('border-color', 'inherit')
                                    .css('background-color', 'inherit');
                            }
                        },
                        {
                            extend: 'csv',
                            title: 'Area',
                            text: '<i class="ti ti-file-text me-2" ></i>Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                               columns: [1, 2, 3, 4],
                                // prevent avatar to be print
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            // if (item.classList.contains('user-name')) {
                                            //     result = result + item.lastChild.textContent;
                                            // } else result = result + item.innerText;
                                            if (item && item.classList && item.classList.contains('category-name')) {
                                                result = result + item.lastChild.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                           title: 'Area',
                            text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                              columns: [1, 2, 3, 4],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            // if (item.classList.contains('user-name')) {
                                            //     result = result + item.lastChild.textContent;
                                            // } else result = result + item.innerText;
                                            if (item && item.classList && item.classList.contains('category-name')) {
                                                result = result + item.lastChild.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdf',
                         title: 'Area',
                            text: '<i class="ti ti-file-text me-2"></i>Pdf',
                            className: 'dropdown-item',
                            exportOptions: {
                               columns: [1, 2, 3, 4],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            // if (item.classList.contains('user-name')) {
                                            //     result = result + item.lastChild.textContent;
                                            // } else result = result + item.innerText;
                                            if (item && item.classList && item.classList.contains('category-name')) {
                                                result = result + item.lastChild.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'copy',
                         title: 'Area',
                            text: '<i class="ti ti-copy me-1" ></i>Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                             columns: [1, 2, 3, 4],
                                // prevent avatar to be copy
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            // if (item.classList.contains('user-name')) {
                                            //     result = result + item.lastChild.textContent;
                                            // } else result = result + item.innerText;
                                            if (item && item.classList && item.classList.contains('category-name')) {
                                                result = result + item.lastChild.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        }
                    ]
                },
                {
                    text: '<i class="ti ti-plus me-0 me-sm-1"></i><span class="d-none d-sm-inline-block">Add New Area</span>',
                    className: 'add-new btn btn-primary',
                    attr: {
                        'data-bs-toggle': 'offcanvas',
                        'data-bs-target': '#offcanvasAddArea'
                    }
                }
            ],
            // For responsive popup
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data['name'];
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' +
                                col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td>' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>'
                                : '';
                        }).join('');

                        return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                }
            },
            drawCallback: function (settings) {

                // hide pager and info if the table has NO results
                const api = new $.fn.dataTable.Api(settings);
                const pageCount = api.page.info().pages;

                const wrapper = $('#' + settings.sTableId).closest('.dataTables_wrapper');
                const pagination = wrapper.find('.dataTables_paginate');
                const info = wrapper.find('.dataTables_info');

                pagination.toggle(pageCount > 0);
                info.toggle(pageCount > 0);
            }

        });
        $('.dataTables_length').addClass('mt-2 mt-sm-0 mt-md-3');

        dt_category.on('init', function () {


        });
    }


    // Delete Record
    $(document).on('click', '.delete-record', function () {
        var area_id = $(this).data('id'),
            dtrModal = $('.dtr-bs-modal.show');

        // hide responsive modal in small screen
        if (dtrModal.length) {
            dtrModal.modal('hide');
        }

        // sweetalert for confirmation of delete
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                // delete the data
                $.ajax({
                    type: 'DELETE',
                    url: `${baseUrl}/admin/areas/${area_id}`,
                    success: function () {
                        dt_category.draw();
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });

                // success sweetalert
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'The Area has been deleted!',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'The Area is not deleted!',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            }
        });
    });

    // edit record
    $(document).on('click', '.edit-record', function () {

        var area_id = $(this).data('id');
        console.log(area_id)
        var dtrModal = $('.dtr-bs-modal.show');

        // hide responsive modal in small screen
        if (dtrModal.length) {
            dtrModal.modal('hide');
        }

        // changing the title of offcanvas

        $('#offcanvasAddAreaLabel').html('Edit Area');

        // get data
        $.get(`${baseUrl}/admin/areas\/${area_id}\/edit`, function (data) {
            console.log(data)
            $('#area_id').val(data.id);
            $('#add-area-name-ar').val(data.name.ar);
            $('#add-area-name-en').val(data.name.en);
            $('#city_id').val(data.city_id);
        });
    });

    // changing the title
    $('.add-new').on('click', function () {
        $('#area_id').val(''); //reseting input field
        $('#offcanvasAddAreaLabel').html('Add Area');
    });

    // Filter form control to default size
    // ? setTimeout used for multilingual table initialization
    setTimeout(() => {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);

    // validating form and updating categories data
    const addNewAreaForm = document.getElementById('addNewAreaForm');

    // category form validation
    const fv = FormValidation.formValidation(addNewAreaForm, {

        fields: {
            name_en: {
                validators: {
                    notEmpty: {
                        // message: 'Please enter name (en)'
                        message: validationMessages.data('name-en-required')
                    }
                }
            },
            name_ar: {
                validators: {
                    notEmpty: {
                        // message: 'Please enter name (ar)'
                        message: validationMessages.data('name-ar-required')
                    }
                }
            },
            city_id: {
                validators: {
                    notEmpty: {
                        // message: 'Please choose main category'
                        message: validationMessages.data('city-id-required')
                    }
                }
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                // Use this for enabling/changing valid/invalid class
                eleValidClass: '',
                rowSelector: function (field, ele) {
                    // field is the field name & ele is the field element
                    return '.mb-3';
                }
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            // Submit the form when all fields are valid
            // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        }
    }).on('core.form.valid', function () {
        // adding or updating category when form successfully validate
        $.ajax({
            data: $('#addNewAreaForm').serialize(),
            url: `${baseUrl}/admin/areas`,
            type: 'POST',
            success: function (status) {
                dt_category.draw();
                offCanvasForm.offcanvas('hide');
                $('.select2').val(null).trigger('change');
                // Clear form inputs
                $('#addNewAreaForm').trigger('reset');
                // sweetalert
                Swal.fire({
                    icon: 'success',
                    title: `Successfully ${status}!`,
                    text: `Area ${status} Successfully.`,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            },
            error: function (xhr) {


                if (xhr.status === 422) {
                    // Validation error
                    const errors = xhr.responseJSON.errors;

                    // Display error messages for each field
                    for (const fieldName in errors) {
                        if (errors.hasOwnProperty(fieldName)) {
                            const fieldError = errors[fieldName][0];
                            // You can display the error message next to the field or handle it as needed
                            // For example, you can use jQuery to select the field and display the message
                            $(`[name="${fieldName}"]`).addClass('is-invalid');
                            $(`[name="${fieldName}"]`).siblings('.invalid-feedback').html(fieldError);
                        }
                    }

                } else {
                    // Handle other errors (not validation-related)
                    offCanvasForm.offcanvas('hide');
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while processing your request.',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                }
            }
        });
    });

    // clearing form data when offcanvas hidden
    offCanvasForm.on('hidden.bs.offcanvas', function () {
        fv.resetForm(true);
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').html('');
    });

    const phoneMaskList = document.querySelectorAll('.phone-mask');

    // Phone Number
    if (phoneMaskList) {
        phoneMaskList.forEach(function (phoneMask) {
            new Cleave(phoneMask, {
                phone: true,
                phoneRegionCode: 'US'
            });
        });
    }

    // Add this code inside your JavaScript function


});

