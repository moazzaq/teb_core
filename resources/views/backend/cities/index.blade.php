@extends('layouts.admin')
@section('title', __('cp.cities'))
@push('style')



@endpush
@section('admin')

    <div class="container-xxl flex-grow-1 container-p-y">
    {{--        <h4 class="py-3 mb-4"><span class="text-muted fw-light">eCommerce /</span> Product List</h4>--}}

    <!-- Product List Widget -->

        <div class="card mb-4">

        </div>

        <!-- Product List Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{__('cp.filter')}}</h5>
                {{--                <form>--}}
                <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">

                    <div class="col-md-4 filter_country">

                    </div>
                    <div class="col-md-2 product_status">
                        <button type="button" class="btn btn-primary" name="filter"
                                id="filterByCountryBtn">
                                        <span>
                                            <i class="la la-search"></i>
                                            <span>{{__('cp.filter')}}</span>
                                        </span>
                        </button>
                    </div>

                </div>
                {{--                </form>--}}
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-cities table">
                    <thead class="border-top">
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>{{__('cp.name')}}</th>
                        <th>{{__('cp.country')}}</th>
                        <th>{{__('cp.created')}}</th>
                        <th>{{__('cp.action')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCity"
                 aria-labelledby="offcanvasAddCityLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddCityLabel" class="offcanvas-title">{{__('cp.add_cities')}}</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0">
                    <form class="add-new-city pt-0" id="addNewCityForm">
                        <input type="hidden" name="id" id="city_id">
                        <div class="mb-3">
                            <label class="form-label" for="add-city-name">{{__('cp.name_ar')}}</label>
                            <input type="text" class="form-control" id="add-city-name-ar"
                                   placeholder="{{__('cp.name_ar')}}" name="name_ar" aria-label="{{__('cp.name_ar')}}"/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-city-name">{{__('cp.name_en')}}</label>
                            <input type="text" class="form-control" id="add-city-name-en"
                                   placeholder="{{__('cp.name_en')}}" name="name_en" aria-label="{{__('cp.name_en')}}"/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="country_id">{{__('cp.country')}}</label>
                            <select id="country_id" name="country_id" class="select2 form-select">
                                <option value="">{{__('cp.select')}}</option>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">{{__('cp.save')}}</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">{{__('cp.cancel')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="list_countries" data-countries="{{json_encode($countries)}}"></div>
    <div id="validation-messages" style="display: none;"
         data-add-new="{{ trans('cp.add_city') }}"
         data-edit="{{ trans('cp.edit') }}"
         data-name-en-required="{{ trans('cp.name_en_required') }}"
         data-name-ar-required="{{ trans('cp.name_ar_required') }}"
         data-country-id-required="{{ trans('cp.country_id_required') }}"
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
    <script src="{{asset('backend/datatables/js/city-management.js')}}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        var csrf = "{{csrf_token()}}";
        var DATA_URL = "{{ route('admin.cities.api') }}";
        var baseUrl = '{{URL::to('')}}';
        var filter_1 = -1;

    </script>

    <script>

    </script>
@endpush
