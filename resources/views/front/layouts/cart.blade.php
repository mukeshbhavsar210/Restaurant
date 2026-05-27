<div id="bottomSheet" class="bottom-sheet" data-product="">
    <div class="sheet-content">
        <div class="handle">                       
            @if($qty > 0)                
                <div>
                    <span class="cart-count">{{ getCartCount() }}</span> for 
                    <span class="cart-total grandTotal"></span>
                </div>
                <div class="tab-content dinein active">
                    <span class="sprites tab1_icon"></span>
                </div>
                <div class="tab-content takeaway">
                    <span class="sprites tab2_icon"></span>
                </div>
                <div class="tab-content delivery">
                    <span class="sprites tab3_icon"></span>
                </div>
            @else
                <span class="manage-qty">Order</span> 
            @endif   
        </div>
                               
            @if($qty > 0)
                <ul class="custom-tabs">
                    @php
                        $businessTypes = explode(',', $config->business_types);
                    @endphp

                    @foreach ($businessTypes as $type)
                        @php
                            $typeValue = strtolower(trim($type));
                        @endphp

                        <li class="tab-link {{ $loop->first ? 'active' : '' }}" data-tab="tab{{ $loop->iteration }}" data-type="{{ strtolower(trim($type)) }}">
                            {{ trim($type) }}
                        </li>
                    @endforeach
                </ul>             

                <form id="placeOrder" name="placeOrder" method="POST" action="{{ route('submit.order') }}">
                    @csrf
                    <div class="scroll-order">
                        <div class="basket-page__content__products">
                            @foreach(session('cart') as $id => $value)       
                                @php
                                    $qty = $value['quantity'];
                                @endphp

                                <div class="cart-row cart-{{ $id }}">                                
                                    <div class="item-name">
                                        <span class="manage-qty manage-qty-{{ $id }}">{{ $qty }}</span> x {{ $value['name'] }}
                                        @if(!empty($value['variant_name']))                                                        
                                            ({{ $value['variant_name'] }})
                                        @endif
                                    </div>
                                    <div class="calculate">
                                        <div class="flex-inner">
                                            @if($qty > 0)
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
                                                <p class="item-name">₹{{ round($value['price']) }}</p>
                                            </div>     
                                        </div>
                                    </div>
                                </div>
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

                        <div class="basket-page__content__notes mb-2">
                            <textarea name="notes" placeholder="Add note 🙏🏻..." ></textarea>
                        </div>

                        @foreach ($businessTypes as $type)                        
                            @if($type == 'Dinein')
                                <div class="tab-content dinein active">
                                    <div class="basket-page__content__delivery-content mb-3">
                                        <select name="seat_id" id="seat_id" class="form-select mb-3">
                                            <option value="">Table</option>
                                            @foreach ($seats as $value)
                                                {{-- @if(empty($value->area_id))
                                                    <option value="{{ $value->id }}">
                                                        {{ $value->table_name }}
                                                    </option>
                                                @endif --}}
                                                @if($value->area_id == NULL)
                                                    <option value="{{ $value->id }}">{{ $value->table_name }}</option>
                                                @elseif($value->area_id == '')
                                                    <option value="{{ $value->id }}">{{ $value->table_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @elseif($type == 'Takeaway')
                                <div class="tab-content takeaway">                                    
                                    <div class="form-group mb-2">
                                        <input type="text" class="form-control" placeholder="Name" name="customer_name">
                                    </div>
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="form-group mb-2">
                                                <input type="email" class="form-control" placeholder="Email" name="customer_email">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="form-group mb-2">
                                                <input type="phone" class="form-control" placeholder="Phone" name="customer_phone">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            @elseif($type == 'Delivery')
                                <div class="tab-content delivery">
                                    <div class="basket-page__content__delivery-content mb-2" >
                                        <textarea class="form-control" name="delivery_address" placeholder="Enter address"></textarea>
                                    </div>

                                    <div class="form-group mb-2">
                                        <input type="text" class="form-control" placeholder="Customer Name" name="delivery_name">
                                    </div>

                                    <div class="row">
                                        <div class="col-7">
                                            <div class="form-group mb-2">
                                                <input type="email" class="form-control" placeholder="Email" name="delivery_email">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="form-group mb-2">
                                                <input type="phone" class="form-control" placeholder="Phone" name="delivery_phone">
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            @endif
                        @endforeach

                        @php
                            $businessTypes = explode(',', $config->business_types);
                            $defaultType = strtolower(trim($businessTypes[0]));
                        @endphp

                        <input type="hidden" name="order_type" id="order_type" value="{{ $defaultType }}" class="form-control">
                        <input type="hidden" name="total_amount" value="{{ $total }}" class="form-control">
                    
                        <p class="validation mt-2">Fill all required fields</p>
                        <div class="basket-page__content__terms">By clicking Order, you confirm your age is 18+ and you agree to the <a href="https://instalacarte.com/page/privacy-policy" target="_blank">terms</a></div>

                        <input type="hidden" name="variant_name" id="variant_name" value="{{ $variants->first()->name ?? '' }}">
                        <input type="hidden" name="variant_price" id="variant_price" value="{{ $variants->first()->price ?? $product->price }}">
                    </div>

                    <div class="button-padd">
                        <button class="btn btn-primary w-100 mt-3 orderBtn">Order</button>
                    </div>
                </form>                
            @else                    
                <div class="emptyBag">
                    <img src="{{ asset('front-assets/images/empty_bag.png') }}" alt="empty bag" />
                    <p>Nothing to order</p>
                </div>
            @endif        
        </div>

    <div class="sheet-overlay"></div>
</div>