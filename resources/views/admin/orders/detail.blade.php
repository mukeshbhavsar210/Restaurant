@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

@php
    $shipping = ($order->order_type === 'delivery') ? $order->shipping : 0;
    $grandTotal = $subtotal + $gstAmount + $sgstAmount + $cgstAmount + $shipping;
    $type = strtolower($order->order_type);
@endphp

    <div class="row">        
        <div class="col-md-9">     
            <div class="card">
                <div class="card-body">
                    <div class="row invoice-info">
                        <div class="col-md-8 invoice-col"> 
                            <h4 class="mb-2">Order Details - {{ $order->id }}</h4>                             
                            {{-- Dine-in specific --}}
                            @if($type === 'dinein')                                
                                <h5 class="mb-1">{{ $order->seat?->table_name }}</h5>
                                <p class="mb-0">{{ $order->seat?->area?->area_name }}</p>
                            @endif                            

                            {{-- Customer info (Takeaway + Delivery) --}}
                            @if(in_array($type, ['takeaway', 'delivery']))
                                <address class="mb-0">
                                    <p>
                                        <b>{{ $order->customer_name }}</b><br />
                                        @if($type === 'delivery')
                                            {{ $order->address }}<br />
                                        @endif

                                        Phone: {{ $order->customer_phone }}<br />
                                        Email: {{ $order->customer_email }}
                                    </p>
                                </address>
                            @endif
                        </div>
                        <div class="col-md-4">                            
                            <div class="row mb-1">
                                <div class="col-md-4 text-right">Order Type</div>
                                <div class="col-md-8">: <p class="types-restaurant border border-primary text-primary">{{ ucfirst($type) }} Order</p></div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-4 text-right">Status</div>
                                <div class="col-md-8">:
                                    @if($type === 'dinein')
                                        <span class="badge {{ $order->status == 'running' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $order->status == 'running' ? 'Running' : 'Available' }}
                                        </span>
                                    @elseif ($type == 'takeaway' || $type == 'delivered')
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
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-right">Total</div>
                                <div class="col-md-8">: <b>₹{{ round($grandTotal) }}</b></div>
                            </div>                            
                            @if($type === 'dinein')
                                <div class="row">
                                    <div class="col-md-4 text-right">Order On</div>
                                    <div class="col-md-8">: {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y, h:i A') }}</div>
                                </div>
                            @elseif ($type == 'takeaway' || $type == 'delivered')
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
                        </tbody>
                    </table>                   
                </div>
            </div>
        </div>

        <div class="col-md-3">            
            <div class="card">
                <div class="card-body"> 
                    <a href="{{ route('orders.index') }}" class="btn btn-primary ">Back</a><br /><br />

                    <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                        @if($type === 'dinein')
                            <div class="form-group">
                                <label for="shipped_date">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="available" {{ ($order->status == 'available') ? 'selected' : ''}}>Available</option>
                                    <option value="running" {{ ($order->status == 'running') ? 'selected' : ''}}>Running</option>                                    
                                </select>
                            </div>
                        @elseif ($type == 'takeawat' || $type == 'delivered')
                            <div class="form-group">
                                <label for="shipped_date">Status</label>
                                <select name="status" id="status" class="form-select">                                    
                                    <option value="pending" {{ ($order->status == 'pending') ? 'selected' : ''}}>Pending</option>
                                    <option value="shipped" {{ ($order->status == 'shipped') ? 'selected' : ''}}>Shipped</option>
                                    <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : ''}}>Delivered</option>
                                    <option value="cancelled" {{ ($order->status == 'cancelled') ? 'selected' : ''}}>Cancelled</option>
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
