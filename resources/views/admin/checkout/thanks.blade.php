@extends('front.layouts.app')

@section('content')
    <section class="container">
        <div class="col-md-12 text-center py-5">

            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success')}}
                </div>
            @endif

            <h2>Thank You</h2>
            <h6 class="mt-3">Your Order id is: {{ $id }}</h6>

        </div>
    </section>

@endsection

@section('customJs')
@endsection
