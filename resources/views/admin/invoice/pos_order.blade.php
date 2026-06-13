@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

@php
    $businessTypes = explode(',', $config->business_types);                    
    $tab1 = $businessTypes[0] ?? null;
    $tab2 = $businessTypes[1] ?? null;
    $tab3 = $businessTypes[2] ?? null;
    $icons = [ 'tab1_icon', 'tab2_icon', 'tab3_icon'];
@endphp

<div class="row">
    <div class="col-md-7">        
        <div class="card">
            <div class="card-body mobile-padd">
                <div class="row">
                    <div class="col-md-2">
                        <div class="nav flex-column nav-pills" id="category-tab" role="tablist">
                            @foreach($categories as $key => $category)
                                <button class="nav-link {{ $key == 0 ? 'active' : '' }}"
                                    id="tab-{{ $category->id }}" data-bs-toggle="pill"
                                    data-bs-target="#category-{{ $category->id }}" type="button">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-10">
                        <div class="tab-content">
                            @foreach($categories as $key => $category)
                                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="category-{{ $category->id }}">
                                    <div class="flex-2">
                                        @foreach($category->products as $product)
                                                                                
                                            @php
                                                $type = $product->menu?->veg_nonveg;   
                                                $cart = session('cart', []);
                                                $cartKey = $product->id . '_default';
                                                $qty = $cart[$cartKey]['quantity'] ?? 0;                                  
                                            @endphp
                                            
                                            @if($qty > 0)       
                                                @php
                                                    $cart = session('cart', []);
                                                    $cartKey = $product->id . '_default';
                                                @endphp

                                            @if(isset($cart[$cartKey]))
                                                <div class="product-card">
                                                    <div class="line {{ $type == 'Non-veg' ? 'non-veg-card' : ($type == 'Egg' ? 'egg-veg-card' : ($type == 'Veg' ? 'veg-card' : '')) }}"></div>

                                                    <a href="#" class="control-btn increase-cart-main" data-id="{{ $product->id }}">
                                                        {{ $product->name }}
                                                    </a>

                                                    <div class="added">
                                                        <span class="sprites green-tick-icon"></span>
                                                        <a href="#" class="clear-icon">
                                                            <span class="sprites"></span>
                                                        </a>
                                                    </div>
                                                </div>           
                                                @endif                          
                                                {{-- @foreach(session('cart', []) as $id => $item)    
                                                    <div class="product-card">
                                                        <div class="line {{ $type == 'Non-veg' ? 'non-veg-card' : ($type == 'Egg' ? 'egg-veg-card' : ($type == 'Veg' ? 'veg-card' : '')) }}"></div>
                                                        <a href="#" class="control-btn increase-cart" data-id="{{ $id }}">{{ $product->name }}</a>

                                                        <div class="added">
                                                            <span class="sprites green-tick-icon"></span>
                                                            <a href="#" class="clear-icon">
                                                                <span class="sprites"></span>
                                                            </a>
                                                        </div> 
                                                    </div>
                                                @endforeach --}}
                                            @else
                                                <div class="product-card">
                                                    <div class="line {{ $type == 'Non-veg' ? 'non-veg-card' : ($type == 'Egg' ? 'egg-veg-card' : ($type == 'Veg' ? 'veg-card' : '')) }}"></div>
                                                    <form action="{{ route('front.addCart', $product->id) }}" method="GET" class="cart-form" data-id="{{ $product->id }}">                                                
                                                        <button type="submit" class="control-btn">
                                                            {{ $product->name }}                                                                                                                
                                                        </button>                                               
                                                    </form>
                                                </div>
                                            @endif                                            
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
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
        @endphp

        <div class="card">
             <ul class="custom-tabs">
                @foreach ($businessTypes as $type)
                    <li class="tab-link {{ $loop->first ? 'active' : '' }}" data-tab="tab{{ $loop->iteration }}" data-type="{{ $type }}">
                        {{ trim($type) }}
                    </li>
                @endforeach
            </ul>  
            
            <div class="card-body pt-0">
                @if(session()->has('cart') && count(session('cart')) > 0)
                    <a href="{{ route('cart.clear') }}" class="btn btn-danger">Clear</a>
                @endif

                @if(getCartCount() > 0)
                    <form method="POST" action="{{ route('submit.order') }}">
                        @csrf                                            
                        
                        <div class="scroll-order">
                            @foreach(session('cart', []) as $id => $item)    
                                @php
                                    $qty = $item['quantity'];
                                @endphp

                                <div class="cart-row cart-{{ $id }}">                                
                                    <div class="item-name">
                                        <a href="{{ route('cart.removecart', $id) }}" class="remove-icon">
                                        <span class="sprites"></span>
                                    </a>                                     
                                        {{ $item['name'] }}

                                        {{-- @if(!empty($item['variant']))
                                            ({{ $item['variant'] }})
                                        @endif --}}                                            
                                    </div>
                                    <div class="calculate">                                                                                        
                                        @if(getCartCount() > 0)
                                            <div class="qty">
                                                <a href="javascript:void(0)" class="sub-icon decrease-cart" data-id="{{ $id }}">
                                                    <span class="sprites"></span>
                                                </a>

                                                <span class="manage-qty manage-qty-{{ $id }}">{{ $qty }}</span>                                                   

                                                <a href="javascript:void(0)" class="add-icon increase-cart" data-id="{{ $id }}">
                                                    <span class="sprites"></span>
                                                </a>
                                            </div>                                           
                                        @endif                                                                                                            
                                    </div>
                                    <div class="price">₹{{ round($item['price']) }}</div>
                                    <input type="hidden" name="variant_name" class="variant_name" value="{{ $item['variant'] }}">
                                    {{-- <input type="text" name="variant_price" class="variant_price" value="{{ $item['price'] }}"> --}}
                                </div>
                            @endforeach
                        </div> 
                                                                                    
                        {{-- <span class="cart-total grandTotal">₹0</span> --}}
                        {{-- <input type="hidden" name="total" value="{{ $total }}" class="form-control">                             --}}                                                                                        
                        
                        @if($tab1)
                            <div class="tab-content-custom {{ $tab1 }} {{ $tab1 ? 'active' : '' }}">
                                <input type="hidden" name="seat_id" id="seat_id" value="{{ session('seat_id') }}" class="form-control">
                                <input type="hidden" name="who" value="admin" class="form-control">
                            </div>
                        @endif
                        
                        @if($tab2)
                            <div class="tab-content-custom {{ $tab2 }} {{ !$tab1 && $tab2 ? 'active' : '' }}">
                                <div class="form-group mb-2">                                                                    
                                    <input type="text" class="form-control field" data-name="name" name="name" placeholder="Name..." >
                                </div>
                                <div class="form-group mb-2">
                                    <input type="email" class="form-control field" data-name="email" name="email" placeholder="Email..." >
                                </div>                                
                                <div class="form-group mb-2">
                                    <select class="form-select field" data-name="outlet_id" name="outlet_id" >
                                        <option value="">Select Outlet</option>
                                        @foreach(areaData() as $value)                                            
                                            <option value="{{ $value->id }}">{{ $value->area_name }}</option>
                                        @endforeach                                            
                                    </select>
                                </div> 
                            </div>
                        @endif                    
                    
                        @if($tab3)
                            <div class="tab-content-custom {{ $tab3 }} {{ !$tab1 && !$tab2 && $tab3 ? 'active' : '' }}">
                                <div class="form-group mb-2">
                                    <textarea class="form-control" name="address" placeholder="Address..."></textarea>
                                </div>

                                <div class="form-group mb-2">
                                    <input type="text" class="form-control field" data-name="name" name="name" placeholder="Name..." >
                                </div>
                              
                                <div class="form-group mb-2">
                                    <input type="email" class="form-control field" data-name="email" name="email" placeholder="Email..." >
                                </div>

                                <div class="form-group mb-2">
                                    <select class="form-select field" data-name="outlet_id" name="outlet_id" >
                                        <option value="">Takeaway from Outlet</option>
                                        @foreach(areaData() as $value)                                            
                                            <option value="{{ $value->id }}">{{ $value->area_name }}</option>
                                        @endforeach                                            
                                    </select>
                                </div> 
                            </div>    
                        @endif

                        <input type="hidden" id="baseTotal" value="{{ $total }}">
                        <input type="hidden" name="order_type" id="order_type" value="{{ $tab1 }}" class="form-control">

                        <hr />
                        <div class="flex-justify">
                            <div>1</div>
                            <div>
                                <h5>Total: <b>₹{{ $total }}</b></h5>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 mt-2">Save Order</button>
                    </form>                                
                @else                    
                    <div class="emptyBag">
                        <img src="{{ asset('front-assets/images/empty_bag.png') }}" alt="empty bag" />
                        <p>Nothing to order</p>
                    </div>
                @endif    
            </div>                
            
            {{-- <div class="card-body">
                <div id="cart-items"></div>
                <h5>Total ₹<span id="cart-total">0</span></h5>
                <button class="btn btn-success w-100">Save Order</button>                                    
            </div> --}}
        </div>
    </div>
</div>
@endsection

@section('customJs')
<script type="text/javascript">    
    $(document).on('click', '.increase-cart-main', function() {        
        let id = $(this).data('id');

        $.get('/cart/increase-main/' + id, function(response){
            if(response.status){
                location.reload(); 
            }
        });
    });

    $(document).on('click', '.increase-cart', function(){
        let id = $(this).data('id');

        $.get('/cart/increase/' + id, function(response){
            if(response.status){
                location.reload(); // or update qty without reload
            }
        });
    });

    $(document).on('click', '.decrease-cart', function(){
        let id = $(this).data('id');

        $.get('/cart/decrease/' + id, function(response){
            if(response.status){
                location.reload(); // or update qty without reload
            }
        });
    });
</script>
@endsection