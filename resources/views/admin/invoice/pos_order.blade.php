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
                                            @endphp

                                            <form action="{{ route('front.addCart', $product->id) }}" method="GET" class="cart-form" data-id="{{ $product->id }}">
                                                <div class="product-card" 
                                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                                                    <button type="submit" class="control-btn">                     
                                                        <div class="line 
                                                            {{ $type == 'Non-veg' ? 'non-veg-card' : '' }}
                                                            {{ $type == 'Egg' ? 'egg-card' : '' }}
                                                            {{ $type == 'Veg' ? 'veg-card' : '' }}">                                                    
                                                        </div>
                                                        {{ $product->name }}
                                                        <div class="added">
                                                            <span class="sprites green-tick-icon"></span>
                                                            <a href="#" class="clear-icon">
                                                                <span class="sprites"></span>
                                                            </a>
                                                        </div>
                                                    </div> 
                                                </button>                                               
                                            </form>
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

             @if(getCartCount() > 0)
                <div class="card-body pt-0">
                    <h4>Table {{ $seat->table }}</h4>
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

                                        {{-- <a href="{{ route('cart.removecart', '$id') }}" class="remove-icon">
                                            <span class="sprites"></span>
                                        </a> --}}

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
                                                            
                        <input type="hidden" id="baseTotal" value="{{ $total }}">
                        <input type="hidden" name="order_type" id="order_type" value="{{ $tab1 }}" class="form-control">
                        {{-- <span class="cart-total grandTotal">₹0</span> --}}
                        {{-- <input type="hidden" name="total" value="{{ $total }}" class="form-control">                             --}}                                                                                        
                        
                        @if($tab1)
                            <div class="tab-content-custom {{ $tab1 }} {{ $tab1 ? 'active' : '' }}">
                                <div class="form-group mb-2">
                                    <select name="seat_id" id="seat_id" class="form-select">
                                        <option value="">Table...</option>                                        
                                        @foreach($seats as $seat)
                                            <option value="{{ $seat->id }}"
                                                {{ session('table_id') == $seat->id ? 'selected' : '' }}>
                                                Table {{ $seat->table }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <input type="text" name="seat_id" id="seat_id" value="{{ $seat->table }}" class="form-control">
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
                        <div class="flex">
                            <div></div>
                            <div>
                                <p>Total: {{ $total }}</p>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100">Save Order</button>
                    </form>                
                </div>
            @else                    
                <div class="emptyBag">
                    <img src="{{ asset('front-assets/images/empty_bag.png') }}" alt="empty bag" />
                    <p>Nothing to order</p>
                </div>
            @endif                    
            
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
    let cart = [];

    $('.product-card').click(function(){        
        $(this).addClass('added-product');
        // $('.product-card').removeClass('added-product');
        // $(this).addClass('added-product');

        let id = $(this).data('id');
        let name = $(this).data('name');
        let price = parseFloat($(this).data('price'));
        let item = cart.find(x => x.id == id);

        if(item){
            item.qty++;
        }else{
            cart.push({
                id:id,
                name:name,
                price:price,
                qty:1
            });
        }

        renderCart();
    });

    function renderCart() {
        let html = '';
        let total = 0;

        cart.forEach(function(item) {
            let lineTotal = item.price * item.qty;
            total += lineTotal;

            html += `
                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-danger remove-item" data-id="${item.id}">×</button>
                        <div><strong>${item.name}</strong><br>₹${item.price}</div>
                        <button type="button" class="btn btn-sm btn-outline-secondary qty-minus" data-id="${item.id}">-</button>
                        <span>${item.qty}</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary qty-plus" data-id="${item.id}">+</button>
                        <span>₹${lineTotal}</span>
                    </div>
                </div>
            `;
        });

        $('#cart-items').html(html);
        $('#cart-total').text(total);
    }

    $(document).on('click', '.qty-plus', function() {
        let id = $(this).data('id');
        let item = cart.find(x => x.id == id);

        if(item) {
            item.qty++;
        }

        renderCart();
    });

    $(document).on('click', '.qty-minus', function() {
        let id = $(this).data('id');
        let item = cart.find(x => x.id == id);

        if(item) {
            item.qty--;
            if(item.qty <= 0) {
                cart = cart.filter(x => x.id != id);
            }
        }
        renderCart();
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