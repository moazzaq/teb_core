@extends('layouts.admin')
@section('title', 'Users')
{{--@section('title',"$page_title")--}}
@section('admin')

    <div class="container-xxl flex-grow-1 container-p-y">
       <div class="row">
           <div class="row g-4 mb-4">
               <div class="col-sm-6 col-xl-3">
                   <div class="card">
                       <div class="card-body">
                           <div class="d-flex align-items-start justify-content-between">
                               <div class="content-left">
                                   <span>Users</span>
                                   <div class="d-flex align-items-end mt-2">
                                       <h3 class="mb-0 me-2">100</h3>
                                       <small class="text-success">(100%)</small>
                                   </div>
                                   <small>Total Users</small>
                               </div>
                               <span class="badge bg-label-primary rounded p-2">
            <i class="ti ti-user ti-sm"></i>
          </span>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="col-sm-6 col-xl-3">
                   <div class="card">
                       <div class="card-body">
                           <div class="d-flex align-items-start justify-content-between">
                               <div class="content-left">
                                   <span>Verified Users</span>
                                   <div class="d-flex align-items-end mt-2">
                                       <h3 class="mb-0 me-2">10</h3>
                                       <small class="text-success">(+95%)</small>
                                   </div>
                                   <small>Recent analytics </small>
                               </div>
                               <span class="badge bg-label-success rounded p-2">
            <i class="ti ti-user-check ti-sm"></i>
          </span>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="col-sm-6 col-xl-3">
                   <div class="card">
                       <div class="card-body">
                           <div class="d-flex align-items-start justify-content-between">
                               <div class="content-left">
                                   <span>Duplicate Users</span>
                                   <div class="d-flex align-items-end mt-2">
                                       <h3 class="mb-0 me-2">20</h3>
                                       <small class="text-success">(0%)</small>
                                   </div>
                                   <small>Recent analytics</small>
                               </div>
                               <span class="badge bg-label-danger rounded p-2">
            <i class="ti ti-users ti-sm"></i>
          </span>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="col-sm-6 col-xl-3">
                   <div class="card">
                       <div class="card-body">
                           <div class="d-flex align-items-start justify-content-between">
                               <div class="content-left">
                                   <span>Verification Pending</span>
                                   <div class="d-flex align-items-end mt-2">
                                       <h3 class="mb-0 me-2">50</h3>
                                       <small class="text-danger">(+6%)</small>
                                   </div>
                                   <small>Recent analytics</small>
                               </div>
                               <span class="badge bg-label-warning rounded p-2">
            <i class="ti ti-user-circle ti-sm"></i>
          </span>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
           <!-- Users List Table -->
           <div class="card">
               <div class="card-header">
                   <h5 class="card-title mb-0">Search Filter</h5>
               </div>
               <div class="card-datatable table-responsive">
                   <table class="datatables-users table">
                       <thead class="border-top">
                       <tr>
                           <th></th>
                           <th>Id</th>
                           <th>Username</th>
                           <th>Name</th>
                           <th>Last Name</th>
                           <th>Email</th>
                           <th>phone</th>
                           <th>Actions</th>
                       </tr>
                       </thead>
                   </table>
               </div>
               <!-- Offcanvas to add new user -->
               <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
                   <div class="offcanvas-header">
                       <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
                       <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                   </div>
                   <div class="offcanvas-body mx-0 flex-grow-0">
                       <form class="add-new-user pt-0" id="addNewUserForm">
                           <input type="hidden" name="id" id="user_id">
                           <div class="mb-3">
                               <label class="form-label" for="add-user-fullname">First Name</label>
                               <input type="text" class="form-control" id="add-user-firstname" placeholder="First Name" name="first_name" aria-label="First Name" />
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="add-user-fullname">Last Name</label>
                               <input type="text" class="form-control" id="add-user-lastname" placeholder="Last Name" name="last_name" aria-label="Last Name" />
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="add-user-username">Username</label>
                               <input type="text" class="form-control" id="add-user-username" placeholder="Username" name="username" aria-label="Username" />
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="add-user-email">Email</label>
                               <input type="text" id="add-user-email" class="form-control" placeholder="john.doe@example.com" aria-label="john.doe@example.com" name="email" />
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="add-user-contact">Phone</label>
                               <input type="text" id="add-user-contact" class="form-control phone-mask" placeholder="+1 (609) 988-44-11" aria-label="+1 (609) 988-44-11" name="phone" />
                           </div>
{{--                           <div class="mb-3">--}}
{{--                               <label class="form-label" for="add-user-company">Company</label>--}}
{{--                               <input type="text" id="add-user-company" name="company" class="form-control" placeholder="Web Developer" aria-label="jdoe1" />--}}
{{--                           </div>--}}

{{--                           <div class="mb-3">--}}
{{--                               <label class="form-label" for="user-role">User Role</label>--}}
{{--                               <select id="user-role" class="form-select">--}}
{{--                                   <option value="subscriber">Subscriber</option>--}}
{{--                                   <option value="editor">Editor</option>--}}
{{--                                   <option value="maintainer">Maintainer</option>--}}
{{--                                   <option value="author">Author</option>--}}
{{--                                   <option value="admin">Admin</option>--}}
{{--                               </select>--}}
{{--                           </div>--}}

                           <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                           <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                       </form>
                   </div>
               </div>
           </div>
       </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('backend/datatables/js/user-management.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        var csrf = "{{csrf_token()}}";
        var DATA_URL = "{{ route('admin.users.api') }}";
        var baseUrl = '{{URL::to('')}}';
    </script>
    @endpush
