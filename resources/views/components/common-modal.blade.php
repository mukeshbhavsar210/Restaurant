<div class="modal fade drawer right-align" id="{{ $modal['modal_id'] }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $modal['title'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            @php
                $formConfig = $modal['formConfig'];
            @endphp

            <form class="controlForm" action="{{ $formConfig['action'] }}" method="POST" enctype="multipart/form-data">
                @csrf                       
                    <input type="hidden" name="_method" class="formMethod" value="POST">             
                    <div class="modal-body">
                        @if(isset($modal['accordion']) && is_array($modal['accordion']))
                            <div class="accordion accordion-flush" id="modalAccordion">
                                @foreach($modal['accordion'] as $index => $section)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button px-0 fw-semibold {{ $index ? 'collapsed' : '' }}" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#accordion{{ $index }}" >
                                                {{ $section['title'] }}
                                            </button>
                                        </h2>

                                        <div id="accordion{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#modalAccordion" >
                                            <div class="accordion-body px-0">
                                                <div class="row">
                                                    @foreach($section['fields'] as $field)
                                                        <div class="{{ $field['col'] ?? 'col-md-12' }}">
                                                            <div class="form-group">                                                      
                                                                @if(($field['type'] ?? '') != 'hidden' && !empty($field['label']))
                                                                    <label class="form-label" for="{{ $field['name'] }}">
                                                                        {{ $field['label'] }}
                                                                        @if($field['required'] ?? false)
                                                                            <span class="text-danger">*</span>
                                                                        @endif
                                                                    </label>
                                                                @endif

                                                                @if($field['type'] == 'textarea')
                                                                    <textarea name="{{ $field['name'] }}" class="form-control" rows="{{ $field['rows'] ?? 3 }}" placeholder="{{ $field['placeholder'] ?? '' }}"
                                                                        {{ !empty($field['required']) ? 'required' : '' }}
                                                                    ></textarea>
                                                                @elseif($field['type'] == 'checkbox')
                                                                    <div class="{{ isset($field['class']) ? $field['class'] : 'row' }}">
                                                                        @foreach($field['options'] as $option)
                                                                            <div class="col">
                                                                                <label class="custom-checkbox" for="data_{{ $option[$field['option_value']] }}">
                                                                                    <input type="checkbox" id="data_{{ $option[$field['option_value']] }}" name="{{ $field['name'] }}[]"
                                                                                        value="{{ $option[$field['option_text']] }}">
                                                                                    <span class="checkmark"></span>
                                                                                    {{ $option[$field['option_text']] }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}"
                                                                    {{ !empty($field['required']) ? 'required' : '' }} >                                                        
                                                                @endif
                                                            </div>                                                        
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="row">
                            @foreach($modal['formConfig']['fields'] ?? [] as $field)
                                <div class="{{ $field['col'] ?? 'col-md-12' }}">
                                    <div class="form-group">
                                        @if(($field['type'] ?? '') != 'hidden' && !empty($field['label']))
                                            <label class="form-label" for="{{ $field['name'] }}">
                                                {{ $field['label'] }}
                                                @if($field['required'] ?? false)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                        @endif

                                        @if($field['type'] == 'price') 
                                            <div class="form-group" id="original-price">
                                                <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" id="{{ $field['name'] }}" value="{{ $field['value'] ?? old($field['name']) }}"
                                                    placeholder="{{ $field['placeholder'] ?? '' }}" class="form-control {{ $field['class'] ?? '' }}" >
                                            </div>

                                        @elseif($field['type'] == 'radio')                                   
                                            <div class="row">
                                                @foreach($field['options'] as $option)
                                                    <div class="col-6">
                                                        <label class="custom-radio mb-1">
                                                            <input type="radio" name="{{ $field['name'] }}" value="{{ $option }}">
                                                            <span class="radio-mark"></span>
                                                            {{ $option }}
                                                            {{ $option == 1 ? 'Seat' : 'Seats' }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        @elseif($field['type'] == 'veg_radio')                                   
                                            <div class="form-group mb-0">
                                                <div class="btn-group mt-1" role="group" aria-label="Basic radio toggle button group">
                                                    @foreach($field['options'] as $key => $option)
                                                        <input type="radio" class="btn-check" name="{{ $field['name'] }}" value="{{ $option }}" id="{{ $option }}" autocomplete="off" {{ ($field['checked'] ?? '') == $key ? 'checked' : '' }} >
                                                        <label class="btn btn-outline-secondary" for="{{ $option }}">{{ $option }}</label>                                            
                                                    @endforeach
                                                </div>
                                            </div>

                                        @elseif($field['type'] == 'checkbox')
                                            <div class="{{ isset($field['class']) ? $field['class'] : 'row' }}">
                                                @foreach($field['options'] as $option)
                                                    <div class="col-6">
                                                        <label class="custom-checkbox" for="data_{{ $option[$field['option_value']] }}">
                                                            <input type="checkbox" id="data_{{ $option[$field['option_value']] }}" name="{{ $field['name'] }}[]"
                                                                value="{{ $option[$field['option_text']] }}">
                                                            <span class="checkmark"></span>
                                                            {{ $option[$field['option_text']] }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        @elseif($field['type'] == 'select')
                                            <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="form-select">
                                                <option value="">Select {{ $field['label'] }}</option>
                                                @foreach($field['options'] as $option)
                                                    <option value="{{ $option[$field['option_value']] }}"
                                                        {{ ($field['value'] ?? old($field['name'])) == $option[$field['option_value']] ? 'selected' : '' }}>
                                                        {{ $option[$field['option_text']] }}
                                                    </option>
                                                @endforeach                                               
                                            </select> 

                                        @elseif($field['type'] == 'selectLoad')
                                            <select name="{{ $field['name'] }}" id="{{ $field['id'] }}" class="form-select">
                                                <option value="">Select Menu</option>
                                            </select>

                                        @elseif($field['type'] == 'textarea')
                                            <textarea name="{{ $field['name'] }}" class="form-control {{ !empty($field['summer_class']) ? $field['summer_class'] : '' }}" rows="3"></textarea>
                                            
                                        @elseif($field['type'] == 'category')                                                                                
                                            <select name="sub_category_id" id="sub_category" class="form-select" >
                                                <option value="">Sub Category</option>
                                            </select> 
                                            
                                        @elseif($field['type'] == 'variants')
                                            <div class="flex-justify mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="variant_checkbox">
                                                    <label class="form-check-label" for="variant_checkbox">
                                                        Add Variants
                                                    </label>
                                                </div>

                                                <a href="javascript:0" class="add-icon" id="add-variant" style="display:none;">
                                                    <span class="sprites"></span>
                                                </a>
                                            </div>

                                            <div id="variant-wrapper" style="display:none;">
                                                <div id="variant-container">
                                                    <div class="row mb-1 variant-row">
                                                        <div class="col-6">
                                                            <select name="variants[0][name]" class="form-select">
                                                                <option value="">Select Variant</option>
                                                                <option value="Oil">Oil</option>
                                                                <option value="Butter">Butter</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="flex">
                                                                <input type="text" name="variants[0][price]" class="form-control" placeholder="Price">
                                                                <a href="javascript:0" class="remove-variant mt-1 delete-icon">
                                                                    <span class="sprites"></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        @elseif($field['type'] == 'multiple')
                                            <div class="form-group">                                           
                                                <input type="file" id="image" name="images[]" class="form-control" accept="image/*" multiple>
                                                <small class="text-muted">Maximum 5 images allowed.</small>
                                                <div id="image-preview" ></div>
                                            </div>

                                            <div class="row">
                                                @if(isset($product) && $product->images->isNotEmpty())                        
                                                    <div id="product-gallery" >                                    
                                                        @foreach ($product->images as $index => $image)
                                                            <div class="col-2 uploaded-images" id="image-row-{{ $image->id }}">                                        
                                                                <input type="hidden" name="image_array[{{ $index }}][image_id]" value="{{ $image->id }}">
                                                                <img src="{{ asset('uploads/product/small/'.$image->image) }}" class="rounded" />

                                                                <a href="javascript:void(0)" class="deleteProductImg delete-icon-edit" data-id="{{ $image->id }}">
                                                                    <span class="sprites"></span>
                                                                </a>
                                                            </div>
                                                        @endforeach                                                            
                                                    </div>                               
                                                @endif                                                                                                    
                                            </div>
                                            <div class="row" id="product-gallery"></div>
                                        @else
                                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" class="form-control {{ $field['class'] ?? '' }}"
                                                {{ !empty($field['required']) ? 'required' : '' }} 

                                                @if(isset($field['data']))
                                                    @foreach($field['data'] as $key => $value)
                                                        data-{{ $key }}="{{ $value }}"
                                                    @endforeach
                                                @endif

                                                @if(isset($field['placeholder']))
                                                    placeholder="{{ $field['placeholder'] ?? '' }}"
                                                @endif

                                                @if(isset($field['id']))
                                                    id="{{ $field['name'] ?? '' }}"
                                                @endif
                                            >
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">                    
                            <button type="submit" class="btn btn-primary">{{ $formConfig['button'] }}</button>                    
                        </div>
                    </form>                         
        </div>
    </div>
</div>

<div class="modal fade" id="commonDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage" class="mt-0 mb-0"></p>
            </div>
            <div class="modal-footer">
                <a href="" id="deleteBtn" class="btn btn-danger w-100">Yes, Delete</a>
            </div>
        </div>
    </div>
</div>

@section('customJs')
    <script>
        $('.editConfig').click(function () {
            $('.controlForm').attr('action', $(this).data('action'));            
            $('.formMethod').val($(this).data('method'));            
            $('.modal-title').text($(this).data('title'));
            $('.btn-primary').text($(this).data('button'));

            // Reset all checkboxes first
            $('[name="business_types[]"]').prop('checked', false);

            // Get DB values
            let businessTypes = $(this).data('business_types');

            if (businessTypes) {
                businessTypes = businessTypes.split(',');
                businessTypes.forEach(function (value) {
                    $('[name="business_types[]"][value="' + value + '"]')
                        .prop('checked', true);

                });
            }
            
            $('[name="name"]').val($(this).data('name'));
            $('[name="email"]').val($(this).data('email'));
            $('[name="phone"]').val($(this).data('phone'));
            $('[name="mobile"]').val($(this).data('mobile'));
            $('[name="address"]').val($(this).data('address'));
            $('[name="shipping"]').val($(this).data('shipping'));
            $('[name="upi_id"]').val($(this).data('upi_id'));
            $('[name="sgst"]').val($(this).data('sgst'));
            $('[name="cgst"]').val($(this).data('cgst'));
            $('[name="primary_color"]').val($(this).data('primary_color'));
            $('[name="secondary_color"]').val($(this).data('secondary_color'));
            $('[name="payment_key_id"]').val($(this).data('payment_key_id'));
            $('[name="payment_key_secret"]').val($(this).data('payment_key_secret'));
        });       

        $('.editPage').click(function () {
            $('.controlForm').attr('action', $(this).data('action'));            
            $('.formMethod').val($(this).data('method'));            
            $('.modal-title').text($(this).data('title'));
            $('.btn-primary').text($(this).data('button'));

            $('[name="page_name"]').val($(this).data('page_name'));
            $('[name="page_slug"]').val($(this).data('page_slug'));
            $('[name="content"]').val($(this).data('content'));            
        });

        $('.editProduct').click(function () {
            $('.controlForm').attr('action', $(this).data('action'));           
            $('.formMethod').val($(this).data('method'));
            $('.modal-title').text($(this).data('title'));
            $('.btn-primary').text($(this).data('button'));            

            $('[name="name"]').val($(this).data('name'));
            $('[name="slug"]').val($(this).data('slug'));
            $('[name="category_id"]').val($(this).data('category_id'));
            $('[name="menu_id"]').val($(this).data('menu_id'));
            $('[name="price"]').val($(this).data('price'));
            $('[name="description"]').val($(this).data('description'));
            $('[name="image_id"]').val($(this).data('image_id'));
        });       

        $('.editPassword').click(function () {
            $('.controlForm').attr('action', $(this).data('action'));           
            $('.formMethod').val($(this).data('method'));
            $('.modal-title').text($(this).data('title'));
            $('.btn-primary').text($(this).data('button'));            

            $('[name="current_password"]').val($(this).data('current_password'));
            $('[name="password"]').val($(this).data('password'));
            $('[name="password_confirmation"]').val($(this).data('password_confirmation'));            
        });

        $('.editProfile').click(function () {
            $('.controlForm').attr('action', $(this).data('action'));           
            $('.formMethod').val($(this).data('method'));
            $('.modal-title').text($(this).data('title'));
            $('.btn-primary').text($(this).data('button'));            

            $('[name="name"]').val($(this).data('name'));
            $('[name="email"]').val($(this).data('email'));
            $('[name="mobile"]').val($(this).data('mobile'));
        });

        $(document).on('click', '.commonDeleteBtn', function () {
            let url = $(this).data('url');
            let title = $(this).data('title');

            $('#deleteBtn').attr('href', url);
            
            $('.modal-title').html(
                'Delete ' + title + ''
            );
            $('#deleteMessage').html(
                'Are you sure you want to delete <br /><b>' + title + '?</b>'
            );
        });

    $("#category").change(function(){
        var category_id = $(this).val();

        $.ajax({
            url: '{{ route("product-subcategories.index") }}',
            type: 'get',
            data: {category_id: category_id},
            dataType: 'json',

            success: function(response) {

                $("#menu_item")
                    .find("option")
                    .not(":first")
                    .remove();

                $.each(response["subCategories"], function(key, item){

                    $("#menu_item").append(
                        `<option value='${item.id}'>${item.name}</option>`
                    );

                });
            },

            error: function(){
                console.log("Something went wrong")
            }
        });
    });

    const dropzone = new Dropzone("#image", {
        url:  "{{ route('temp-images.create') }}",
        maxFiles: 5,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, 

        success: function(file, response){
            $("#image_id").val(response.image_id);
            console.log(response)

            var html = `<div class="col-4 mb-3" id="image-row-${response.image_id}">
                <div class="uploaded-img">
                    <input type="hidden" name="image_array[]" value="${response.image_id}" >
                    <img src="${response.ImagePath}" class="img-fluid rounded" />
                    <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="deleteCardImg delete-icon">
                        <span class="sprites"></span>
                    </a>
                </div>
            </div>`;

            $("#product-gallery").append(html);
        },
        complete: function(file){
            this.removeFile(file);
        }
    });

    function deleteImage(id){
        $("#image-row-"+id).remove();
    }
</script>
@endsection