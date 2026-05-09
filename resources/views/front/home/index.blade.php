@extends('front.layouts.app')

@section('content')

<div id="customAlert"></div>

<section class="menu-products-section menu-products-section--grid">
    @if($popularProducts->isNotEmpty())    
        <div class="menu-grid">
            @foreach($popularProducts as $product)
                <x-products :product="$product" :variants="$product->variants" />
            @endforeach
        </div>
    @endif
</section>   

<x-cart :seats="$seats"  />

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

    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        let type = $(e.target).data('type');
        $('#order_type').val(type);
    });        
</script>
@endsection