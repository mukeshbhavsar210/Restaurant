@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

@include('components.common-modal', [
    'modal' => $productForm,
])

<div class="card">
    <div class="card-body">
        <div class="row">                
            <div class="col-md-7 col-12">
                <div class="page-title"> 
                    <h4>Products</h4>                           
                    <span class="counts">{{ $products->total() }}</span>
                </div>
            </div>
            <div class="col-md-5 col-12 float-end">
                <div class="flexContainer">
                    {{-- <form action="" method="get" >
                        <div class="d-flex">
                            <div class="card-title mr-3">
                                <a href="javascript:0" onclick="window.location.href='{{ route('products.index') }}'" class="refresh-icon" >
                                    <span class="sprites"></span>                                            
                                </button>
                            </div>
        
                            <div class="card-tools">
                                <div class="input-group input-group searchMain">
                                    <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
        
                                    <div class="input-group-append">
                                        <button type="submit" class="btn">
                                            <i class="iconoir-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form> --}}
                    <button type="button" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#{{ $productForm['modal_id'] }}">{{ $productForm['button_name'] }}</button>                    
                </div>                         
            </div>
        </div>                            
            
        <div class="table-responsive mt-2">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-top-0">Product</th>
                        <th class="border-top-0" width="150">Category</th>
                        <th class="border-top-0 text-end" width="150">Price</th>
                        <th class="border-top-0 text-end" width="100">Status</th>
                        <th class="border-top-0 text-end" width="100">Action</th>
                    </tr>
                </thead>                     
                <tbody>
                    @if ($products->isNotEmpty())
                        @foreach($products as $value)
                            <tr>
                                <td>
                                    <div class="product-row">
                                        @php
                                            $productImage = $value->product_images->first();
                                        @endphp
                                        
                                        <a href="{{ route('products.edit', $value->id) }}" class="show-tooltip">
                                            <div class="veg-float">
                                                @php
                                                    $type = $value->menu?->veg_nonveg;
                                                @endphp

                                                @if($type == 'Non-veg')
                                                    <span class="sprites nonveg-icon"></span>
                                                @elseif($type == 'Egg')
                                                    <span class="sprites egg-icon"></span>
                                                @elseif($type == 'Veg')
                                                    <span class="sprites veg-icon"></span>
                                                @endif                                                                                                       
                                            </div>

                                            @if (!empty($productImage->image))
                                                <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" height="90" class="me-3 align-self-center rounded" >
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" height="90" class="me-3 align-self-center rounded" />
                                            @endif
                                        </a>
                                        <div class="flex-grow-1 text-truncate">
                                            <h5 class="product-title">{{ $value->name }}</h5>
                                            <p>{{ $value->description }}</p>                                            
                                        </div>
                                    </div>
                                </td>   
                                <td>
                                    <h5 class="mb-0">{{ $value->category->name }}</h5>
                                    @if($value->menu)
                                        <p class="tiny-font text-muted">{{ $value->menu->name }}</p>
                                    @endif
                                </td>                    
                                <td class="text-end">
                                    <h5 class="mb-0">
                                        @if($value->variants->count() > 0)
                                            @foreach($value->variants as $variant)
                                                ₹{{ round($variant->price) }} <span class="text-muted tiny-font">({{ $variant->name }})</span><br />
                                            @endforeach
                                        @else
                                            ₹{{ round($value->price) }}
                                        @endif                                    
                                    </h5>
                                </td>   
                                <td>
                                    <div class="pull-right">
                                        @if ($value->status == 1)  
                                            <span class="sprites green-tick-icon"></span>
                                        @else
                                            <span class="sprites red-tick-icon"></span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex pull-right">
                                        <a href="javascript:void(0)" class="editProductModal"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#createProductModal"
                                            data-action="{{ route('products.update', $value->id) }}"
                                            data-name="{{ $value->name }}"
                                            data-slug="{{ $value->slug }}"
                                            data-price="{{ $value->price }}"
                                            data-category="{{ $value->category_id }}"
                                            data-menu="{{ $value->menu_id }}"
                                            data-description="{{ $value->description }}"                                            
                                            data-button="Update Product" >
                                            
                                            <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                        </a>

                                        {{-- <a href="{{ route('products.delete', $value->id) }}" class="text-danger deleteProduct" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $value->id }}">
                                            <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>                                          --}}

                                        <a href="javascript:void(0)" class="text-danger commonDeleteBtn" data-bs-toggle="modal" data-bs-target="#commonDeleteModal"
                                                data-url="{{ route('products.delete', $value->id) }}" data-title="Product">

                                            <svg class="filament-link-icon w-4 h-4 mr-1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor">

                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9z"
                                                    clip-rule="evenodd">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>                                                                         

                                    <div class="modal fade" id="deleteModal{{ $value->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    Are you sure you want to delete?
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>

                                                    <a href="{{ route('products.delete', $value->id) }}" class="btn btn-danger">Yes, Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td>
                                    <h5>Product not created yet</h5>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
</div>
  
@endsection

@section('customJs')
<script>
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

     Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            url:  "{{ route('temp-images.create') }}",
            maxFiles: 5,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(file, response){
                $("#image_id").val(response.image_id);
                console.log(response)

               var html = `<div class="col-md-4 col-6" id="image-row-${response.image_id}">
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

        $(document).ready(function () {
            $('input[name="veg_nonveg"]').on('change', function () {
                // remove active from all labels
                $('input[name="veg_nonveg"]').closest('label').removeClass('active');

                // add active to selected one
                $(this).closest('label').addClass('active');
            });
        });


        $(document).on('click', '.editProductModal', function () {
            let action = $(this).data('action');
            let buttonText = $(this).data('button');
            // let categoryId = $(this).data('category');
            // let menuId = $(this).data('menu');
           
            // $('#category_id').val(categoryId).trigger('change');

            // setTimeout(function () {
            //     $('#menu_id').val(menuId);
            // }, 500);            

            $('#commonForm').attr('action', action);
            $('input[name="name"]').val($(this).data('name'));
            $('input[name="slug"]').val($(this).data('slug'));
            $('input[name="category"]').val($(this).data('category'));
            $('input[name="price"]').val($(this).data('price'));
            $('textarea[name="description"]').val($(this).data('description'));
            $('textarea[name="description"]').val($(this).data('description'));            
            $('#submitBtn').text(buttonText);
        });
</script>
@endsection