@if (Session::has('success'))    
    <div class="custom-success-alert" role="alert">
        {!! Session::get('success') !!}            
    </div>
@endif

@if (Session::has('error'))
    <div class="custom-success-alert" role="alert">
        {{ Session::get('error') }}        
    </div>
@endif