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
@endphp

<div class="modal-cart">
    <div class="bottom-sheet">
        <div class="sheet-handle">
            @php
                $businessTypes = explode(',', $config->business_types);                    
                $tab1 = $businessTypes[0] ?? null;
                $tab2 = $businessTypes[1] ?? null;
                $tab3 = $businessTypes[2] ?? null;
                $icons = [ 'tab1_icon', 'tab2_icon', 'tab3_icon'];
            @endphp
                            
            @if(getCartCount() > 0)
                <div>
                    <span class="cart-count">{{ getCartCount() }} </span> for 
                    <span class="cart-total">₹{{ getCartTotal() }}</span>
                    {{-- <span class="cart-total grandTotal"></span> --}}
                </div>                
                @foreach($businessTypes as $index => $type)
                    <div class="tab-content {{ $type }} {{ $index == 0 ? 'active' : '' }}">
                        <span class="sprites {{ $icons[$index] ?? '' }}"></span>
                    </div>
                @endforeach
            @else
                <span class="manage-qty">Order</span> 
            @endif
        </div>

        <div class="sheet-content">
            @if(getCartCount() > 0)
                <ul class="custom-tabs">
                    @foreach ($businessTypes as $type)
                        <li class="tab-link {{ $loop->first ? 'active' : '' }}" data-tab="tab{{ $loop->iteration }}" data-type="{{ $type }}">
                            {{ trim($type) }}
                        </li>
                    @endforeach
                </ul>             

                <form method="POST" action="{{ route('submit.order') }}">
                    @csrf
                    
                    <div class="scroll-order">
                        <div class="basket-page__content__products">
                            @foreach(session('cart', []) as $id => $item)    
                                @php
                                    $qty = $item['quantity'];
                                @endphp

                                <div class="cart-row cart-{{ $id }}">                                
                                    <div class="item-name">
                                        <span class="manage-qty manage-qty-{{ $id }}">{{ $qty }}</span> x {{ $item['name'] }} 
                                        @if(!empty($item['variant']))
                                            ({{ $item['variant'] }})
                                        @endif
                                    </div>
                                    <div class="calculate">
                                        <div class="flex-inner">
                                            @if(getCartCount() > 0)
                                                <div class="qty-box flex align-items-center">  
                                                    <a href="javascript:0" class="sub-icon sub-icon-control-{{ $id }} {{ $qty <= 1 ? 'qty-remove' : 'qty-decrease' }}" data-id="{{ $id }}">
                                                        <span class="sprites"></span>
                                                    </a>
                    
                                                    <a href="javascript:0" class="add-icon qty-increase" data-id="{{ $id }}">
                                                        <span class="sprites"></span>
                                                    </a>
                                                </div>
                                            @else
                                                <a href="javascript:0" class="add-to-cart add-icon qty-increase" data-id="{{ $id }}">
                                                    <span class="sprites"></span>
                                                </a>
                                            @endif                                                                
                                            <div class="right">
                                                <p class="item-name">₹{{ round($item['price']) }}</p>
                                            </div>     
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="variant_name" class="variant_name" value="{{ $item['variant'] }}">
                                {{-- <input type="text" name="variant_price" class="variant_price" value="{{ $item['price'] }}"> --}}
                            @endforeach
                        </div> 
            
                        <div class="basket-page__content__total">
                            <p>Total:</p>                        
                            <input type="hidden" id="baseTotal" value="{{ $total }}">                        
                            <span class="cart-total grandTotal">₹0</span>
                        </div>

                        <div class="basket-page__content__delivery">
                            <p id="deliveryFeeText" style="display:none;">+ Delivery fee ₹50</p>                        
                        </div>
                        
                        <div class="basket-page__content__notes mb-2 mt-4">
                            <textarea name="notes" placeholder="Add note 🙏🏻..." ></textarea>
                        </div>    
                        
                        <div class="basket-page__content__notes mb-2">
                            <input type="phone" class="form-control field" data-name="phone" name="phone" placeholder="Mobile...">
                        </div>

                        @if($tab1)
                            <div class="tab-content {{ $tab1 }} {{ $tab1 ? 'active' : '' }}">
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
                            </div>
                        @endif
                        
                        @if($tab2)
                            <div class="tab-content {{ $tab2 }} {{ !$tab1 && $tab2 ? 'active' : '' }}">
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
                            <div class="tab-content {{ $tab3 }} {{ !$tab1 && !$tab2 && $tab3 ? 'active' : '' }}">
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
                    
                        <input type="hidden" name="order_type" id="order_type" value="{{ $tab1 }}" class="form-control">
                        <input type="hidden" name="total" value="{{ $total }}" class="form-control">
                        <input type="hidden" name="who" value="customer" class="form-control">
                                                                
                        <div class="basket-page__content__terms">
                            <p class="validation">Fill all required fields</p>
                            <p>By clicking Order, you confirm your age is 18+ and you agree to the <a href="https://instalacarte.com/page/privacy-policy" target="_blank">terms</a></p>
                        </div>
                    </div>

                    <div class="basket-order-button-container">                    
                        <button class="btn btn--brand basket-page__content__order-btn basket-page__content__order-btn--disabled">Order</button>
                    </div>
                </form>                
            @else                    
                <div class="emptyBag">
                    <img src="{{ asset('front-assets/images/empty_bag.png') }}" alt="empty bag" />
                    <p>Nothing to order</p>
                </div>
            @endif  
        </div>
    </div>

    @include('front/layouts/message')
    
<div class="sheet-overlay"></div>
</div>

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