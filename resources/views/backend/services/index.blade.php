@extends('layouts.admin')
@section('title', __('cp.services'))
@section('admin')

    <div class="container-xxl flex-grow-1 container-p-y">
       <div class="row">
           <div class="row g-4 mb-4">

           </div>
           <!-- Categories List Table -->
           <div class="card">
               <div class="card-header">
                   <h5 class="card-title mb-0">Search Filter</h5>
               </div>
               <div class="card-datatable table-responsive">
                   <table class="datatables-services table">
                       <thead class="border-top">
                       <tr>
                           <th></th>
                           <th>Id</th>
                           <th>Name</th>
                           <th>Created At</th>
                           <th>Actions</th>
                       </tr>
                       </thead>
                   </table>
               </div>
               <!-- Offcanvas to add new user -->
               <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddService" aria-labelledby="offcanvasAddServiceLabel">
                   <div class="offcanvas-header">
                       <h5 id="offcanvasAddServiceLabel" class="offcanvas-title">Add Service</h5>
                       <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                   </div>
                   <div class="offcanvas-body mx-0 flex-grow-0">
                       <form class="add-new-service pt-0" id="addNewServiceForm" enctype="multipart/form-data">
                           <input type="hidden" name="id" id="service_id">
                           <div class="mb-3">
                               <label class="form-label" for="add-service-name">Name (ar)</label>
                               <input type="text" class="form-control" id="add-service-name-ar" placeholder="Service Name (ar)" name="name_ar" aria-label="Service Name (ar)" />
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="add-service-name">Name (en)</label>
                               <input type="text" class="form-control" id="add-service-name-en" placeholder="Service Name (en)" name="name_en" aria-label="Service Name (en)" />
                           </div>

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
    <script src="{{asset('backend/datatables/js/service-management.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        var csrf = "{{csrf_token()}}";
        var DATA_URL = "{{ route('admin.services.api') }}";
        var baseUrl = '{{URL::to('')}}';
    </script>

    <script>

    </script>
@endpush
