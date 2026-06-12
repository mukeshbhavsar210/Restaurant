<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 5px; }

        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .invoice { width: 90%; margin: 0 auto; padding: 10px 20px; }
        .text-center { text-align: center; }        
        h3 { font-size: 20px; margin-bottom: 5px;}
        h5 { font-size: 13px; margin-bottom: 10px;}               
        .border-top { border-top: 1px dashed #666; }        
        .small-font { font-size: 11px;}
        .qrCode { color: #666;}
    </style>
</head>
<body style="text-align:center;">
    <div class="invoice">
        <div class="text-center mb-10">
            @if(!empty($config->logo))
                <img src="{{ public_path('uploads/logo/'.$config->logo) }}" style="width:110px;">
            @endif                        
            <p>{{ $config->address }}, M.: {{ $config->mobile }}</p>
        </div>

        <div class="border-top">
            <h5>Scan QR code for book Table</h5>
            <img src="data:image/png;base64,{{ $qrCode }}" width="230" class="qrCode">            
            <p>Table {{ $seat->table }} ({{ $seat->area?->area_name }})</p>
        </div>
    </div>
</body>
</html>