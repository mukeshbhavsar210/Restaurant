@extends('front.layouts.app')

@section('content')

<div class="menu-content--categories-medium-photo menu-content">
    <section class="menu-products-section menu-products-section--grid">
        <div class="menu-grid">
            @if(session('wishlist'))
                @foreach(session('wishlist') as $id => $value)
                    <div class="menu-product">
                        <div class="menu-product__item">
                            <a href="{{ route('clear_wishlist', $id) }}" class="wishlist-icon-active">
                                <span class="sprites"></span>
                            </a>                            
                            <div class="menu-product__item__img">
                                @php
                                    $product = \App\Models\Product::with('product_images')->find($id);
                                    $productImage = $product?->product_images->first();
                                @endphp

                                @if (!empty($value['image']))
                                    <img src="{{ asset('uploads/product/large/'.$value['image']) }}">
                                @elseif (!empty($productImage?->image))
                                    <img src="{{ asset('uploads/product/large/'.$productImage->image) }}">
                                @endif
                            </div>
                            <div class="menu-product__item__top-block">
                                <div class="menu-product__item__name text-overflow">{{ $value['name'] }}</p></div>
                                <div class="menu-product__item__price no-wrap">
                                    ₹{{ $value['price'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
</div>
@endsection