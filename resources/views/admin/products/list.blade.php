@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

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
                    <form action="" method="get" >
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
                    </form>
                    <a href="javascript:0" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</a>                    
                </div>                         
            </div>
        </div>                            
            
        <div class="table-responsive mt-1">
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
                                            ₹{{ round($value->variants->first()->price) }}
                                        @else
                                            ₹{{ round($value->price) }}
                                        @endif                                    
                                    </h5>                                                                    

                                    @if($value->variants->count() > 0)                                            
                                        @foreach($value->variants as $variant)
                                            <p class="tiny-font mb-0 text-muted">{{ $variant->name }} : ₹{{ round($variant->price) }}</p>
                                        @endforeach                                            
                                    @endif
                                </td>                                
                                <td class="text-end">
                                    <div class="pull-right">
                                        @if ($value->status == 1)  
                                            <span class="sprites green-tick-icon"></span>
                                        @else
                                            <span class="sprites red-tick-icon"></span>
                                        @endif
                                    </div>
                                </td> 
                                <td class="text-end">
                                    <a href="{{ route('products.edit', $value->id) }}">
                                        <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </a>                                        
                                    <a href="{{ route('products.delete', $value->id) }}" class="text-danger deleteProduct">
                                        <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>                                
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

<div class="modal fade drawer right-align" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form {{ route('products.store') }} method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Item Name</label>
                            <input type="text" name="name" id="name" class="form-control slug-source" placeholder="Name" data-target="#slug" >
                            <input type="hidden" readonly name="slug" id="slug" class="form-control">
                            <p class="error"></p>                                    
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-select">
                                        <option value="">Select</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error"></p>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="menu">Menu Item</label>
                                    <select name="menu_id" id="menu_item" class="form-select">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="original-price">
                            <label for="price">Price</label>
                            <input type="number" name="price" id="price" class="form-control" placeholder="Price">
                            <p class="error"></p>
                        </div>

                        <div class="flex-justify mt-2 mb-2">
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
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" cols="2" rows="2" class="form-control" placeholder="Description"></textarea>
                        </div>
                                                    
                        <div id="image" class="dropzone dz-clickable mt-3 mb-2">
                            <div class="dz-message needsclick">Drop Product Image</div>
                        </div>                            

                        <div class="row">
                            @if(isset($product) && $product->images->isNotEmpty())                        
                                <div id="product-gallery" class="row">                                    
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
                                            
                            <div class="row" id="product-gallery"></div>         
                        </div>                                                     
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
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
</script>
@endsection