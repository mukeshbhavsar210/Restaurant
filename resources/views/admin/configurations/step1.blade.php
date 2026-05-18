@if ($configurations->count())
    <div class="row mt-2">
        <div class="col-md-2">
            <img style="width:100%;" src="{{ asset('uploads/logo/'.$configurations->pluck('logo')->implode('')) }}" />
        </div>
        <div class="col-md-10">
            <h4>{{ $configurations->pluck('name')->implode('') }}</h4>
            <p>{{ $configurations->pluck('address')->implode('') }}<br />
            Email: {{ $configurations->pluck('email')->implode('') }}<br />
            Mobile: {{ $configurations->pluck('phone')->implode('') }}</p>
            <a href="{{ route('configurations.edit', $configurations->pluck('id')->implode('') ) }}" class="btn btn-primary">Edit</a>
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
                <div class="col-md-4">
                    
        </div>                         
    </div>
@endif           

<div class="row mt-3">
    <div class="col-md-10 col-12">
        <h4>Branches</h4>
    </div>
    
    <div class="col-md-2 col-12">
        <a href="javascript:0" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#addBranchModal">Add Branch</a>
    </div>            
</div>

<div class="modal fade drawer right-align" id="addBranchModal" tabindex="-1" aria-labelledby="addBranchModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('areas.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="area_name" id="area_name" class="form-control slug-source" placeholder="Name" data-target="#area_slug">
                        <input type="hidden" name="area_slug" id="area_slug" class="form-control" placeholder="Name">
                        @error('area_name')
                            <p class="text-red-400 font-small">Enter New Restaurant</p>
                        @enderror
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
                 <h2 class="accordion-header" id="heading{{ $value->id }}">
                    <button class="accordion-button {{ $key != 0 ? 'collapsed' : '' }}"
                            type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $value->id }}"
                            aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $value->id }}">
                        {{ $value->area_name }} ({{ $value->total_seats }})
                    </button>
                </h2>
                <div id="collapse{{ $value->id }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $value->id }}" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            @foreach ($value->seats as $seat)
                                <div class="col-md-2 col-12">
                                    <div class="card-header bg-primary">
                                        <h4 class="card-title text-white">{{ $seat->table_name }}</h4>
                                    </div>
                                    <div>
                                        <p>Seating Capacity: <b>{{ $seat->capacity }}</b></p>
                                        {!! DNS2D::getBarcodeHTML('http://127.0.0.1:8000/'.$value->area_slug.'/'.$seat->table_slug, 'QRCODE',5.8,5.8) !!}
                                        <p class="mt-2">{{ $seat->status }}</p>
                                    </div>
                                </div>
                            @endforeach                            
                        </div>
                        
                        <a href="javascript:0" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTableModal_{{ $value->id }}">Add Table</a>                        
                        <a href="{{ route('delete.branch', $value->id) }}" class="btn btn-outline-danger">Delete</a>                        
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

                        <form action="" method="post" name="addingTableForm" id="addingTableForm"> 
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="table_name">Table Code</label>
                                    <input type="text" name="table_name" id="name" class="form-control slug-source" placeholder="e.g. Table_01" data-target="#slug">
                                    <input type="hidden" name="slug" id="slug" class="form-control">
                                    <input type="hidden" name="area_name" id="area_{{ $value->area_name }}" value="{{ $value->id }}">
                                    <p></p>
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
                                                    <input type="radio" name="seating_capacity"  value="{{ $seat }}">
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