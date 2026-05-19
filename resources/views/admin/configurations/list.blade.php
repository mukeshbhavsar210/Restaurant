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
                        <a class="nav-link active py-2" data-bs-toggle="tab" href="#tabs-1" role="tab" aria-selected="true">Information</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#tabs-2" role="tab" aria-selected="true">Branch</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#tabs-3" role="tab" aria-selected="false" tabindex="-1">Payments</a>
                    </li>                    
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#tabs-4" role="tab" aria-selected="false" tabindex="-1">Reservation</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#tabs-5" role="tab" aria-selected="false" tabindex="-1">GST</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#tabs-6" role="tab" aria-selected="false" tabindex="-1">Your Plan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#tabs-7" role="tab" aria-selected="false" tabindex="-1">Payment Gateway</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#tabs-8" role="tab" aria-selected="false" tabindex="-1">Customer Website</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link py-2" data-bs-toggle="tab" href="#tabs-9" role="tab" aria-selected="false" tabindex="-1">Theme</a>
                    </li>
                </ul>
            </div>
                        
            <div class="tab-content">
                <div class="tab-pane active" id="tabs-1" role="tabpanel">
                    @if ($configurations->count())
                        <div class="row pt-3">
                            <div class="col-md-2">
                                <img style="width:100%;" src="{{ asset('uploads/logo/'.$configurations->pluck('logo')->implode('')) }}" />
                            </div>
                            <div class="col-md-10">
                                <h2 class="mb-1">{{ $configurations->pluck('name')->implode('') }}</h2>
                                <p>{{ $configurations->pluck('address')->implode('') }}<br />
                                Email: {{ $configurations->pluck('email')->implode('') }}<br />
                                Mobile: {{ $configurations->pluck('phone')->implode('') }}</p>
                                <a href="javascript:0" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#editCompanyModal">Edit</a>
                                {{-- <a href="{{ route('configurations.edit', $configurations->pluck('id')->implode('') ) }}" class="btn btn-primary">Edit</a> --}}
                            </div>        
                        </div>    

                        <div class="modal fade drawer right-align" id="editCompanyModal" tabindex="-1" aria-labelledby="editCompanyModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Update</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    @else
                        <form action="{{ route('configurations.store') }}" method="post" enctype="multipart/form-data" >
                            @csrf
                                <div class="row">   
                                    <div class="col-md-8">
                                        <div class="row">   
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input name="name" type="text" class="form-control" placeholder="Restaurant Name" value="{{ old('name') }}" />
                                                    @error('name')
                                                        <p class="alert alert-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="image">Logo</label>
                                                    <input type="file" class="form-control" name="logo" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input name="email" placeholder="email" type="email" class="form-control" value="{{ old('email') }}" />
                                                    @error('email')
                                                        <p class="text-red-400 font-small">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Phone</label>
                                                    <input name="phone" placeholder="Phone" type="text" class="form-control" value="{{ old('phone') }}" />
                                                    @error('phone')
                                                        <p class="text-red-400 font-small">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <textarea name="address" placeholder="Restaurant address" type="text" cols="3" rows="4" class="form-control">{{ old('address') }}</textarea>
                                                    @error('address')
                                                        <p class="text-red-400 font-small">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary">Submit</button>
                                    </form>
                                </div>                                                         
                        </div>
                    @endif                               
                </div>

                <div class="tab-pane" id="tabs-2" role="tabpanel">
                    <div class="row mt-3">
                        <div class="col-md-10 col-12">
                            <h4>Branches</h4>
                        </div>
                        
                        <div class="col-md-2 col-12">
                            <a href="javascript:0" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addBranchModal">Add Branch</a>
                        </div>            
                    </div>

                    <div class="modal fade" id="addBranchModal" tabindex="-1" aria-labelledby="addBranchModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Branch</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form action="{{ route('branch.store') }}" method="post">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input type="text" name="area_name" id="area_name" class="form-control slug-source" placeholder="Name" data-target="#area_slug">
                                            <input type="hidden" name="area_slug" id="area_slug" class="form-control" placeholder="Name">                                            
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Create Branch</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="accordion mt-1" id="accordionExample">
                        @if($branches->isNotEmpty())
                            @foreach ($branches as $key => $value)
                                <div class="accordion-item">                                    
                                    <div class="accordion-header" id="heading{{ $value->id }}">
                                        <button class="accordion-button {{ $key != 0 ? 'collapsed' : '' }}"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $value->id }}"
                                                aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $value->id }}">
                                            <h5 class="mb-0">{{ $value->area_name }} - {{ $value->total_seats }}</h5>
                                        </button>
                                    </div>
                                    <div id="collapse{{ $value->id }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $value->id }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <a href="javascript:0" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTableModal_{{ $value->id }}">Add Table</a>
                                            <a href="{{ route('delete.branch', $value->id) }}" class="btn btn-outline-danger">Delete</a>

                                            <div class="flex-2 mt-3 mb-2">
                                                @foreach ($value->seats as $seat)
                                                    <div>
                                                        <button type="button" class="btn btn-outline-secondary position-relative" data-bs-toggle="modal" data-bs-target="#QRModal_{{ $value->id }}">
                                                            <div class="flex-2">
                                                                <p class="mb-0 mr-2">{{ $seat->table_name }}</p>
                                                                @if($seat->status == 'running')
                                                                    <div class="dot-status green"></div>
                                                                @elseif($seat->status == 'available')
                                                                    <div class="dot-status red"></div>
                                                                @endif
                                                            </div> 
                                                            
                                                            @if($seat->capacity)
                                                                <span class="position-absolute top-0 start-100 translate-middle bg-black border border-light rounded-circle">
                                                                    <small class="thumb-xs white">{{ $seat->capacity }}</small>
                                                                </span>                                                                                                                                
                                                            @endif                                                            
                                                        </button>
                                                        
                                                        <div class="modal fade" id="QRModal_{{ $value->id }}" tabindex="-1" aria-labelledby="QRModalLabel" aria-hidden="true" style="display: none;">
                                                            <div class="modal-dialog modal-sm" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">{{ $value->area_name }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">                                                  
                                                                        <h4>{{ $seat->table_name }} ({{ $seat->capacity }})</h4>                                                                                
                                                                        <p>{!! DNS2D::getBarcodeHTML('http://127.0.0.1:8000/'.$value->area_slug.'/'.$seat->table_slug, 'QRCODE',9.0,9.0) !!}</p>
                                                                        <a href="{{ route('delete.table', $seat->id) }}" class="btn btn-outline-danger w-100">Delete Table</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="modal fade drawer right-align" id="addTableModal_{{ $value->id }}" tabindex="-1" aria-labelledby="addTableModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Table for {{ $value->area_name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <form action="{{ route('table.store') }}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="table_name">Table Code</label>
                                                        <input type="text" name="table_name" id="name" class="form-control slug-source" placeholder="e.g. Table_01" data-target="#table_slug">
                                                        <input type="hidden" name="table_slug" id="table_slug" class="form-control">
                                                        <input type="hidden" name="area_id" value="{{ $value->id }}">                                                        
                                                    </div>  
                                                
                                                    <div class="form-group">
                                                        <label for="seating_capacity">Seating Capacity</label>
                                                        @php
                                                            $seatingCapacities = [1, 2, 4, 6, 8, 10];
                                                        @endphp

                                                        <div class="row">
                                                            @foreach($seatingCapacities as $seat)
                                                                <div class="col-md-4">
                                                                    <label class="custom-radio mt-2"> 
                                                                        <input type="radio" name="capacity" value="{{ $seat }}">
                                                                            <span class="radio-mark"></span>
                                                                            {{ $seat }} {{ $seat == 1 ? 'Seat' : 'Seats' }}
                                                                    </label>
                                                                </div>
                                                            @endforeach      
                                                        </div>                              
                                                    </div>
                                                </div>
                                                <div class="modal-footer">                                
                                                    <button type="submit" class="btn btn-primary">Create</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="tab-pane" id="tabs-3" role="tabpanel">
                    <h1>Payments</h1>
                </div>
                <div class="tab-pane" id="tabs-4" role="tabpanel">                    
                    <h1>Reservation</h1>
                </div>
                <div class="tab-pane" id="tabs-5" role="tabpanel">                    
                    {{ $configurations->pluck('taxes')->implode('') }}
                    {{ $configurations->pluck('percentages')->implode('') }} %
                </div>
                <div class="tab-pane" id="tabs-6" role="tabpanel">
                    {{ $configurations->pluck('plan')->implode('') }}
                </div>
                <div class="tab-pane" id="tabs-7" role="tabpanel">                    
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
                <div class="tab-pane" id="tabs-8" role="tabpanel">                   
                    <h1>Customer Website</h1>                                        
                </div>
                <div class="tab-pane" id="tabs-9" role="tabpanel">
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

    function deleteArea(id){
        var url = '{{ route("delete.branch","ID") }}'
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
                        window.location.href="{{ route('configurations.index') }}"
                    }
                }
            });
        }
    }

    $(document).ready(function () {
        $('.green').addClass('blink');
    });
</script>
@endsection