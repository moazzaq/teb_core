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
                <h5 class="card-title mb-0">Filter</h5>
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
                                            <span>Filter</span>
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
                        <th>Id</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Main Category</th>
                        <th>Created At</th>
                        <th>Actions</th>
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
                            <label class="form-label" for="add-category-name">Name (ar)</label>
                            <input type="text" class="form-control" id="add-category-name-ar"
                                   placeholder="Category Name (ar)" name="name_ar" aria-label="Category Name (ar)"/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-category-name">Name (en)</label>
                            <input type="text" class="form-control" id="add-category-name-en"
                                   placeholder="Category Name (en)" name="name_en" aria-label="Category Name (en)"/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-category-slug">Slug</label>
                            <input type="text" class="form-control" disabled id="add-category-slug"
                                   placeholder="Category Slug" name="slug" aria-label="Category Slug"/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="parent_id">Main Category</label>
                            <select id="parent_id" name="parent_id" class="select2 form-select">
                                <option value="">Select</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="list_categories" data-categories="{{json_encode($parent_categories)}}"></div>
    <div id="validation-messages" style="display: none;"
         data-name-en-required="{{ trans('cp.name_en_required') }}"
         data-name-ar-required="{{ trans('cp.name_ar_required') }}"
         data-parent-id-required="{{ trans('cp.parent_id_required') }}">
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
