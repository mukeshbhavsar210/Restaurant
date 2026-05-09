@extends('front.layouts.app')

@section('content')

@include('front.checkout.breadcrumb')

<section class="section-9 pt-4">
    <div class="container">
        <form action="" name="orderForm" id="orderForm" method="post">
            <div class="row">
                <div class="col-md-8">
                    <h2>Payment failed.</h2>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
