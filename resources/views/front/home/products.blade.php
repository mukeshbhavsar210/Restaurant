<div class="menu-grid">
    @if(!empty($products))
        @foreach($products as $value)
            <div class="product-home">
                <div class="product-image">
                    <a onclick="addToWishlist({{ $value->id }})" class="whishlist" href="javascript:void(0)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#222222" stroke-width="1.2px" x="0px" y="0px" viewBox="-1 -2 14 13" xml:space="preserve"><path d="M11,1c-0.6-0.6-1.5-1-2.3-1C7.8,0,7,0.4,6.3,1L6,1.3L5.7,1C5,0.3,4.2,0,3.3,0S1.6,0.3,1,1C0.3,1.6,0,2.4,0,3.3S0.3,5,1,5.7
                            l4.8,4.8C5.9,10.6,6,10.6,6,10.6c0.1,0,0.2,0,0.2-0.1L11,5.7c0.6-0.6,1-1.5,1-2.4S11.7,1.6,11,1z"></path></svg>
                    </a>

                    <a href="javascript:void(0);" class="mainPic" data-toggle="modal" data-target="#{{ $value->id }}" >
                        @if (!empty($value->image))
                            <img src="{{ asset('uploads/product/'.$value->image) }}" >
                        @else
                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" />
                        @endif
                    </a>
                </div>

                <div class="product-title">
                    <div class="row">
                        <div class="col-7">
                            {{ $value->name }}
                        </div>
                        <div class="col-5">
                            <div class="right">
                                ₹ {{ $value->price }}
                                @if ($value->discounted_price > 0)
                                    <span class="text-underline"><del>₹ {{ $value->discounted_price }}</del></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="{{ $value->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="menuContainer">
                            <div class="product-pic">
                                @if (!empty($value->image))
                                    <img class="card-img-top" src="{{ asset('uploads/product/'.$value->image) }}" >
                                @else
                                    <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" />
                                @endif
                            </div>
                            <div class="btnControl">
                                <div class="row p-3">
                                    <div class="col-9">
                                        <a type="button" class="link" data-bs-dismiss="modal" aria-label="Close">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"></path></svg>
                                        </a>
                                    </div>
                                    <div class="col-3">
                                        <a href="#" class="link right" >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-suit-heart-fill" viewBox="0 0 16 16"><path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"></path></svg>
                                        </a>
                                    </div>
                                </div> 
                            </div>
                            <div class="product-title">
                                <div class="row">
                                    <div class="col-9">
                                        <h3>{{ $value->name }}</h3>
                                    </div>
                                    <div class="col-3">
                                        <div class="product-price">
                                            <span class="price"> ₹{{ $value->price }}</span>
                                            @if ($value->discounted_price > 0)
                                                <span class="price text-secondary"><del> ₹{{ $value->discounted_price }}</del></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-details">
                                <a href="javascript:void(0);" data-product-id="{{ $value->id }}" id="add-cart-btn-{{ $value->id }}" class="add-to-cart-button product-add">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"></path></svg>
                                </a>
                                <span id="adding-cart-{{ $value->id }}" style="display:none;">Added.</span>
                                <p>{{ \Illuminate\Support\Str::limit(strtolower($value->description), 50) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>