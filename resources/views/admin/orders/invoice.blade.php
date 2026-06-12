<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 5px; }

        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .invoice { width: 90%; margin: 0 auto; padding: 10px; }
        .text-center { text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 3px 0; font-size: 11px; }
        h3 { font-size: 20px; margin-bottom: 5px;}
        p { margin: 3px 0; padding: 0;}
        .mt-10 { margin-top: 15px;}
        .mb-10 { margin-bottom: 10px; }
        .padding { padding:8px 0; }
        .border-top { border-top: 1px dashed #000; }
        .border-bottom { border-bottom: 1px dashed #000; margin: 10px 0; }
        .total { font-size: 13px; font-weight: bold; }
        .flex { display: flex; justify-content:space-between; }
    </style>
</head>
<body>

@php
    $subtotal = $order->total;
    $shipping = $config->shipping;    
    $gstAmount = ($subtotal * $config->gst) / 100;
    $sgstAmount = ($subtotal * $config->sgst) / 100;
    $cgstAmount = ($subtotal * $config->cgst) / 100;
    $grandTotal = $subtotal + $gstAmount + $sgstAmount + $cgstAmount;
@endphp

<div class="invoice">
    <div class="text-center mb-10">
        @if(!empty($config->upi))
            <img src="{{ public_path('uploads/logo/'.$config->upi) }}" style="width:110px;">
        @endif
        <p>{{ $config->address }}, M.: {{ $config->mobile }}</p>        
    </div>

    <div class="border-top border-bottom mt-10">
        <div class="padding">
            <table>                
                <tr>
                    <td>
                        @if($order->name)
                            Name: {{ $order->name }} - {{ $order->phone }}
                        @else
                            Mobile: {{ $order->phone }}
                        @endif
                        </td>
                    <td align="right">Invoice: {{ $order->id }}</td>
                </tr>
                <tr>
                    <td>
                        @if($order->seat?->table)
                            Table: #{{ $order->seat?->table }} {{ $order->seat?->area?->area_name }}   
                        @else
                            Order: {{ $order->order_type }}
                        @endif
                    </td>
                    <td align="right">Date: {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</td>
                </tr>
            </table>
        </div>
    </div>    
    <table>
        <thead>
            <tr>
                <th align="left">Item</th>
                <th align="center">Price/Qty</th>
                <th align="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td align="center">{{ $item->price }} x {{ $item->quantity }}</td>
                    <td align="right">₹{{ number_format($item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <table>
        @if($order->order_type == 'Delivery')
            <tr>
                <td>Platform Fee</td>
                <td align="right">₹{{ number_format($shipping, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td>Subtotal</td>
            <td align="right">₹{{ number_format($subtotal, 2) }}</td>
        </tr>        
        <tr>
            <td>CGST</td>
            <td align="right">₹{{ number_format($cgstAmount, 2) }}</td>
        </tr>
        <tr>
            <td>SGST</td>
            <td align="right">₹{{ number_format($sgstAmount, 2) }}</td>
        </tr>
    </table>
    <hr>
    <table>        
        <tr class="total">
            <td>Total</td>
            <td align="right">₹{{ round($grandTotal) }}</td>
        </tr>
    </table>

    <div class="text-center" style="margin-top:15px;">
        Thank You! Visit Again
    </div>
</div>

</body>
</html>