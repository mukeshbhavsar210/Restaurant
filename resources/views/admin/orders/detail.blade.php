@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

@php
    //$shipping = ($order->order_type === 'Delivery') ? $order->shipping : 50;
    $dinein = $order->order_type === 'Dinein';
    $takeaway = $order->order_type === 'Takeaway';
    $delivery = $order->order_type === 'Delivery';
    $placed = $order->status == 'placed';
    $running = $order->status == 'running';
    $pending = $order->status == 'pending';
    $shipped = $order->status == 'shipped';
    $delivered = $order->status == 'delivered';
    $cancelled = $order->status == 'cancelled';
    $available = $order->status == 'available';    
@endphp

    <div class="row">        
        <div class="col-md-9">     
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="flex">
                            <a href="{{ route('orders.index') }}" class="back-arrow">
                                <span class="sprites"></span>
                            </a>
                            <h4 class="mb-2">Order Details - {{ $order->id }}</h4>                            
                        </div>

                        <div class="col-md-8">
                            <div class="padd-invoice">
                                @if($dinein)
                                    <h5 class="mb-1">{{ $order->seat?->table_name }}</h5>
                                    <p class="mb-0">{{ $order->seat?->area?->area_name }}</p>
                                @elseif($takeaway || $delivery)
                                    <address>
                                        <b>{{ $order->name }}</b><br />
                                        {{ $order->address }}<br />
                                        Phone: {{ $order->phone }}<br />
                                        Email: {{ $order->email }}                                      
                                    </address>                                
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">                            
                            <div class="row mb-1">
                                <div class="col-md-4 text-right">Order Type</div>
                                <div class="col-md-8">: <p class="types-restaurant border border-primary text-primary">{{ $order->order_type }}</p></div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-4 text-right">Status</div>
                                <div class="col-md-8">:
                                    @if($dinein)
                                        <span class="badge {{ $running ? 'bg-danger' : 'bg-success' }}">
                                            {{ $running ? 'Running' : 'Available' }}
                                        </span>
                                    @elseif ($takeaway || $delivery)
                                        @if ($placed)
                                            <span class="badge bg-success">Placed Order</span>                                        
                                        @elseif ($shipped)
                                            <span class="badge bg-info">Shipped</span>
                                        @elseif ($delivered)
                                            <span class="badge bg-success">Delivered</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif                                    
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-right">Total</div>
                                <div class="col-md-8">: <b>₹{{ round($grandTotal) }}</b></div>
                            </div>                            
                            @if($dinein)
                                <div class="row">
                                    <div class="col-md-4 text-right">Order On</div>
                                    <div class="col-md-8">: {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y, h:i A') }}</div>
                                </div>
                            @elseif ($takeaway || $delivery)
                                <div class="row">                                
                                    <div class="col-md-4 text-right">Shipped Date</div>
                                    <div class="col-md-8">:                            
                                        @if (!empty($order->shipped_date))
                                            {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, y')}}
                                        @else
                                            n/a
                                        @endif                            
                                    </div>
                                </div>
                            @endif                            
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
                                        
                                        <a href="{{ route('front.menu', [$value->product->category->slug, $value->product->slug]) }}" target="_blank">
                                            @if (!empty($productImage->image))
                                                <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" height="60" class="me-2 rounded">
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" height="60" class="me-2 rounded">
                                            @endif
                                            
                                            {{ $value->product_name }}
                                        </a>
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
                                <td colspan="3" class="text-end">GST ({{ $config->gst }}%)</td>
                                <td class="text-end">₹{{ round($gstAmount) }}</td>
                            </tr>                    
                            <tr>
                                <td colspan="3" class="text-end">SCGT ({{ $config->sgst }}%):</td>
                                <td class="text-end">₹{{ round($sgstAmount) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">CGST ({{ $config->cgst }}%):</td>
                                <td class="text-end">₹{{ round($cgstAmount) }}</td>
                            </tr>
                            @if($delivery)
                                <tr>
                                    <td colspan="3" class="text-end">Shipping:</td>
                                    <td class="text-end">₹{{ $config->shipping }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end"><b>Grand Total</b></td>
                                <td class="text-end"><b>₹{{ round($grandTotal) }}</b></td>
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
                        @if($dinein)
                            <div class="form-group">
                                <label for="shipped_date">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="available" {{ ($available) ? 'selected' : ''}}>Available</option>
                                    <option value="running" {{ ($running) ? 'selected' : ''}}>Running</option>                                    
                                </select>
                            </div>
                        @elseif ($takeaway || $delivery)
                            <div class="form-group">
                                <label for="shipped_date">Status</label>
                                <select name="status" id="status" class="form-select">                                    
                                    <option value="pending" {{ ($pending) ? 'selected' : ''}}>Pending</option>
                                    <option value="shipped" {{ ($shipped) ? 'selected' : ''}}>Shipped</option>
                                    <option value="delivered" {{ ($delivered) ? 'selected' : ''}}>Delivered</option>
                                    <option value="cancelled" {{ ($cancelled) ? 'selected' : ''}}>Cancelled</option>
                                </select>
                            </div>
                        @endif  

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
