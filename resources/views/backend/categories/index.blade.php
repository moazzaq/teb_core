@extends('layouts.admin')
@section('title', 'Categories')
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
                   <table class="datatables-categories table">
                       <thead class="border-top">
                       <tr>
                           <th></th>
                           <th>Id</th>
                           <th>Name</th>
                           <th>Slug</th>
                           <th>Created At</th>
                           <th>Actions</th>
                       </tr>
                       </thead>
                   </table>
               </div>
               <!-- Offcanvas to add new user -->
               <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCategory" aria-labelledby="offcanvasAddCategoryLabel">
                   <div class="offcanvas-header">
                       <h5 id="offcanvasAddCategoryLabel" class="offcanvas-title">Add Category</h5>
                       <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                   </div>
                   <div class="offcanvas-body mx-0 flex-grow-0">
                       <form class="add-new-category pt-0" id="addNewCategoryForm" enctype="multipart/form-data">
                           <input type="hidden" name="id" id="category_id">
                           <div class="mb-3">
                               <label class="form-label" for="add-category-name">Name (ar)</label>
                               <input type="text" class="form-control" id="add-category-name-ar" placeholder="Category Name (ar)" name="name_ar" aria-label="Category Name (ar)" />
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="add-category-name">Name (en)</label>
                               <input type="text" class="form-control" id="add-category-name-en" placeholder="Category Name (en)" name="name_en" aria-label="Category Name (en)" />
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="add-category-slug">Slug</label>
                               <input type="text" class="form-control" disabled id="add-category-slug" placeholder="Category Slug" name="slug" aria-label="Category Slug" />
                           </div>
{{--                           <div class="mb-3">--}}
{{--                               <label class="form-label" for="add-category-image">Image</label>--}}
{{--                               <input type="file" class="form-control" id="add-category-image" name="image" aria-label="Image" />--}}
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
    <script src="{{asset('backend/datatables/js/category-management.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        var csrf = "{{csrf_token()}}";
        var DATA_URL = "{{ route('admin.categories.api') }}";
        var baseUrl = '{{URL::to('')}}';
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
