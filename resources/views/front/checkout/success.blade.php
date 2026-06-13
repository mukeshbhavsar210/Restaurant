@extends('front.layouts.app')

@section('content')    

@include('front/layouts/message')

<div class="order-summary">
    <h2>Order placed successfully!</h2>
    <p>Redirecting to Main page in <span id="countdown">7</span>  seconds...</p>  
    <a href="{{ route('customer.invoice', $order->id) }}" class="btn btn-primary btn-sm mt-3">
        Download Invoice
    </a> 

    @php
        $upiId = configData()->upi_id;
        $merchantName = configData()->name;
        $subtotal = $order->total;
        $sgst = configData()->sgst ?? 0;
        $cgst = configData()->cgst ?? 0;
        $sgstAmount = ($subtotal * $sgst) / 100;
        $cgstAmount = ($subtotal * $cgst) / 100;
        // $amount = $subtotal + $sgstAmount + $cgstAmount;

        $amount = round(
            $order->total
            + (($order->total * configData()->sgst) / 100)
            + (($order->total * configData()->cgst) / 100)
        );

        $upiUrl = "upi://pay?pa={$upiId}&pn=".urlencode($merchantName)."&am={$amount}&cu=INR";
    @endphp

    <div class="text-center">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate($upiUrl) !!}
        {{-- {!! QrCode::size(250)->generate($upiUrl) !!} --}}
        {{-- <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#upiPaymentModal">
            Pay at Restaurant
        </button> --}}
    </div>

    {{-- <div class="modal fade" id="upiPaymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan & Pay</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    {!! QrCode::size(250)->generate($upiUrl) !!}
                    <p class="mt-3 mb-1">
                        Amount: ₹{{ number_format($amount, 2) }}
                    </p>
                    <small>{{ $upiId }}</small>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="upi-code">
        @if(!empty(configData()->upi))
            <img src="{{ asset('uploads/logo/'.configData()->upi) }}" class="upi-img" />
        @endif
        <p>Scan & Pay Using PhonePe App to</p>
        @if(!empty(configData()->upi_id))
            <h3>{{ configData()->upi_id }}</h3>
        @endif        
    </div>        
</div>
@endsection

@section('customJs')
    {{-- <script>
        let seconds = 10;
        const timer = setInterval(function () {
            seconds--;

            $('#countdown').text(seconds);

            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = "{{ route('front.menu') }}";
            }
        }, 1000);
    </script> --}}
@endsection