@extends('layouts.admin')
@section('title', __('cp.cases'))
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
                   <table class="datatables-cases table">
                       <thead class="border-top">
                       <tr>
                           <th></th>
                           <th>Id</th>
                           <th>Name</th>
                           <th>Category</th>
                           <th>Sub-Category</th>
                           <th>Created At</th>
                           <th>Actions</th>
                       </tr>
                       </thead>
                   </table>
               </div>
               <!-- Offcanvas to add new user -->
               <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCases" aria-labelledby="offcanvasAddCasesLabel">
                   <div class="offcanvas-header">
                       <h5 id="offcanvasAddCasesLabel" class="offcanvas-title">Add Cases</h5>
                       <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                   </div>
                   <div class="offcanvas-body mx-0 flex-grow-0">
                       <form class="add-new-cases pt-0" id="addNewCasesForm" enctype="multipart/form-data">
                           <input type="hidden" name="id" id="case_id">
                           <div class="mb-3">
                               <label class="form-label" for="add-case-name">Name (ar)</label>
                               <input type="text" class="form-control" id="add-case-name-ar" placeholder="Case Name (ar)" name="name_ar" aria-label="Case Name (ar)" />
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="add-case-name">Name (en)</label>
                               <input type="text" class="form-control" id="add-case-name-en" placeholder="Case Name (en)" name="name_en" aria-label="Case Name (en)" />
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="category_id">Main Category</label>
                               <select id="category_id" name="category_id" class="select2 form-select">
                                   <option value="">Select</option>
                                   @foreach($parent_categories as $category)
                                       <option value="{{$category->id}}">{{$category->name}}</option>
                                   @endforeach
                               </select>
                           </div>
                           <div class="mb-3">
                               <label class="form-label" for="child_category_id">Sub Category</label>
                               <select id="child_category_id" name="child_category_id" class="select2_sub form-select">
                                   <option value="">Select</option>
{{--                                   @foreach($child_categories as $sub)--}}
{{--                                       <option value="{{$sub->id}}">{{$sub->name}}</option>--}}
{{--                                   @endforeach--}}
                               </select>
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
    <script src="{{asset('backend/datatables/js/cases-management.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        var csrf = "{{csrf_token()}}";
        var DATA_URL = "{{ route('admin.cases.api') }}";
        var baseUrl = '{{URL::to('')}}';
    </script>

    <script>
        $(document).ready(function () {

            $('#category_id').on('change', function () {
                var getCategoryId = $(this).val();
                if (getCategoryId) {
                    $.ajax({
                        url: '/admin/get-sub-category-by-category/' + getCategoryId,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            if (data) {

                                $('#child_category_id').empty();
                                $('#child_category_id').focus;

                                $('#child_category_id').append('<option value="">Select</option>');
                                $.each(data, function (key, value) {
                                    $('#child_category_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                                });
                            } else {
                                $('#child_category_id').empty();
                            }
                        }
                    });
                } else {
                    $('#child_category_id').empty();
                }
            });

            $(document).on('click', '.edit-record', function () {
                var case_id = $(this).data('id'),
                    dtrModal = $('.dtr-bs-modal.show');

                // hide responsive modal in small screen
                if (dtrModal.length) {
                    dtrModal.modal('hide');
                }

                // changing the title of offcanvas
                $('#offcanvasAddCasesLabel').html('Edit Case');

                // get data
                $.get(`${baseUrl}/admin/cases\/${case_id}\/edit`, function (data) {
                    $('#case_id').val(data.id);
                    $('#add-case-name-ar').val(data.name.ar);
                    $('#add-case-name-en').val(data.name.en);
                    $('#category_id').val(data.category_id);
                    $('#child_category_id').val(data.child_category_id);


                        var getCategoryId = data.category_id;
                        if (getCategoryId) {
                            $.ajax({
                                url: '/admin/get-sub-category-by-category/' + getCategoryId,
                                type: "GET",
                                dataType: "json",
                                success: function (subCategoryData) {
                                    if (subCategoryData) {
                                        var childCategoryDropdown = $('#child_category_id');
                                        childCategoryDropdown.empty();
                                        childCategoryDropdown.focus();

                                        childCategoryDropdown.append('<option value="">Select</option>');
                                        $.each(subCategoryData, function (key, value) {
                                            childCategoryDropdown.append('<option value="' + value.id + '">' + value.name + '</option>');
                                        });

                                        // Set the selected value in child_category_id dropdown
                                        childCategoryDropdown.val(data.child_category_id);
                                    } else {
                                        $('#child_category_id').empty();
                                    }
                                }
                            });
                        } else {
                            $('#child_category_id').empty();
                        }
                });
            });

        });




    </script>
@endpush
