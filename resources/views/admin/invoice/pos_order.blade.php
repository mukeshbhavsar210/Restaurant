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

<div class="card">
    <div class="card-body mobile-padd p-0">
        <div class="row">
            <div class="col-md-8 col-12 grey-back">                
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
                                    <div class="product-grid">
                                        @foreach($category->products as $product)                                                                                
                                            @php
                                                $type = $product->menu?->veg_nonveg;   
                                                $cart = session('cart', []);
                                                $cartKey = $product->id . '_default';
                                                $qty = $cart[$cartKey]['quantity'] ?? 0;                                  
                                                $cartProductIds = collect(session('kot_cart', []))->pluck('product_id')->toArray();
                                            @endphp                                            

                                            @if($qty > 0)
                                                {{-- <a href="{{ route('admin.cart.add', $product->id) }}" class="product-card {{ in_array($product->id, $cartProductIds) ? 'added-cart' : '' }}">
                                                    <div class="product-name">{{ $product->name }} </div>
                                                </a> --}}
                                            
                                                {{-- <a href="#" class="product-card control-btn " data-id="{{ $product->id }}">
                                                    <div class="line {{ $type == 'Non-veg' ? 'non-veg-card' : ($type == 'Egg' ? 'egg-veg-card' : ($type == 'Veg' ? 'veg-card' : '')) }}"></div>
                                                    <div class="product-name">{{ $product->name }}</div>
                                                    <div class="added">
                                                        <span class="sprites green-tick-icon"></span>
                                                        <a href="#" class="clear-icon">
                                                            <span class="sprites"></span>
                                                        </a>
                                                    </div>
                                                </a>                                             --}}
                                            @else
                                               @php
                                                    $productImage = $product->product_images->first();                                                        
                                                @endphp

                                                <a href="{{ route('admin.kot', $product->id) }}" class="product-card {{ in_array($product->id, $cartProductIds) ? 'added' : '' }}">                                                    
                                                    <div class="details">
                                                        <div class="photo {{ $type == 'Non-veg' ? 'non-veg-card' : ($type == 'Egg' ? 'egg-veg-card' : ($type == 'Veg' ? 'veg-card' : '')) }}">
                                                            @if (!empty($productImage->image))                                                            
                                                                <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" alt="{{ $product->name }}" class="rounded-circle" />                                                            
                                                            @else
                                                                <img src="{{ asset('admin-assets/images/default-150x150.png') }}" alt="{{ $product->name }}" class="rounded-circle" />
                                                            @endif
                                                        </div>
                                                        
                                                        <span class="product-name">{{ \Illuminate\Support\Str::limit($product->name, 12) }}</span>                                                                                                            
                                                    </div>
                                                </a>                                                
                                            @endif                                            
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-md-4 col-12">
                @php
                    $total = 0;
                    $deliveryFee = 50;
                    $id = $product->id;    
                    $productImage = $product->product_images->first();    
                    $kot_cart = session('kot_cart', []);
                    foreach($kot_cart as $item){
                        $total += $item['price'] * $item['quantity'];
                    }
                @endphp
               
                <ul class="custom-tabs mt-2">
                    @foreach ($businessTypes as $type)
                        <li class="tab-link {{ $loop->first ? 'active' : '' }}" data-tab="tab{{ $loop->iteration }}" data-type="{{ $type }}">
                            {{ trim($type) }}
                        </li>
                    @endforeach
                </ul>  

                <form method="POST" action="{{ route('submit.order') }}">
                    @csrf  
                        @if(getAdminCartCount() > 0)                                                              
                        
                        <div class="scroll-order">                            
                            @foreach($kot_cart as $id => $item)
                                @php
                                    $qty = $item['quantity'];
                                @endphp                                
                                <div class="cart-row cart-{{ $id }}" >
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="flex-3">
                                                <a href="{{ route('kot.cart.removecart', $id) }}" class="remove-icon">
                                                    <span class="sprites"></span>
                                                </a>

                                                {{ $item['name'] }}
                                                {{-- @if(!empty($item['variant']))
                                                    ({{ $item['variant'] }})
                                                @endif --}}   
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="calculate">                                                                                                                                        
                                                <div class="qty align-items-center">
                                                    <a href="javascript:0" class="qty-decrease-small" data-id="{{ $id }}" data-seat="{{ $item['seat_id'] }}">
                                                        <span class="sprites"></span>
                                                    </a>                                                    

                                                    <span class="manage-qty manage-qty-{{ $id }}">{{ $qty }}</span>
                    
                                                    <a href="javascript:0" class="qty-increase-small" data-id="{{ $id }}" data-seat="{{ $item['seat_id'] }}">
                                                        <span class="sprites"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="price">₹{{ round($item['price']) }}</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="variant_name" class="variant_name" value="{{ $item['variant'] }}">
                                    {{-- <input type="text" name="variant_price" class="variant_price" value="{{ $item['price'] }}"> --}}
                                </div>
                            @endforeach
                        
                                                                                                            
                        @if($tab1)
                            <div class="tab-content-custom {{ $tab1 }} {{ $tab1 ? 'active' : '' }}">
                                <input type="hidden" name="seat_id" id="seat_id" value="{{ session('seat_id') }}" class="form-control">
                                <input type="hidden" name="who" value="admin" class="form-control">
                            </div>
                        @endif
                        
                        @if($tab2)
                            <div class="tab-content-custom {{ $tab2 }} {{ !$tab1 && $tab2 ? 'active' : '' }}">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-2">                                                                    
                                            <input type="text" class="form-control field" data-name="name" name="name" placeholder="Name..." >
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-2">
                                            <input type="email" class="form-control field" data-name="email" name="email" placeholder="Email..." >
                                        </div>           
                                    </div>
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
                        </div>                                             
                    @else                    
                        <div class="emptyBag">                        
                            <h5>No item Selected</h5>
                            <p class="font-13">Please select item from left Menu items</p>
                        </div>
                    @endif 

                    <div class="total-wrapper">
                        <div class="row-total">
                            <div>
                                @if(session()->has('kot_cart') && count(session('kot_cart')) > 0)
                                    <a href="{{ route('cart.kot') }}" class="btn btn-danger">R</a>
                                @endif
                            </div>
                            <div>
                                <h5>Total: <b><span class="cart-total grandTotal"></span></b></h5>                                
                                <input type="hidden" name="total" id="baseTotal" value="{{ $total }}">
                                <input type="hidden" name="order_type" id="order_type" value="{{ $tab1 }}" class="form-control">
                            </div>
                        </div>                    
                        <div class="row-bottom">
                            <button class="btn btn-primary w-100 mt-2">Save</button>
                            <a href="#" class="btn btn-primary">Save & Print</a>
                            <a href="#" class="btn btn-primary">Save & eBill</a>
                            <a href="#" class="btn btn-secondary">KOT</a>
                            <a href="#" class="btn btn-secondary">KOT & Print</a>
                            {{-- <a href="#" class="btn btn-outline-secondary">Hold</a> --}}                            
                        </div>
                    </div>
                </form>                                
            </div>
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
        alert("Hi");
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