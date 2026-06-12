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
                    <span class="counts">{{ $products->count() }}</span>
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
                    <button type="button" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#{{ $productForm['modal_id'] }}">{{ $productForm['title'] }}</button>                    
                </div>                         
            </div>
        </div>        
        
        <div class="accordion mt-2" id="productAccordion">
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
                                                            <span class="badge bg-black">{{ $value->product_images->count() }}</span>                                                            
                                                            
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
                                                            <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" height="90" class="me-3 rounded"/>
                                                        @else
                                                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}" height="90" class="me-3 rounded" />
                                                        @endif
                                                        <div>
                                                            <h5 class="mb-0">{{ $value->name }}</h5>
                                                            <p class="text-muted mb-0">{{ $value->description }}</p>                                                            
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
                                                    <div class="flex pull-right">
                                                        @if ($value->status == 1)  
                                                            <span class="sprites green-tick-icon"></span>
                                                        @else
                                                            <span class="sprites red-tick-icon"></span>
                                                        @endif  
                                                        
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
                        @endforeach
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

    //Dropzone.autoDiscover = false;
    // const tempImageUrl = "{{ route('temp-images.create') }}";
    // const dropzone = new Dropzone("#image_dropzone", {
    //     // url: "{{ route('temp-images.create') }}",
    //     url: tempImageUrl,
    //     maxFiles: 5,
    //     paramName: "image",
    //     addRemoveLinks: true,
    //     acceptedFiles: "image/jpeg,image/png,image/gif",
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
    //     },
    //     success: function(file, response) {
    //         // code
    //         console.log('Dropzone Loaded', Dropzone);
    //     },
    //     complete: function(file) {
    //         this.removeFile(file);
    //     }
    // });    

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