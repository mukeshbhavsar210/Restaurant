@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">
    <div class="card-body">
        <div class="row">                
            <div class="col-md-10 col-12">
                <div class="page-title"> 
                    <h4>Products</h4>
                    <span class="counts">{{ $products->count() }}</span>                 
                </div>
            </div>            
        </div>    

        <div class="accordion mt-3" id="productAccordion">
            @foreach($products as $categoryName => $categoryProducts)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $loop->index }}">
                        <button class="accordion-button {{ !$loop->first ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}">
                            {{ $categoryName }}
                            <span class="ms-2 badge bg-secondary">
                                {{ $categoryProducts->count() }}
                            </span>
                        </button>
                    </h2>

                    <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                        data-bs-parent="#productAccordion">

                        <div class="accordion-body">
                             <table class="table align-middle mb-0">
                                <tbody> 
                                    @if ($products->isNotEmpty())
                                        @foreach($categoryProducts as $value)
                                            <tr>
                                                <td>
                                                    <div class="product-row">
                                                        <div class="show-tooltip">
                                                            <div class="veg-float">
                                                                    @php
                                                                        $type = $value->menu?->veg_nonveg;
                                                                        $productImage = $value->product_images->first();
                                                                    @endphp

                                                                    @if($type == 'Non-veg')
                                                                        <span class="sprites nonveg-icon"></span>
                                                                    @elseif($type == 'Egg')
                                                                        <span class="sprites egg-icon"></span>
                                                                    @elseif($type == 'Veg')
                                                                        <span class="sprites veg-icon"></span>
                                                                    @endif                                                                                                       
                                                                </div>

                                                                @if (!empty($productImage?->image))
                                                                    <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" height="90"
                                                                class="me-3 rounded"/>
                                                                @else
                                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" height="90"
                                                                class="me-3 rounded" />
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h5 class="mb-0">{{ $value->name }}</h5>
                                                                <p class="text-muted mb-0">
                                                                    {{ $value->description }}
                                                                </p>
                                                            </div>
                                                        </div>                                                    
                                                    </div> 
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
                                                <td width="150">
                                                    <div class="flex pull-right">
                                                        {{-- <a href="javascript:void(0)"
                                                            class="editProduct edit-icon"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#createProductModal"
                                                            data-action="{{ route('products.update', $value->id) }}"                                            
                                                            data-method="PUT"
                                                            data-title="Edit Product"
                                                            data-button="Update Product"

                                                            data-name="{{ $value->name }}"
                                                            data-slug="{{ $value->slug }}"
                                                            data-price="{{ $value->price }}"
                                                            data-category_id="{{ $value->category_id }}"
                                                            data-menu_id="{{ $value->menu_id }}"
                                                            data-price="{{ $value->price }}"
                                                            data-description="{{ $value->description }}"
                                                            data-image_id="{{ $value->image_id }}"
                                                            >

                                                            <span class="sprites"></span>
                                                        </a> --}}

                                                        <a href="{{ route('products.edit', $value->id) }}" class="edit-icon">
                                                            <span class="sprites"></span>
                                                        </a>                                  
                                                        
                                                        <a href="javascript:void(0)" class="delete-icon commonDeleteBtn"
                                                            data-bs-toggle="modal" data-bs-target="#commonDeleteModal"
                                                            data-url="{{ route('products.delete', $value->id) }}" data-title="{{ $value->name }}">
                                                            <span class="sprites"></span>
                                                        </a>                                        
                                                    </div>                                                     
                                                </td>
                                            </tr>
                                        @endforeach                                    
                                    @endif
                                </tbody>
                            </table>    
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="modal fade drawer right-align show" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Product</h5>
                        <a href="{{ route('products.index') }}" class="btn-close"></a>                            
                    </div>

                    <form action="{{ route('products.update',$product->id) }}" method="post" enctype="multipart/form-data" >
                            @csrf       
                            <div class="modal-body"> 
                                <div class="form-group">
                                    <label for="name">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control slug-source" data-target="#slug" placeholder="Name" value="{{ $product->name }}">                                    
                                    <input type="hidden" readonly name="slug" id="slug" class="form-control" placeholder="slug" value="{{ $product->slug }}">                                    
                                </div>  

                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="category">Category <span class="text-danger">*</span></label>
                                            <select name="category" id="category" class="form-select">
                                            <option value="">Select a category</option>
                                                @if ($categories->isNotEmpty())
                                                    @foreach ($categories as $value)
                                                        <option {{ ($product->category_id == $value->id) ? 'selected' : '' }} value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="category">Menu Item <span class="text-danger">*</span></label>
                                            <select name="menu" id="sub_category" class="form-select">
                                                <option value="">Select Menu</option>
                                                @if ($subCategories->isNotEmpty())
                                                    @foreach ($subCategories as $subCategory)
                                                        <option {{ ($product->menu_id == $subCategory->id) ? 'selected' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="price">Price <span class="text-danger">*</span></label>
                                    <div id="original-price">
                                        <input type="text" name="price" id="price" class="form-control" placeholder="Price" value="{{ $product->price }}">
                                    </div>
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
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" cols="5" rows="3" class="form-control" placeholder="Description">{{ $product->description }}</textarea>
                                </div>                                

                                <div class="form-group">
                                    <label for="images">Images <span class="text-danger">*</span></label>
                                    <input type="file" id="image" name="images[]" class="form-control" accept="image/*" multiple>
                                    <small class="text-muted">Maximum 5 images allowed.</small>
                                </div>

                                <div class="row" id="product-gallery">
                                    @if(isset($product) && $product->product_images->isNotEmpty())
                                        @foreach ($product->product_images as $index => $image)
                                            <div class="col-4 uploaded-images uploaded-img mb-2" id="image-row-{{ $image->id }}">
                                                <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                                                <img src="{{ asset('uploads/product/small/'.$image->image) }}" class="img-fluid rounded">

                                                <a href="javascript:void(0)" class="deleteProductImg delete-icon" data-id="{{ $image->id }}">
                                                    <span class="sprites"></span>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update Product</button>                            
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>    
</div>

<div class="modal-backdrop fade show"></div>
@endsection

@section('customJs')
<script>
    $("#category").change(function(){
        var category_id = $(this).val();
        $.ajax({
            url: '{{ route("product-subcategories.index") }}',
            type: 'get',
            data: {category_id:category_id},
            dataType: 'json',
            success: function(response) {
                $("#sub_category").find("option").not(":first").remove();
                $.each(response["subCategories"],function(key,item){
                    $("#sub_category").append(`<option value='${item.id}' >${item.name}</option>`)
                })
            },
            error: function(){
                console.log("Something went wrong")
            }
        });
    })    

    $(document).ready(function () {
        $('input[name="veg_nonveg"]').on('change', function () {
            // Remove active class from all labels
            $('#options label').removeClass('active');

            // Add active class to selected radio's parent label
            $(this).closest('label').addClass('active');

        });
    });

    $(document).on('click', '.deleteProductImg', function () {
        let imageId = $(this).data('id');
        $.ajax({
            url: '/products/image/delete/' + imageId,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                $('#image-row-' + imageId).remove();
            }
        });
    });
</script>
@endsection