@extends('front.layouts.app')

@section('content')

    <section class="section-9 pt-4">
        <div class="container">
            <div class="row">

                @if (Session::has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {!! Session::get('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if (Session::has('error'))
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <nav>
                    <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Dinein</button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Takeaway</button>
                        <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Delivery</button>
                    </div>
                </nav>

                @if (Cart::count() > 0)
                    @foreach ($cartContent as $item)
                        <div class="cartWrapper">
                            <div class="row">
                                <div class="col-4">
                                    {{ $item->qty }} x {{ $item->name }}
                                </div>
                                <div class="col-4">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub" data-id="{{ $item->rowId }}">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" class="form-control form-control-sm  border-0 text-center" value="{{ $item->qty }}">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{ $item->rowId }}">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-4">
                                    ₹{{ $item->price }}
                                </div>
                            </div>
                                    
                            ₹{{ $item->price*$item->qty }}
                                {{-- <button class="btn btn-sm btn-danger" onclick="deleteItem('{{ $item->rowId}}' );"><i class="fa fa-times"></i></button> --}}
                        @endforeach
                       
                        {{-- @foreach (Cart::content() as $item)
                            <div class="d-flex justify-content-between pb-2">
                                <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                <div class="h6">₹{{ $item->price*$item->qty }}</div>
                            </div>
                        @endforeach --}}

                        <div class="d-flex justify-content-between summary-end">
                            <div class="h6">Total</div>
                            <div class="h6">₹{{ Cart::subtotal() }}</div>
                        </div>
                        <div class="pt-5">
                            <a href="{{ route('front.checkout') }}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                        </div>

                        <div class="tab-content p-3 " id="nav-tabContent">
                            <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                content 1
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <p>2</p>
                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                <p>3</p>
                            </div>
                        </div>
                    </div>
                @else

                <div class=col-md-12>
                    <div class="card">
                      <div class="card-body d-flex justify-content-center align-item-center">
                        <h5 class="card-title">Your cart is empty!</h5>
                      </div>
                    </div>
                </div>
                

                @endif
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $('.add').click(function(){
            var qtyElement = $(this).parent().prev(); // Qty Input
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue < 10) {
                qtyElement.val(qtyValue+1);

                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId,newQty)
            }
        });

        $('.sub').click(function(){
            var qtyElement = $(this).parent().next();
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue-1);

                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId,newQty)
            }
        });

        function updateCart(rowId,qty){
            $.ajax({
                url: '{{ route("front.updateCart") }}',
                type: 'post',
                data: {rowId:rowId, qty:qty},
                dataType: 'json',
                success: function(response){
                    window.location.href='{{ route("front.cart") }}';
                }
            })
        }

        function deleteItem(rowId){
            if(confirm("Are you sure you want to delete?")){
                $.ajax({
                    url: '{{ route("front.deleteItem.cart") }}',
                    type: 'post',
                    data: {rowId:rowId},
                    dataType: 'json',
                    success: function(response){
                        window.location.href='{{ route("front.cart") }}';
                    }
                })
            }
        }
    </script>
@endsection
