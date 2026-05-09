@props(['product', 'popularProducts', 'variants', 'seats'])

@php
    $productImage = $product->product_images->first();
@endphp

<div class="menu-product">
    <div class="menu-product__item">
        @php
            $cart = session('cart', []);
            $id = $product->id;
            $qty = collect($cart)
                    ->filter(function ($item) use ($product) {
                        return $item['product_id'] == $product->id;
                    })
                    ->sum('quantity');
        @endphp     

        @if($qty > 0)
            <div class="menu-product__item__ordered_qty">
                {{ $qty }}
            </div>
        @endif
    
        @php
            $wishlist = session('wishlist', []);
        @endphp

        @if(isset($wishlist[$product->id]))
            <a href="{{ route('clear_wishlist', $product->id) }}" class="wishlist-icon-active">
                <span class="sprites"></span>
            </a>
        @else
            <a href="{{ route('addwishlist', $product->id ) }}" class="wishlist-icon">
                <span class="sprites"></span>
            </a>
        @endif           
        

        {{-- Product Image --}}                
        <a href="javascript:void(0)" class="open-modal" data-modal="productModal_{{ $product->id }}" class="product-img">
            <div class="menu-product__item__img">
                @if (!empty($productImage->image))
                    <img src="{{ asset('uploads/product/large/'.$productImage->image) }}" alt="{{ $product->name }}">
                @endif
            </div>
        </a>

        {{-- Product Name + Price --}}
        <div class="menu-product__item__top-block">            
            <p>{{ $product->name }}</p>
            <p>
                ₹<span class="product-price-show">
                    @if($product->variants->count() > 0)
                        {{ $product->variants->first()->price }}
                    @else
                        {{ $product->price }}
                    @endif
                </span>   
            </p>
        </div>
    </div>
</div>

<div class="custom-modal" id="productModal_{{ $product->id }}">    
    <div class="menuContainer">
        <div class="product-pic">
            @if($product->product_images->count() > 0)
                <div class="product-slider">
                    @foreach($product->product_images as $productImage)                            
                        <div>
                            @if(!empty($productImage->image))
                                <img src="{{ asset('uploads/product/large/'.$productImage->image) }}" alt="{{ $product->name }}" />
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <img  src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="{{ $product->name }}" />
            @endif
        </div>                                    
        
        <div class="btnControl flex-justify">
            <a href="javascript:void(0)" class="back-icon close-modal" data-modal="productModal_{{ $product->id }}">
                <span class="sprites"></span>                                                            
            </a>

            @php
                $wishlist = session('wishlist', []);
            @endphp

            @if(isset($wishlist[$product->id]))
                <a href="{{ route('clear_wishlist', $product->id) }}" class="wishlist-icon-big-active">
                    <span class="sprites"></span>
                </a>
            @else
                <a href="{{ route('addwishlist', $product->id ) }}" class="wishlist-icon-big">
                    <span class="sprites"></span>
                </a>
            @endif                                                                        
        </div>

        <div class="product-title flex-justify">
            <div class="product-name">
                @if (!empty($productImage->image))
                    <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" alt="{{ $product->name }}" class="small-thumb product-img">
                @endif
                <h2>{{ $product->name }}</h2>
            </div>
            <div class="product-price">
                ₹<span class="price product-price-show">
                    @if($product->variants->count() > 0)
                        {{ $product->variants->first()->price }}
                    @else
                        {{ $product->price }}
                    @endif
                </span>
            </div>
        </div>
        <div class="product-details">   
            <p>{{ \Illuminate\Support\Str::limit(strtolower($product->description), 50) }}</p>  
            
            @if($qty > 0)
                @foreach(session('cart') as $id => $value)
                    <div class="add-controls">
                        @if($value['quantity'] > 0)
                            <div class="qty-box flex align-items-center">
                                <a href="javascript:0" class="sub-qty products-sub" data-id="{{ $id }}">
                                    <span class="sprites"></span>                                                        
                                </a>

                                <div class="manage-qty">{{ $qty }}</div> 

                                <a href="javascript:0" class="add-qty products-add" data-id="{{ $id }}">
                                    <span class="sprites"></span>
                                </a>
                            </div>
                        </div>   
                        @endif                             
                    @endforeach
                @else
                    <form action="{{ route('front.addCart', $product->id) }}" method="GET" class="cart-form" data-modal="productModal">
                        @if($product->variants->count() > 0)
                            <input type="hidden" name="variant_name" value="{{ $variants->first()->name ?? '' }}">
                            <input type="hidden" name="variant_price" value="{{ $variants->first()->price }}">
                        @else
                            <input type="hidden" name="variant_price" value="{{ $product->price }}">
                        @endif

                        <button type="submit" class="add-to-cart-button product-add products-add">
                            <span class="sprites"></span>
                        </button>
                    </form>
                @endif
            
            <div class="variant-group mt-3" role="group">
                @if($product->variants->count() > 0)
                    @foreach($product->variants as $key => $variant)
                        <label class="custom-radio" for="variant-{{ $variant->id }}">
                            <input type="radio" class="product-variant" name="variant" id="variant-{{ $variant->id }}"
                                value="{{ $variant->price }}" data-name="{{ $variant->name }}" {{ $key == 0 ? 'checked' : '' }}>
                                <span class="radio-mark"></span>
                                {{ $variant->name }}
                        </label>
                    @endforeach
                @endif
            </div>                                                                    
        </div>
    </div>    
</div>