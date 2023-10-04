@extends('layouts.admin')
@section('title', 'Users')
{{--@section('title',"$page_title")--}}
@section('admin')

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"
                        ><i class="ti-xs ti ti-users me-1"></i> Account</a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('admin.users.profile.edit-password')}}"
                        ><i class="ti-xs ti ti-lock me-1"></i> Security</a
                        >
                    </li>
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link" href="pages-account-settings-notifications.html"--}}
{{--                        ><i class="ti-xs ti ti-bell me-1"></i> Notifications</a--}}
{{--                        >--}}
{{--                    </li>--}}
                </ul>
                <div class="card mb-4">
                    <h5 class="card-header">Profile Details</h5>
                    <!-- Account -->
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img
                                src="{{ $user->user_avatar }}"
                                alt="user-avatar"
                                class="d-block w-px-100 h-px-100 rounded"
                                id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="ti ti-upload d-block d-sm-none"></i>
                                    <input
                                        type="file"
                                        id="upload"
                                        name="avatar"
                                        class="account-file-input"
                                        hidden
                                        accept="image/png, image/jpeg" />
                                </label>
                                <button type="button" class="btn btn-label-secondary account-image-reset mb-3">
                                    <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>

                                <div class="text-muted"></div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                        <form id="formAccountSettings" method="POST" action="{{ route('admin.users.profile.update') }}">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        id="firstName"
                                        name="first_name"
                                        value="{{auth()->user()->first_name}}"
                                        autofocus />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input class="form-control" type="text" name="last_name" id="lastName" value="{{auth()->user()->last_name}}" />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="username" class="form-label">Username</label>
                                    <input class="form-control" type="text" name="username" id="username" value="{{auth()->user()->username}}" />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        id="email"
                                        name="email"
                                        value="{{auth()->user()->email}}"
                                        placeholder="john.doe@example.com" />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="phoneNumber">Phone Number</label>
                                    <div class="input-group input-group-merge">
{{--                                        <span class="input-group-text">US (+1)</span>--}}
                                        <input
                                            type="text"
                                            id="phoneNumber"
                                            name="phone"
                                            value="{{auth()->user()->phone}}"
                                            class="form-control"
                                            placeholder="202 555 0111" />
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                <button type="reset" class="btn btn-label-secondary">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <!-- /Account -->
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script src="{{asset('backend')}}/js/pages-account-settings-account.js"></script>
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

                // var formData = $(this).serialize();
                var formData = new FormData(this);
                var avatarFileInput = $('#upload')[0].files[0];
                console.log(avatarFileInput)
                if (avatarFileInput) {
                    formData.append('avatar', avatarFileInput);
                }
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log(response.user)
                        var userAvatarCircleElement = $('#avatar-image-circle');
                        var userAvatarElement = $('#avatar-image');
                        var changeEmail = $('#change-email');
                        var changeUsername = $('#change-username');
                        userAvatarCircleElement.attr('src', response.user.user_avatar);
                        userAvatarElement.attr('src', response.user.user_avatar);
                        changeEmail.html(response.user.email);
                        changeUsername.html(response.user.username);
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
