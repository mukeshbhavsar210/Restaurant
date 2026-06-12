@extends('front.layouts.app')

@section('content')
<div class="container text-center py-5">
    <h2>Payment Failed</h2>
    <p>Your payment could not be completed.</p>

    @if(request('error'))
        <p>{{ request('error') }}</p>
    @endif

    <a href="{{ route('razorpay.checkout', $order->id) }}" class="btn btn-primary">Try Again</a>
</div>
@endsection