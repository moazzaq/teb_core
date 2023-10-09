@extends('layouts.admin')
@section('title', 'Countries')
@section('admin')

    <div class="container-xxl flex-grow-1 container-p-y">
       <div class="row">
           <div class="row g-4 mb-4">

           </div>
           <!-- Countries List Table -->
           <div class="card">
               <div class="card-header">
                   <h5 class="card-title mb-0">Search Filter</h5>
               </div>
               <div class="card-datatable table-responsive">
                   <table class="datatables-countries table">
                       <thead class="border-top">
                       <tr>
                           <th></th>
                           <th>Id</th>
                           <th>Name</th>
                           <th>Country Key</th>
                           <th>Created At</th>
                           <th>Actions</th>
                       </tr>
                       </thead>
                   </table>
               </div>
               <!-- Offcanvas to add new user -->
               <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCountry" aria-labelledby="offcanvasAddCountryLabel">
                   <div class="offcanvas-header">
                       <h5 id="offcanvasAddCountryLabel" class="offcanvas-title">Add Country</h5>
                       <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                   </div>
                   <div class="offcanvas-body mx-0 flex-grow-0">
                       <form class="add-new-country pt-0" id="addNewCountryForm" enctype="multipart/form-data">
                           <input type="hidden" name="id" id="country_id">
                           <div class="mb-3">
                               <label class="form-label" for="add-country-name">Name (ar)</label>
                               <input type="text" class="form-control" id="add-country-name-ar" placeholder="Country Name (ar)" name="name_ar" aria-label="Country Name (ar)" />
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="add-country-name">Name (en)</label>
                               <input type="text" class="form-control" id="add-country-name-en" placeholder="Country Name (en)" name="name_en" aria-label="Country Name (en)" />
                           </div>
{{--                           <div class="mb-3">--}}
{{--                               <label class="form-label" for="add-country-image">Image</label>--}}
{{--                               <input type="file" class="form-control" id="add-country-image" name="image" aria-label="Image" />--}}
{{--                           </div>--}}
                           <div class="mb-3">
                               <label class="form-label" for="add-country-key">Country key</label>
                               <input type="text" class="form-control" id="add-country-key" name="country_key" aria-label="Country key" />
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
    <script src="{{asset('backend/datatables/js/country-management.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        var csrf = "{{csrf_token()}}";
        var DATA_URL = "{{ route('admin.countries.api') }}";
        var baseUrl = '{{URL::to('')}}';
    </script>

    <script>

    </script>
@endpush
