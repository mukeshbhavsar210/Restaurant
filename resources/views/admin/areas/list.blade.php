@extends('admin.layouts.app')

@section('content')

<section class="content-header">
        <div class="row">
            <div class="col-sm-10">
                <h1>Tables <span class="count">{{ $totalTable }}</span></h1>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addTable">Add Table</button>
            </div>
        </div>
</section>
   
<section>
    @include('admin.layouts.message')
    <div class="modal fade drawer right-align" id="addTable" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" name="addingTableForm" id="addingTableForm">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="table_name">Table Code</label>
                                    <input type="text" name="table_name" id="name" class="form-control" placeholder="e.g. Table_01">
                                    <input type="hidden" name="slug" id="slug" class="form-control" placeholder="Slug">
                                    <p></p>
                                </div>  
                            
                                <div class="form-group">
                                    <label for="seating_capacity">Seating Capacity</label>
                                    <div class="tableSelection">
                                        <div class="btn-group" name="seating_capacity" id="options" data-toggle="buttons">
                                            <label class="btn btn-default">1 Seat
                                                <input type="radio" name="seating_capacity" class="btn-check" value="1">
                                            </label>

                                            <label class="btn btn-default">2 seats
                                                <input type="radio" name="seating_capacity" class="btn-check" value="2">
                                            </label>
        
                                            <label class="btn btn-default">4 seats
                                                <input type="radio" name="seating_capacity" class="btn-check" value="4" >
                                            </label>
                                            
                                            <label class="btn btn-default">6 seats
                                                <input type="radio" name="seating_capacity" class="btn-check" value="6" >
                                            </label>

                                            <label class="btn btn-default">8 seats
                                                <input type="radio" name="seating_capacity" class="btn-check" value="8" >
                                            </label>

                                            <label class="btn btn-default">10 seats
                                                <input type="radio" name="seating_capacity" class="btn-check" value="10" >
                                            </label>
                                        </div>
                                    </div>
                                    <p></p>
                                </div>
                            
                                <div class="form-group">
                                    <label>Select Restaurant</label>
                                    <div class="tableSelection">
                                        <div class="btn-group" name="area_name" id="options" data-toggle="buttons">
                                            @if($areas->isNotEmpty())
                                                @foreach ($areas as $value)
                                                    <label class="btn btn-default" for="area_{{ $value->area_name }}">{{ $value->area_name }}
                                                        <input type="radio" name="area_name" id="area_{{ $value->area_name }}" class="btn-check" value="{{ $value->id }}">
                                                    </label>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                @if($tableRunning->isNotEmpty())
                    @foreach ($tableRunning as $value)
                        <div class="col-md-3">
                            <div class="invisible-checkboxes">
                                {{-- <input {{ ($value->seat->status == $value->status) ? 'checked' : '' }} type="checkbox" id="custom_{{ $value->table_slug }}" value="{{ $value->table_name }}" />
                                <label class="checkbox-alias" for="custom_{{ $value->seat->table_slug }}">{{ $value->seat->table_name  }} <p class="small-text">Seats: {{ $value->seating_capacity }}</p></label> --}}
                                
                                <div class="countSeat">
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#showQR_{{ $value->table_slug }}">QR</a>
                                </div>
                            </div>

                            <div class="modal fade drawer right-align" id="showQR_{{ $value->table_slug }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">QR Code</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h2>{{ $value->table_name }}</h2>
                                            <h2>{{ $value->area_id }}</h2>
                                            {!! DNS2D::getBarcodeHTML('http://127.0.0.1:8000/'.$value->table_slug, 'QRCODE',6.5,6.5) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div id="accordion" class="accordion mt-3">
                <div class="card mb-0">
                    @if ($areas->isNotEmpty())
                        @foreach ($areas as $value)
                            @if($value->seat->count() > 0)
                                <div class="card-header collapsed" data-toggle="collapse" href="#collapse_{{ $value->id }}" aria-expanded="true">
                                    <a class="card-title">{{ $value->area_name }} <span class="count">{{ $value->seat->count() }}</span></a> 
                                </div>
                            @endif                                    
                            <div id="collapse_{{ $value->id }}" class="collapse" data-parent="#accordion" >
                                <div class="card-body">
                                    <div class="row">
                                        @if ($value->seat->isNotEmpty())
                                            @foreach ($value->seat as $value)
                                                <div class="col-md-4">
                                                    <div class="invisible-checkboxes">
                                                        <input {{ ($value->status == 'running') ? 'checked' : '' }} type="checkbox" id="custom_{{ $value->area_id }}_{{ $value->table_slug }}"  value="{{ $value->table_name }}" />
                                                        <label class="checkbox-alias" for="custom_{{ $value->area_id }}_{{ $value->table_slug }}">{{ $value->table_name }} <p class="small-text">Seats: {{ $value->seating_capacity }}</p></label>

                                                        <div class="countSeat">
                                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#showQR_branch_{{ $value->table_slug }}">QR</a>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade drawer right-align" id="showQR_branch_{{ $value->table_slug }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-sm" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel2">QR Code</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h2>{{ $value->table_name }}</h2>
                                                                    <p>{{ $value->area_name }}</p>
                                                                    {!! DNS2D::getBarcodeHTML('http://127.0.0.1:8000/'.$value->table_slug, 'QRCODE',6.5,6.5) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>                           
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        <div class="col-md-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tabs_1" role="tab">Dinein</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabs_2" role="tab">Takeaway</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabs_3" role="tab">Delivery</a></li>
            </ul>
            <div class="tab-content card">
                <div class="tab-pane card-body" id="tabs_1" role="tabpanel">
                    <h2>Dinein</h2>
                </div>
                <div class="tab-pane card-body" id="tabs_2" role="tabpanel">
                    <h2>Takeaway</h2>
                </div>
                <div class="tab-pane card-body" id="tabs_3" role="tabpanel">
                    <h2>Delivery</h2>
                </div>
            </div>
        </div>
    </div>
</section>   
@endsection

@section('customJs')
<script>  
    $('#name').change(function(){
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
                    $("#slug").val(response["slug"]);
                }
            }
        });
    })

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
                    window.location.href="{{ route('areas.index') }}"
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