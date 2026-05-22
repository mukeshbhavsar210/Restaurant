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
                                        <a href="javascript:void(0)" class="editProductModal edit-icon"
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

                                            <span class="sprites"></span>
                                        </a>                                                                          

                                        <a href="javascript:void(0)" class="delete-icon commonDeleteBtn"
                                            data-bs-toggle="modal" data-bs-target="#commonDeleteModal"
                                            data-url="{{ route('products.delete', $value->id) }}" data-title="Product">
                                            <span class="sprites"></span>
                                        </a>
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