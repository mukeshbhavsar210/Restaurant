@extends('front.layouts.app')

@section('content')

<section class="menu-products-section menu-products-section--grid">
    @if($popularProducts->isNotEmpty())    
        <div class="menu-grid">
            @foreach($popularProducts as $product)
                @php
                    $qty = getProductQty($product->id);
                @endphp               

                <x-products :product="$product" :variants="$product->variants" :qty="$qty" />
            @endforeach
        </div>
    @endif
</section> 

@include('front/layouts/cart', ['product' => $product])
@include('front/layouts/message')

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

</script>
@endsection