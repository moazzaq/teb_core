@extends('layouts.admin')
@section('title', 'Sub-Categories')
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

                        <div class="col-md-4 product_category">
{{--                            <select class="form-select text-capitalize" name="filter_1"--}}
{{--                                    id="filter_1">--}}
{{--                                <option value="">Category</option>--}}
{{--                                @foreach($parent_categories as $category)--}}
{{--                                    <option value="{{$category->id}}">{{$category->name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                        </div>
                        <div class="col-md-2 product_status">
                            <button type="button" class="btn btn-primary" name="filter"
                                    id="filterByCategoryBtn">
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
                <table class="datatables-sub-categories table">
                    <thead class="border-top">
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>{{__('cp.name')}}</th>
                        <th>{{__('cp.slug')}}</th>
                        <th>{{__('cp.maincategory')}}</th>
                        <th>{{__('cp.created')}}</th>
                        <th>{{__('cp.action')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddSubCategory"
                 aria-labelledby="offcanvasAddSubCategoryLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddSubCategoryLabel" class="offcanvas-title">Add Sub Category</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0">
                    <form class="add-new-sub-category pt-0" id="addNewSubCategoryForm">
                        <input type="hidden" name="id" id="category_id">
                        <div class="mb-3">
                            <label class="form-label" for="add-category-name">{{__('cp.name_ar')}}</label>
                            <input type="text" class="form-control" id="add-category-name-ar"
                                   placeholder="{{__('cp.name_ar')}}" name="name_ar" aria-label="{{__('cp.name_ar')}}"/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-category-name">{{__('cp.name_en')}}</label>
                            <input type="text" class="form-control" id="add-category-name-en"
                                   placeholder="{{__('cp.name_en')}}" name="name_en" aria-label="{{__('cp.name_en')}}"/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-category-slug">{{__('cp.slug')}}</label>
                            <input type="text" class="form-control" disabled id="add-category-slug"
                                   placeholder="{{__('cp.slug')}}" name="slug" aria-label="{{__('cp.slug')}}"/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="parent_id">{{__('cp.maincategory')}}</label>
                            <select id="parent_id" name="parent_id" class="select2 form-select">
                                <option value="">{{__('cp.choose')}}</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
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
    <div id="list_categories" data-categories="{{json_encode($parent_categories)}}"></div>
    <div id="validation-messages" style="display: none;"
        data-add-new="{{ trans('cp.add_sub_category') }}"
        data-edit="{{ trans('cp.edit') }}"
        data-name-en-required="{{ trans('cp.name_en_required') }}"
        data-name-ar-required="{{ trans('cp.name_ar_required') }}"
        data-parent-id-required="{{ trans('cp.parent_id_required') }}"
        data-country-id-required="{{ trans('cp.parent_id_required') }}"
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
    <script src="{{asset('backend/datatables/js/sub-category-management.js')}}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        var csrf = "{{csrf_token()}}";
        var DATA_URL = "{{ route('admin.sub_categories.api') }}";
        var baseUrl = '{{URL::to('')}}';
        var filter_1 = -1;

    </script>

    <script>
        // Get references to the name and slug input elements
        const nameInput = document.getElementById('add-category-name-en');
        const slugInput = document.getElementById('add-category-slug');

        nameInput.addEventListener('input', function () {
            // Get the value of the name input
            const nameValue = nameInput.value.trim();

            // Replace spaces with hyphens and convert to lowercase to create the slug
            const slugValue = nameValue.replace(/\s+/g, '-').toLowerCase();

            // Update the slug input with the generated slug
            slugInput.value = slugValue;
        });

    </script>
@endpush
