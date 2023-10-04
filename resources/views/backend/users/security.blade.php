@extends('layouts.admin')
@section('title', 'Users')
{{--@section('title',"$page_title")--}}
@section('admin')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Security</h4>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.users.profile.edit')}}"
                        ><i class="ti-xs ti ti-users me-1"></i> Account</a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"
                        ><i class="ti-xs ti ti-lock me-1"></i> Security</a
                        >
                    </li>
                </ul>
                <!-- Change Password -->
                <div class="card mb-4">
                    <h5 class="card-header">Change Password</h5>
                    <div class="card-body">
                        <form id="formAccountSettings" method="POST"
                              action="{{route('admin.users.profile.update-password')}}">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6 form-password-toggle">
                                    <label class="form-label" for="currentPassword">Current Password</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            class="form-control"
                                            type="password"
                                            name="currentPassword"
                                            id="currentPassword"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                                        <span class="input-group-text cursor-pointer"><i
                                                class="ti ti-eye-off"></i></span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6 form-password-toggle">
                                    <label class="form-label" for="newPassword">New Password</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            class="form-control"
                                            type="password"
                                            id="newPassword"
                                            name="newPassword"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                                        <span class="input-group-text cursor-pointer"><i
                                                class="ti ti-eye-off"></i></span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="mb-3 col-md-6 form-password-toggle">
                                    <label class="form-label" for="confirmPassword">Confirm New Password</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            class="form-control"
                                            type="password"
                                            name="newPassword_confirmation"
                                            id="confirmPassword"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                                        <span class="input-group-text cursor-pointer"><i
                                                class="ti ti-eye-off"></i></span>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    {{--                                    <h6>Password Requirements:</h6>--}}
                                    {{--                                    <ul class="ps-3 mb-0">--}}
                                    {{--                                        <li class="mb-1">Minimum 8 characters long - the more, the better</li>--}}
                                    {{--                                        <li class="mb-1">At least one lowercase character</li>--}}
                                    {{--                                        <li>At least one number, symbol, or whitespace character</li>--}}
                                    {{--                                    </ul>--}}
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                    <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--/ Change Password -->
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset('backend')}}/vendor/css/pages/page-account-settings.css"/>
@endpush
@push('js')
    {{--    <script src="{{asset('backend')}}/js/pages-account-settings-security.js"></script>--}}
    <script>
        $(document).ready(function () {
            const addNewProductForm = document.getElementById('formAccountSettings');
            addNewProductForm.addEventListener('click', function (event) {
                const target = event.target;

                if (target.tagName === 'INPUT' && target.classList.contains('is-invalid')) {
                    target.classList.remove('is-invalid');

                    const feedbackElement = target.nextElementSibling;
                    if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                        feedbackElement.textContent = '';
                    }
                }
            });
            $('#formAccountSettings').on('submit', function (e) {

                e.preventDefault();

                var formData = $(this).serialize();


                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,

                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: `Successfully ${response.message}!`,
                            text: `Product ${response.message} Successfully.`,
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    },
                    error: function (xhr) {

                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function (field, errorMessage) {
                                var inputField = $('[name="' + field + '"]');
                                inputField.addClass('is-invalid');
                                inputField.siblings('.invalid-feedback').html(errorMessage[0]);
                            });
                        } else {
                            // Handle other types of errors
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
        });
    </script>
@endpush
