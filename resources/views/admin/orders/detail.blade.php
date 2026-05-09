@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">
    <div class="card-body">
        <div class="row">                
            <div class="col-md-10 col-8">
                <div class="page-title"> 
                    <h4>Order: {{ $order->id }}</h4>
                </div>
            </div>            
            <div class="col-md-2 col-2">
                <a href="{{ route('orders.index') }}" class="btn btn-primary pull-right">Back</a>
            </div>            
        </div>
    </div>
</div>


        @php
            $subtotal = 0;
            $gstAmount = 0;
            $sgstAmount = 0;
            $cgstAmount = 0;                        

            foreach ($order->items as $item) {
                $itemTotal = $item->price * $item->quantity;
                $subtotal += $itemTotal;
                $gstAmount += ($itemTotal * $order->gst) / 100;
                $sgstAmount += ($itemTotal * $order->sgst) / 100;
                $cgstAmount += ($itemTotal * $order->cgst) / 100;
            }

            $shipping = ($order->order_type === 'delivery') ? $order->shipping : 0;

            $grandTotal = $subtotal + $gstAmount + $sgstAmount + $cgstAmount + $shipping;
        @endphp

    <div class="row">        
        <div class="col-md-9">     
            <div class="card">
                <div class="card-body">       
                    <div class="row invoice-info">
                        <div class="col-sm-8 invoice-col">                            
                            @php
                                $type = strtolower($order->order_type);
                            @endphp

                            <h1 class="h5 mb-2">Order Type: {{ ucfirst($type) }}</h1>
                            {{-- Dine-in specific --}}
                            @if($type === 'dinein')
                                <p class="mb-0">{{ $order->seat?->table_name }}</p>
                                <p class="mb-0">Seating Capacity: {{ $order->seat?->capacity }}</p>
                                <p class="mb-0">{{ $order->seat?->area?->area_name }}</p>
                            @endif

                            {{-- Customer info (Takeaway + Delivery) --}}
                            @if(in_array($type, ['takeaway', 'delivery']))
                                <address class="mb-0">
                                    <p>
                                        <b>{{ $order->customer_name }}</b><br />

                                        {{-- Address only for delivery --}}
                                        @if($type === 'delivery')
                                            {{ $order->address }}<br />
                                        @endif

                                        Phone: {{ $order->customer_phone }}<br />
                                        Email: {{ $order->customer_email }}
                                    </p>
                                </address>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-6 text-right">Invoice</div>
                                <div class="col-sm-6">: #000{{ $order->id }}</div>
                            </div>                    
                            <div class="row">
                                <div class="col-sm-6 text-right">Total</div>
                                <div class="col-sm-6">: ₹{{ round($grandTotal) }}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 text-right">Status</div>
                                <div class="col-sm-6">:
                                    @if ($order->status == 'running')
                                        <span class="badge bg-danger">Running</span>
                                    @elseif ($order->status == 'pending')
                                        <span class="badge bg-danger">Pending</span>
                                    @elseif ($order->status == 'shipped')
                                        <span class="badge bg-info">Shipped</span>
                                    @elseif ($order->status == 'delivered')
                                        <span class="badge bg-success">Delivered</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 text-right">Shipped Date</div>
                                <div class="col-sm-6">:                            
                                    @if (!empty($order->shipped_date))
                                        {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, y')}}
                                    @else
                                        n/a
                                    @endif                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-top-0">Product</th>                            
                                <th class="border-top-0 text-end" width="30">Qty</th>
                                <th class="border-top-0 text-end" width="100">Price</th>
                                <th class="border-top-0 text-end" width="100">Total</th>  
                            </tr>
                        </thead>                     
                        <tbody>  
                            @foreach ($orderItems as $value)
                                <tr>                            
                                    <td>
                                        @php
                                            $productImage = optional($value->product?->product_images->first());
                                        @endphp
                                        
                                        @if (!empty($productImage->image))
                                            <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" height="60" class="me-2 rounded">
                                        @else
                                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}" height="60" class="me-2 rounded">
                                        @endif
                                        
                                        {{ $value->product_name }}
                                    </td>
                                    <td class="text-end">{{ $value->quantity }}</td>
                                    <td class="text-end">₹{{ round($value->price) }}</td>                            
                                    <td class="text-end">₹{{ round($value->price * $value->quantity) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end"><b>Subtotal</b></td>
                                <td class="text-end">₹{{ round($subtotal) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">GST ({{ $order->gst }}%)</td>
                                <td class="text-end">₹{{ round($gstAmount) }}</td>
                            </tr>                    
                            <tr>
                                <td colspan="3" class="text-end">SCGT ({{ $order->sgst }}%):</td>
                                <td class="text-end">₹{{ round($sgstAmount) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">CGST ({{ $order->cgst }}%):</td>
                                <td class="text-end">₹{{ round($cgstAmount) }}</td>
                            </tr>
                            @if($order->order_type === 'delivery')
                                <tr>
                                    <td colspan="3" class="text-end">Shipping</td>
                                    <td class="text-end">₹{{ $order->shipping }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end"><b>Grand Total</b></td>
                                <td class="text-end"><b>₹{{ round($grandTotal) }}</b></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">1</td>                        
                            </tr>                    
                        </tbody>
                    </table>                   
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body"> 
                    <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                        <div class="form-group">
                            <label for="shipped_date">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="available" {{ ($order->status == 'available') ? 'selected' : ''}}>Available</option>
                                <option value="running" {{ ($order->status == 'running') ? 'selected' : ''}}>Running</option>
                                <option value="pending" {{ ($order->status == 'pending') ? 'selected' : ''}}>Pending</option>
                                <option value="shipped" {{ ($order->status == 'shipped') ? 'selected' : ''}}>Shipped</option>
                                <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : ''}}>Delivered</option>
                                <option value="cancelled" {{ ($order->status == 'cancelled') ? 'selected' : ''}}>Cancelled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="shipped_date">Date</label>
                            <input placeholder="Shipped Date" autocomplete="off" value="{{ $order->shipped_date }}" type="date" name="shipped_date" id="shipped_date" class="form-control">
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        // $(document).ready(function(){
        //     $('#shipped_date').datetimepicker({
        //         format:'Y-m-d H:i:s',
        //     });
        // });

        $("#changeOrderStatusForm").submit(function(event){
            event.preventDefault();
            var element = $(this);

            if (confirm("Are you sure you want to change status?")){
                $.ajax({
                    url: '{{ route("orders.changeOrderStatus",$order->id) }}',
                    type: 'post',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        window.location.href='{{ route("orders.detail",$order->id ) }}';
                    }
                });
            }
        });

        $("#sendInvoiceEmail").submit(function(event){
            event.preventDefault();
            var element = $(this);

            if (confirm("Are you sure you want to send email?")){
                $.ajax({
                    url: '{{ route("orders.sendInvoiceEmail",$order->id) }}',
                    type: 'post',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        window.location.href='{{ route("orders.detail",$order->id ) }}';
                    }
                });
            }
        });
    </script>
@endsection
