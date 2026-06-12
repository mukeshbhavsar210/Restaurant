@extends('front.layouts.app')

@section('content')    
    @if($products->count())
        <section class="menu-products-section menu-products-section--grid"> 
            <div class="menu-grid">
                @foreach($products as $product)
                    <x-products :product="$product" :variants="$product->variants" :qty="0" type="wishlist" :config="$config" class="clear-icon"  />
                @endforeach
            </div>
        </section>
    @else
        <div class="emptyBag">
            <img src="http://127.0.0.1:8000/front-assets/images/empty_bag.png" alt="empty bag">
            <p>No Wishlist items</p>
            <a href="{{ route('front.menu') }}" class="btn btn-primary mt-2 btm-sm">Add to Favourite</a>
        </div>
    @endif

    @foreach($products as $product)
        <x-carts :product="$product" :variants="$product->variants" :seats="$seats" :qty="0" type="wishlist" :config="$config" class="clear-icon" />
    @endforeach
@endsection