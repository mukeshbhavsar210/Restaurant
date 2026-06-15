@extends('front.layouts.app')

@section('content')    
    
@if($menus->count() > 1)
    <section class="subcategories-section">
        <div class="subcategories-section__item text-overflow">                
            <a href="{{ route('front.menu', [$category->slug]) }}" class="{{ empty($menuSlug) ? 'active' : '' }}">
                All
            </a>
                
            @foreach($menus as $menu)   
                <a href="{{ route('front.menu', [$category->slug, $menu->slug]) }}" class="{{ $menuSlug == $menu->slug ? 'active' : '' }}">
                    {{ $menu->name }}
                </a>
            @endforeach
        </div>
    </section>  
@endif  
    
    <section class="menu-products-section menu-products-section--grid">
        <div class="menu-grid">
            @if(!$category)
                @if($popularProducts->isNotEmpty())                    
                    @foreach($popularProducts as $product)
                        @php
                            $qty = getProductQty($product->id);
                            $type = $product->menu?->veg_nonveg;
                        @endphp 
                        
                        {{ $qty }}

                        <x-products :product="$product" :variants="$product->variants" :qty="$qty" :type="$type" :config="$config" class="wishlist-icon-active" />
                    @endforeach
                @endif
            @else
                @if ($products->isNotEmpty())
                    @foreach ($products as $product)  
                        @php
                            $qty = getProductQty($product->id);
                            $type = $product->menu?->veg_nonveg;                        
                        @endphp

                        <x-products :product="$product" :variants="$product->variants" :qty="$qty" :type="$type" :config="$config" class="wishlist-icon-active" />
                    @endforeach
                @endif
            @endif
        </div>
    </section>        

    <x-carts :product="$product" :variants="$product->variants" :qty="$qty" :seats="$seats" :type="$type" :config="$config" />
@endsection

@section('customJs')
<script>
    $('#seat_id').change(function(){
        element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: {title: element.val()},
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled', false);
                if(response["status"] == true){
                    $("#slug").val(response["slug"]);
                }
            }
        });
    })
        
    //Hide alert 
    $(function() {
        setTimeout(function() { $(".alert").fadeOut(1500); }, 1500)
    })

    $(document).ready(function() {
        $('.lab-slide-up').find('a').attr('data-toggle', 'modal');
        $('.lab-slide-up').find('a').attr('data-target', '#lab-slide-bottom-popup');
    });
</script>
@endsection