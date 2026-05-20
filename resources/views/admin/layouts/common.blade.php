{{-- <div class="modal fade drawer right-align" id="{{ $modal_id }}" tabindex="-1" aria-labelledby="{{ $modal_id }}Label" aria-hidden="true" data-bs-keyboard="true">
    <div class="modal-dialog {{ $formConfig['modal_size'] ?? '' }}">
        <div class="modal-content">                        
            <form action="{{ $formConfig['action'] }}" method="post" class="ajax-form" enctype="multipart/form-data" id="{{ $form_id }}">
                @csrf                
                
                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body py-3">
                    <input type="hidden" name="_method" id="{{ $method_id }}" value="POST" class="form-control">
                    
                    <div class="row">
                        @foreach($formConfig['fields'] as $field)                        
                            <div class="{{ $field['col'] ?? 'col-md-12' }}">
                                <div class="form-group">
                                    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>                                    

                                    @if($field['type'] == 'text')                                                                            
                                        <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" id="{{ $field['id'] ?? '' }}" value="{{ old($field['name']) }}" class="form-control {{ $field['animate_label'] ?? '' }} {{ $field['class'] ?? '' }}" 
                                            @if(isset($field['data']))
                                                @foreach($field['data'] as $key => $value)
                                                    data-{{ $key }}="{{ $value }}"
                                                @endforeach
                                            @endif 
                                        >                                                                            

                                    @elseif($field['type'] == 'email')                                                                                    
                                        <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                            

                                    @elseif($field['type'] == 'textarea')
                                        <textarea name="{{ $field['name'] }}" class="form-control {{ $field['summer_class'] }}" rows="4"></textarea>                                                                                
                                        
                                    @elseif($field['type'] == 'color')                                        
                                        <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                            

                                    @elseif($field['type'] == 'date')                                        
                                        <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                            

                                    @elseif($field['type'] == 'file')                                        
                                        <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                        

                                    @elseif($field['type'] == 'select')                                                                                
                                        <select name="{{ $field['name'] }}" class="form-select" id="{{ $field['name'] }}">
                                            @foreach($field['options'] as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>                                                    
                                        
                                    @elseif($field['type'] == 'category')                                                                                
                                        <select name="sub_category_id" id="sub_category" class="form-select" >
                                            <option value="">Sub Category</option>
                                        </select>    

                                    @elseif($field['type'] == 'dropzone')
                                        <input type="hidden" id="{{ $field['name'] }}_id" name="{{ $field['name'] }}_id" value=" ">                                        
                                        <div id="{{ $field['name'] }}" data-input="{{ $field['name'] }}_id" class="dropzone custom-dropzone dz-clickable">
                                            <div class="dz-message needsclick">
                                                <br>Drop files here or click to upload.<br><br>
                                            </div>
                                        </div>                                       
                                    @endif
                                </div>
                            </div>
                        @endforeach                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        {{ $formConfig['button'] }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('customJs')
<script>
    $(document).on('submit', '.ajax-form', function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = new FormData(this);

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Close modal
                let modal = form.closest('.modal');
                let modalInstance = bootstrap.Modal.getInstance(modal[0]);
                modalInstance.hide();

                // Optional: Reset form
                form[0].reset();

                // Show success alert
                $('#alert-area').html(`
                    <div class="alert alert-success alert-dismissible fade show">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);

                // Auto remove after 3 seconds
                setTimeout(function(){
                    $('.alert').fadeOut();
                }, 3000);

                // Reload page OR append row dynamically
                location.reload();
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });

    $(document).on('input', '.slug-source', function () {
        let element = $(this);
        let form = element.closest('form');
        let target = element.data('target');
        let submitBtn = form.find("button[type=submit]");

        submitBtn.prop('disabled', true);

        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'GET',
            data: { title: element.val() },
            dataType: 'json',
            success: function (response) {

                submitBtn.prop('disabled', false);

                if (response.status) {
                    form.find(target).val(response.slug);
                }
            }
        });
    });

    Dropzone.autoDiscover = false;
    document.querySelectorAll('.custom-dropzone').forEach(function (el) {
        let inputId = el.getAttribute('data-input');
        new Dropzone(el, { 
            url: "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            success: function(file, response){
                document.getElementById(inputId).value = response.image_id;
            }
        });
    });

   

    // //Store
    // const store_branch        = "{{ route('branch.store') }}";    

    // //Update    
    // const update_branch       = "{{ url('admin/category') }}";    

    // function createCategoryModal() {
    //     document.querySelector('#categoryModal .modal-title').innerText = 'Create Category';
    //     let form = document.getElementById('categoryForm');
    //     form.reset();
    //     form.action = store_category;
    //     document.getElementById('form_method').value = 'POST';
    //     document.getElementById('form_submit_btn').innerText = 'Create Category';
    // }

    // function createSubCategoryModal() {
    //     let form = document.getElementById('subCategoryForm');
    //     form.reset();
    //     form.action = store_subcategory;
    //     document.getElementById('form_method').value = 'POST';
    //     document.getElementById('form_submit_btn').innerText = 'Create Sub Category';
    // }

    // function createSubSubCategoryModal() {
    //     let form = document.getElementById('subSubCategoryForm');
    //     form.reset();
    //     form.action = store_subsubcategory;
    //     document.getElementById('form_method').value = 'POST';
    //     document.getElementById('form_submit_btn').innerText = 'Create Sub Sub Category';
    // }

    // function editCategoryModal(button) {
    //     let id = button.dataset.id;
    //     let category_name = button.dataset.category_name;
    //     let status = button.dataset.status;
    //     let showHome = button.dataset.showHome;
    //     let menu_order = button.dataset.menu_order;

    //     document.querySelector('#categoryModal .modal-title').innerText = 'Edit Category';
    //     let form = document.getElementById('categoryForm');

    //     // Set action
    //     form.action = `${update_category}/${id}`;
    //     document.getElementById('category_method').value = 'PUT';

    //     // Fill values
    //     document.getElementById('category_name').value = category_name;
    //     document.getElementById('menu_order').value = menu_order;
    //     document.getElementById('status').value = (status == 'Active') ? 1 : 0;
    //     document.getElementById('showHome').value = showHome;
    //     document.getElementById('form_submit_btn').innerText = 'Update Category';
    // }

    // document.getElementById('editCategoryModal').addEventListener('hidden.bs.modal', function () {
    //     document.getElementById('categoryForm').reset();
    // });
    

    // function editSubCategoryModal(button) {
    //     let id = button.dataset.id;

    //     let form = document.getElementById('subCategoryForm');

    //     form.action = `${update_subCategory}/${id}`;
    //     document.getElementById('subcategory_method').value = 'PUT';

    //     // Fill values
    //     document.getElementById('sub_category_name').value = button.dataset.sub_category_name;                    

    //     document.querySelector('#subCategoryModal .modal-title').innerText = 'Edit Sub Category';
    //     document.getElementById('form_submit_btn').innerText = 'Update Category';
    // }

    // document.getElementById('subCategoryModal').addEventListener('hidden.bs.modal', function () {
    //     document.getElementById('subCategoryForm').reset();
    // });   

</script>
@endsection --}}