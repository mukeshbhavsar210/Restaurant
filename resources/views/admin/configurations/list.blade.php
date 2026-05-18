@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">        
        <div class="page-title"> 
            <h4>Configurations</h4>
        </div>
        
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="clearfix">                
                <ul class="nav nav-tabs my-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold active py-2" data-bs-toggle="tab" href="#tabs-1" role="tab" aria-selected="true">Information</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold py-2" data-bs-toggle="tab" href="#tabs-2" role="tab" aria-selected="false" tabindex="-1">Payments</a>
                    </li>                    
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold py-2" data-bs-toggle="tab" href="#tabs-3" role="tab" aria-selected="false" tabindex="-1">Reservation</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold py-2" data-bs-toggle="tab" href="#tabs-4" role="tab" aria-selected="false" tabindex="-1">GST</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold py-2" data-bs-toggle="tab" href="#tabs-5" role="tab" aria-selected="false" tabindex="-1">Your Plan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold py-2" data-bs-toggle="tab" href="#tabs-6" role="tab" aria-selected="false" tabindex="-1">Payment Gateway</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold py-2" data-bs-toggle="tab" href="#tabs-7" role="tab" aria-selected="false" tabindex="-1">Customer Website</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-semibold py-2" data-bs-toggle="tab" href="#tabs-8" role="tab" aria-selected="false" tabindex="-1">Theme</a>
                    </li>
                </ul>
            </div>
                        
            <div class="tab-content">
                <div class="tab-pane active" id="tabs-1" role="tabpanel">
                    @include('admin.configurations.step1')
                </div>
                <div class="tab-pane" id="tabs-2" role="tabpanel">                    
                    <h1>Payments</h1>
                </div>
                <div class="tab-pane" id="tabs-3" role="tabpanel">                    
                    <h1>Reservation</h1>
                </div>
                <div class="tab-pane" id="tabs-4" role="tabpanel">                    
                    {{ $configurations->pluck('taxes')->implode('') }}
                    {{ $configurations->pluck('percentages')->implode('') }} %
                </div>
                <div class="tab-pane" id="tabs-5" role="tabpanel">
                    {{ $configurations->pluck('plan')->implode('') }}
                </div>
                <div class="tab-pane" id="tabs-6" role="tabpanel">                    
                    <h5>Payment Gateway</h5><br />
                    @if ($payments->count())
                        <p>Your Key ID: {{ $payments->pluck('your_key_id')->implode('') }}<br />
                        Your Key ID: {{ $payments->pluck('your_key_secret')->implode('') }}</p>
                    @else
                        <form action="{{ route('payment.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>Your Key ID</label>
                                <input type="text" name="your_key_id" id="your_key_id" class="form-control" placeholder="Your Key ID" >
                                @error('your_key_id')
                                    <p class="text-red-400 font-small">Key ID</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Your Key Secret</label>
                                <input type="text" name="your_key_secret" id="your_key_secret" class="form-control" placeholder="Your Key ID">
                                @error('your_key_secret')
                                    <p class="text-red-400 font-small">Secret Key</p>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form> 
                    @endif                                                                                                                           
                </div>
                <div class="tab-pane" id="tabs-7" role="tabpanel">                   
                    <h1>Customer Website</h1>                                        
                </div>
                <div class="tab-pane" id="tabs-8" role="tabpanel">
                    <h3>Theme</h3>
                    <form action="{{ route('configurations.theme') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">                                
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Primary</label>
                                    <input name="primary_color" type="color" class="form-control" value="{{ old('primary_color') }}" />
                                    <div class="theme-primary" style="background-color: {{ $theme->pluck('primary_color')->implode('') }}"></div>
                                    @error('primary_color')
                                        <p class="text-red-400 font-small">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Secondary</label>
                                    <input name="secondary_color" type="color" class="form-control" value="{{ old('secondary_color') }}" />
                                    <div class="theme-primary" style="background-color: {{ $theme->pluck('secondary_color')->implode('') }}"></div>
                                    @error('secondary_color')
                                        <p class="text-red-400 font-small">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Sidebar</label>
                                    <input name="sidebar_color" type="color" class="form-control" value="{{ old('sidebar_color') }}" />
                                    <div class="theme-primary" style="background-color: {{ $theme->pluck('sidebar_color')->implode('') }}"></div>
                                    @error('sidebar_color')
                                        <p class="text-red-400 font-small">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </div>                    
                    </form>                    
                    </div>                                   
                </div>
            </div> 
        </div> 
    </div>
</div>
@endsection
        
@section('customJs')
<script type="text/javascript">
    $('#area_name').change(function(){
        element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: {title: element.val()},
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled', false);
                if(response["status"] == true){
                    $("#area_slug").val(response["slug"]);
                }
            }
        });
    })

    function deletePermission(id) {
        if (confirm("Are you sure you want to delete?")) {
            $.ajax({
                url: '{{ route('permissions.destroy') }}',
                type: 'delete',
                data: {id:id},
                dataType: 'json',                    
                headers: {
                    'x-csrf-token' : '{{ csrf_token() }}'
                },
                success: function(response) {
                    window.location.href="{{ route('configurations.index') }}"
                }
            });
        }
    }

    $("#addingTableForm").submit(function(event){
        event.preventDefault();

        var element = $('#addingTableForm');
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: '{{ route("seatings.store") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled', false);

                if(response["status"] == true){
                    window.location.href="{{ route('configurations.index') }}"
                    $('#name').removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                    
                    $('#category').removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                } else {
                    var errors = response['errors']
                    if(errors['name']){
                        $('#name').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['name']);
                    } else {
                        $('#name').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }
                    
                    if(errors['category']){
                        $('#category').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['category']);
                    } else {
                        $('#category').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }
                }

            }, error: function(jqXHR, exception) {
                console.log("Something event wrong");
            }
        })
    });

    //DELETE
    function deleteMenuItem(id){
        var url = '{{ route("menu.delete","ID") }}'
        var newUrl = url.replace("ID",id)

        if(confirm("Are you sure you want to delete?")){
            $.ajax({
                url: newUrl,
                type: 'delete',
                data: {},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    window.location.href="{{ route('menu.index') }}"
                    /*if(response["status"]){
                        window.location.href="{{ route('menu.index') }}"
                    }*/
                }
            });
        }
    }

    $("#addAreaForm").submit(function(event){
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("areas.store") }}',
            type: 'post',
            data: element.serializeArray(),
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled', false);

                if(response["status"] == true){
                    window.location.href="{{ route('areas.index') }}"
                    $('#name').removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                } else {
                    var errors = response['errors']
                    if(errors['name']){
                        $('#name').addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['name']);
                    } else {
                        $('#name').removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }

                }

            }, error: function(jqXHR, exception) {
                console.log("Something event wrong");
            }
        })
    });      

    function deleteArea(id){
        var url = '{{ route("areas.delete","ID") }}'
        var newUrl = url.replace("ID",id)

        if(confirm("Are you sure you want to delete?")){
            $.ajax({
                url: newUrl,
                type: 'delete',
                data: {},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    if(response["status"]){
                        window.location.href="{{ route('areas.index') }}"
                    }
                }
            });
        }
    }
</script>
@endsection