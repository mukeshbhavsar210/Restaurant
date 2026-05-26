@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">
    <div class="card-body">
        <div class="row">                
            <div class="col-sm-8 col-12">
                <div class="page-title"> 
                    <h4>Orders</h4>                           
                    <span class="counts">{{ $totalOrders }}</span>
                </div>
            </div>
            <div class="col-sm-4 col-12 float-end">
                <div class="flexContainer">
                    <form action="" method="get" >
                        <div class="d-flex">
                            <div class="card-title mr-3">
                                <a href="javascript:0" onclick="window.location.href='{{ route('orders.index') }}'" class="refresh-icon" >
                                    <span class="sprites"></span>                                            
                                </button>
                            </div>

                             <div class="card-tools">
                                <div class="input-group input-group searchMain" >
                                    <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
        
                                    <div class="input-group-append">
                                        <button type="submit" class="btn">
                                            <i class="iconoir-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    
        <ul class="nav nav-tabs" role="tablist">
            @php
                $businessTypes = explode(',', $config->business_types);
            @endphp

            @foreach ($businessTypes as $type)
                <li class="nav-item">
                    <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#{{ strtolower($type) }}" role="tab" aria-selected="true">
                        {{ $type }}
                        <span class="badge rounded text-blue bg-blue-subtle">
                            {{ $orders->where('order_type', trim($type))->count() }}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach ($businessTypes as $type)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ strtolower($type) }}">
                    @php
                        $filteredOrders = $orders->where('order_type', $type);
                    @endphp
                
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-top-0">Order#</th>
                                <th class="border-top-0" width="350">Items Notes</th>
                                <th class="border-top-0" width="150">Table/Outlet</th>
                                <th class="border-top-0 text-end" width="80">Total Qty</th>
                                <th class="border-top-0 text-end" width="80">Price</th>
                                <th class="border-top-0 text-end" width="80">Total</th>
                                <th class="border-top-0 text-end" width="180">Order On</th>
                                <th class="border-top-0 text-end" width="80">Status</th>
                            </tr>
                        </thead>                     
                        <tbody>
                            @forelse ($filteredOrders as $value)
                                <tr>
                                    <td>
                                        <div class="product-row">
                                            <div class="img-group d-flex justify-content-end">
                                                @foreach($value->items as $item)
                                                    @php
                                                        $productImage = optional($item->product?->product_images->first());
                                                    @endphp

                                                    <a href="{{ route('orders.detail', $value->id) }}" class="user-avatar position-relative d-inline-block ms-n2">
                                                        @if (!empty($productImage->image))
                                                            <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" class="thumb-md shadow-sm rounded-circle">
                                                        @else
                                                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}" class="thumb-md shadow-sm rounded-circle">
                                                        @endif

                                                        <span class="order-product-qty">{{ $item->quantity }}</span>
                                                    </a>                                                    
                                                @endforeach
                                            </div>                                                                                                                                                                                    
                                        </div>
                                    </td>
                                    <td>         
                                        @foreach($value->items as $item)
                                            <span class="product-title">{{ $item->product_name }},</span>
                                        @endforeach    
                                        <p>{{ $value->notes }}</p>                                        
                                    </td>
                                    <td>
                                        <p class="text-muted"><b>{{ $value->seat?->table_name }}</b> ({{ $value->seat?->capacity }})</p>                                        
                                        <p class="text-muted tiny-font">
                                            {{ $value->seat?->area?->area_name }}<br />
                                            Ready time:
                                            @if($value->dinein_time)
                                                {{ $value->dinein_time }}<br />    
                                            @elseif($value->ready_time)
                                                {{ $value->ready_time }}<br /> 
                                            @endif  
                                        </p>
                                    </td>  
                                    <td class="text-end">{{ $value->items->sum('quantity') }}</td>
                                    <td class="text-end">₹{{ $value->items->sum('price') }}</td>
                                    <td class="text-end">₹{{ round($value->total_amount) }}</td>
                                    <td class="text-end">{{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y, h:i A') }}</td>
                                    <td class="text-end">
                                        @if ($value->status == 'running')
                                            <span class="badge bg-danger">Running</span>
                                        @elseif ($value->status == 'pending')
                                            <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @elseif ($value->status == 'shipped')
                                            <span class="badge bg-info">Shipped</span>
                                        @elseif ($value->status == 'delivered')
                                            <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>                                        
                                </tr>
                            @empty
                                <tr>
                                    <td>                                        
                                        No <b>{{ $type }}</b> orders found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>       
    </div>
</div>
    
@endsection