@extends('layouts.admin')
@section('title', __('cp.countries'))
@section('admin')

    <div class="container-xxl flex-grow-1 container-p-y">
       <div class="row">
           <div class="row g-4 mb-4">

           </div>
           <!-- Countries List Table -->
           <div class="card">
               <div class="card-header">
{{--                   <h5 class="card-title mb-0">Search Filter</h5>--}}
               </div>
               <div class="card-datatable table-responsive">
                   <table class="datatables-countries table">
                       <thead class="border-top">
                       <tr>
                           <th></th>
                           <th>#</th>
                           <th>{{__('cp.name')}}</th>
                           <th>{{__('cp.country_key')}}</th>
                           <th>{{__('cp.created')}}</th>
                           <th>{{__('cp.action')}}</th>
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
                               <label class="form-label" for="add-country-name">{{__('cp.name_ar')}}</label>
                               <input type="text" class="form-control" id="add-country-name-ar" placeholder="{{__('cp.name_ar')}}" name="name_ar" aria-label="{{__('cp.name_ar')}}" />
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="add-country-name">{{__('cp.name_en')}}</label>
                               <input type="text" class="form-control" id="add-country-name-en" placeholder="{{__('cp.name_en')}}" name="name_en" aria-label="{{__('cp.name_en')}}" />
                           </div>
{{--                           <div class="mb-3">--}}
{{--                               <label class="form-label" for="add-country-image">Image</label>--}}
{{--                               <input type="file" class="form-control" id="add-country-image" name="image" aria-label="Image" />--}}
{{--                           </div>--}}
                           <div class="mb-3">
                               <label class="form-label" for="add-country-key">{{__('cp.country_key')}}</label>
                               <input type="text" class="form-control" id="add-country-key" name="country_key" aria-label="{{__('cp.country_key')}}" placeholder="{{__('cp.country_key')}}" />
                           </div>

                           <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">{{__('cp.save')}}</button>
                           <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">{{__('cp.cancel')}}</button>
                       </form>
                   </div>
               </div>
           </div>
       </div>
    </div>
    <div id="validation-messages" style="display: none;"
         data-add-new="{{ trans('cp.add_country') }}"
         data-edit="{{ trans('cp.edit') }}"
         data-name-en-required="{{ trans('cp.name_en_required') }}"
         data-name-ar-required="{{ trans('cp.name_ar_required') }}"
         data-parent-id-required="{{ trans('cp.parent_id_required') }}"
         data-country-key-required="{{ trans('cp.country_key_required') }}"
         data-export="{{ trans('cp.export') }}"
         data-select="{{ trans('cp.select') }}"
         data-confirm="{{ trans('cp.confirm') }}"
         data-delete="{{ trans('cp.delete') }}"
         data-cancel="{{ trans('cp.cancel') }}"
         data-search="{{ trans('cp.search') }}"
         data-next="{{ trans('cp.next') }}"
         data-previous="{{ trans('cp.previous') }}"
         data-showing="{{ trans('cp.showing') }}"
         data-to="{{ trans('cp.to') }}"
         data-of="{{ trans('cp.of') }}"
         data-entries="{{ trans('cp.entries') }}"
         data-actions="{{ trans('cp.action') }}"
         data-lang="{{ app()->getLocale() }}"
         data-oky="{{ trans('cp.oky') }}"
         data-delete_done="{{ trans('cp.delete_done') }}">
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
