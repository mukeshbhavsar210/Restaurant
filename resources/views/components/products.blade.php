@props(['product', 'popularProducts', 'variants', 'seats', 'qty', 'type', 'config', ])

@php
    $total = 0;
    $deliveryFee = 50;
    $id = $product->id;    
    $productImage = $product->product_images->first();    
    $wishlist = session('wishlist', []);
    $wishlistIds = array_keys($wishlist);
    $cart = session('cart', []);            
    foreach($cart as $item){
        $total += $item['price'] * $item['quantity'];
    }      

    $cartItem = collect(session('cart', []))->firstWhere('product_id', $product->id);
    $cartKey = collect(session('cart', []))->search(fn($item) => $item['product_id'] == $product->id);
    $qty = $cartItem['quantity'] ?? 0;
@endphp


<div class="menu-product">
    <div class="menu-product__item">
        @if($type != 'wishlist' && $qty > 0)
            <div class="menu-product__item__ordered_qty">
                {{ $qty }}
            </div>
        @endif
        
        <div class="overlap">
            @if(isset($wishlist[$product->id]))
                <a href="{{ route('remove_wishlist', $product->id) }}" class="{{ $type === 'wishlist' ? 'clear-icon' : 'wishlist-icon-active' }}">
                    <span class="sprites"></span>
                </a>                
            @else
                <a href="{{ route('addwishlist', $product->id ) }}" class="wishlist-icon">
                    <span class="sprites"></span>
                </a>
            @endif

            @if($type == 'Non-veg')
                <span class="sprites nonveg-icon"></span>
            @elseif($type == 'Egg')
                <span class="sprites egg-icon"></span>
            @elseif($type == 'Veg')
                <span class="sprites veg-icon"></span>
            @else
                <span class=""></span>
            @endif 
        </div>
                
        <a href="javascript:void(0)" class="open-modal" data-modal="productModal_{{ $product->id }}" class="product-img">
            <div class="menu-product__item__img">
                @if (!empty($productImage->image))
                    <img src="{{ asset('uploads/product/large/'.$productImage->image) }}" alt="{{ $product->name }}">
                @endif
            </div>
        </a>

        <div class="menu-product__item__top-block">            
            <p>{{ Str::limit($product->name, 15, '...') }}</p>
            <p>
                <span class="product-price-show">
                    @if($product->variants->count() > 0)
                        ₹{{ $product->variants->first()->price }}
                    @else
                        ₹{{ $product->price }}
                    @endif
                </span>   
            </p>
        </div>
    </div>
</div>

<div class="custom-modal" id="productModal_{{ $product->id }}">
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

        <div class="modal-overlap">
            @if($type == 'Non-veg')
                <span class="sprites nonveg-icon"></span>
            @elseif($type == 'Egg')
                <span class="sprites egg-icon"></span>
            @elseif($type == 'Veg')
                <span class="sprites veg-icon"></span>
            @else
                <span class=""></span>
            @endif   
        </div>
    </div>                                    
    
    <div class="btnControl flex-justify">
        <a href="javascript:void(0)" class="back-icon close-modal" data-modal="productModal_{{ $product->id }}">
            <span class="sprites"></span>                                                            
        </a>        

        @if(isset($wishlist[$product->id]))
            <a href="{{ route('remove_wishlist', $product->id) }}" class="{{ $type === 'wishlist' ? 'clear-big-icon' : 'wishlist-icon-big-active' }}">
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
        <p class="mb-3">{{ \Illuminate\Support\Str::limit(strtolower($product->description), 50) }}</p>

        @if($cartItem)
            <div class="add-controls cart-{{ $cartKey }}">                
                <div class="flex-inner">     
                    <div class="qty-box flex align-items-center">  
                        <a href="javascript:0" class="remove-item-big subIconBig-{{ $cartKey }}" data-id="{{ $cartKey }}">
                            <span class="sprites"></span>
                        </a>                        

                        <div class="manage-modal-qty">
                            <span class="manage-qty-{{ $cartKey }}">{{ $qty }}</span>
                        </div>

                        <a href="javascript:0" class="qty-increase-big" data-id="{{ $cartKey }}">
                            <span class="sprites"></span>
                        </a>
                    </div>                        
                </div>                
            </div>
            <input type="hidden" name="variant_name" class="variant_name" value="{{ $cartItem['variant'] ?? '' }}">
        @else
            <form action="{{ route('front.addCart', $product->id) }}" method="GET" class="cart-form" data-id="{{ $product->id }}">
                @if($product->variants->count() > 0)
                    <input type="hidden" name="variant_name" class="variant_name" value="{{ $variants->first()->name ?? '' }}">
                    <input type="hidden" name="variant_price" class="variant_price" value="{{ $variants->first()->price }}">
                {{--@else
                     <input type="hidden" name="variant_price" value="{{ $product->price }}"> --}}
                @endif

                <button type="submit" class="add-to-cart qty-increase-big">
                    <span class="sprites"></span>
                </button>
            </form>      
        @endif        
       
        <div class="variant-group mt-3" role="group">
            @if($product->variants->count() > 0)
                @foreach($product->variants as $key => $variant)
                    <label class="custom-radio" for="variant-{{ $variant->id }}">
                        <input type="radio" class="product-variant" name="variant" id="variant-{{ $variant->id }}"
                            value="{{ $variant->price }}" data-name="{{ $variant->name }}" 
                            {{ $loop->first ? 'checked' : '' }} >
                            <span class="radio-mark"></span>
                            {{ $variant->name }}
                    </label>
                @endforeach
            @endif
        </div>                                                                    
    </div>    
</div>