@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active pt-0" data-bs-toggle="tab" href="#tabs-1" role="tab" aria-selected="true">Configurations</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link pt-0" data-bs-toggle="tab" href="#tabs-2" role="tab" aria-selected="true">Branch</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link pt-0" data-bs-toggle="tab" href="#tabs-3" role="tab" aria-selected="false" tabindex="-1">Pages</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link pt-0" data-bs-toggle="tab" href="#tabs-4" role="tab" aria-selected="false" tabindex="-1">Permissions</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link pt-0" data-bs-toggle="tab" href="#tabs-5" role="tab" aria-selected="false" tabindex="-1">Roles</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link pt-0" data-bs-toggle="tab" href="#tabs-6" role="tab" aria-selected="false" tabindex="-1">Users</a>
            </li>
        </ul>
                                    
        <div class="tab-content mt-3">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <h4>Configurations</h4>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h5 class="accordion-header m-0" id="headingOne">
                            <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Company Details
                            </button>
                        </h5>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                @if ($configurations->count())
                                    <div class="row pt-2">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <img style="width:100%;" src="{{ asset('uploads/logo/'.$configurations->pluck('logo')->implode('')) }}" />
                                                </div>
                                                <div class="col-md-9">
                                                    <h2 class="mb-1">{{ $configurations->pluck('name')->implode('') }}</h2>
                                                    <p>{{ $configurations->pluck('address')->implode('') }}<br />
                                                    Email: {{ $configurations->pluck('email')->implode('') }}<br />
                                                    Mobile: {{ $configurations->pluck('phone')->implode('') }}</p>
                                                    <a href="javascript:0" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#editCompanyModal">Edit</a>
                                                    {{-- <a href="{{ route('configurations.edit', $configurations->pluck('id')->implode('') ) }}" class="btn btn-primary">Edit</a> --}}
                                                </div>
                                            </div>
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
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h5 class="accordion-header m-0" id="headingTwo">
                            <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Theme
                            </button>
                        </h5>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">                                        
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
                                        <div class="col-md-1">
                                            <button class="btn btn-primary mt-2">Submit</button>
                                        </div>
                                    </div>
                                </form> 
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h5 class="accordion-header m-0" id="headingThree">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Payment Gateway
                            </button>
                        </h5>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">                                        
                                {{ $configurations->pluck('taxes')->implode('') }}
                                {{ $configurations->pluck('percentages')->implode('') }} %
                                {{ $configurations->pluck('plan')->implode('') }}

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
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h5 class="accordion-header m-0" id="headingFour">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Website
                            </button>
                        </h5>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                            <div class="accordion-body">                                        
                                Website  
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tabs-2" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-md-9 col-12">
                        <h4>Branches</h4>
                    </div>
                    
                    <div class="col-md-3 col-12">
                        <div class="flex float-end">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#{{ $branchForm['modal_id'] }}">{{ $branchForm['button_name'] }}</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $tableForm['modal_id'] }}">{{ $tableForm['button_name'] }}</button>
                        </div>
                    </div>            
                </div>                        

                <div class="accordion mt-1" id="accordionExample">
                    @if($branches->isNotEmpty())
                        @foreach ($branches as $key => $value)
                            <div class="accordion-item">                                    
                                <div class="accordion-header" id="heading{{ $value->id }}">
                                    <button class="accordion-button p-xl-2 {{ $key != 0 ? 'collapsed' : '' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $value->id }}"
                                            aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                            aria-controls="collapse{{ $value->id }}">

                                            <h5 class="mb-0 mt-1">{{ $value->area_name }} - {{ $value->total_seats }}</h5>                                                    
                                    </button>
                                </div>

                                <div id="collapse{{ $value->id }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $value->id }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row mt-1 mb-2">
                                            <div class="col-11">
                                                <div class="flex-2 ">                                                    
                                                    @foreach ($value->seats as $seat)                                                        
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
                                                    @endforeach
                                                </div>                                                              
                                            </div>
                                            <div class="col-1">                                                                                                                                                                                                        
                                                <a href="{{ route('delete.branch', $value->id) }}" class="btn btn-outline-danger">Remove</a>
                                            </div>
                                        </div>                                                
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="tab-pane" id="tabs-3" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-md-10 col-12">
                        <h4>Pages</h4>
                    </div>
                    
                    <div class="col-md-2 col-12">
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#{{ $pageForm['modal_id'] }}">{{ $pageForm['button_name'] }}</button>                                
                    </div>            
                </div>                       

                <div class="accordion mt-2" >
                    @if ($pages->isNotEmpty())
                        @foreach ($pages as $key => $value)
                            <div class="accordion-item">
                                <div class="accordion-header" id="heading{{ $value->id }}">
                                    <button class="accordion-button p-xl-2 {{ $key != 0 ? 'collapsed' : '' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $value->id }}"
                                            aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                            aria-controls="collapse{{ $value->id }}">
                                            
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-11">
                                                        <h5 class="mb-0 mt-1">{{ $value->page_name }}</h5>
                                                    </div>
                                                    <div class="col-1">
                                                        <div class="flex">
                                                            <a href="javascript:void(0)" class="editPageModal"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#createPageModal"
                                                                data-action="{{ route('pages.update', $value->id) }}"
                                                                data-page_name="{{ $value->page_name }}"
                                                                data-page_slug="{{ $value->page_slug }}"
                                                                data-content="{{ $value->content }}" 
                                                                data-button="Update Page" >
                                                                
                                                                <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                                </svg>
                                                            </a>
                                                            
                                                            <a href="#" onclick="deletePage({{ $value->id }})" class="text-danger w-4 h-4 mr-1">
                                                                <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </a>                                                                         
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </button>                                            
                                </div>
                                
                                <div id="collapse{{ $value->id }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $value->id }}" data-bs-parent="#accordionExample">                                
                                    <div class="accordion-body">                                                
                                        {!! $value->content !!}
                                    </div>
                                </div>
                            </div> 
                        @endforeach
                    @else                                
                        <div>Records not found</div>                                
                    @endif
                </div>
            </div>

            <div class="tab-pane" id="tabs-4" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-md-7 col-12">     
                        <div class="page-title"> 
                            <h4>Permissions</h4>
                            <span class="counts">{{ $totalPermissions }}</span>
                        </div>
                    </div>

                    <div class="col-md-5 col-12 float-end">                
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#{{ $permissionForm['modal_id'] }}">{{ $permissionForm['button_name'] }}</button>                                
                    </div>
                </div>  

                <div class="table-responsive mt-2">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-top-0">Name</th>
                                <th class="border-top-0" width="150">Author</th>
                                <th class="border-top-0" width="150">Date</th>
                                <th class="border-top-0 text-end" width="100">Action</th>
                            </tr>
                        </thead>                     
                        <tbody>
                        @if($permissions->isNotEmpty())
                            @foreach ($permissions as $value)
                                <tr>
                                    <td>{{ $value->permission_name }}</td>
                                    <td>{{ $value->guard_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}</td>
                                    <td class="text-end">
                                        <a href="javascript:void(0)" class="editPermissionModal"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#createPermissionModal"
                                            data-action="{{ route('permissions.update', $value->id) }}"
                                            data-permission_name="{{ $value->permission_name }}"
                                            data-button="Update Permission" >
                                            
                                            <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                        </a>
                                        <a href="javascript:void(0)" onclick="deletePermission({{ $value->id }})" class="text-danger w-4 h-4 mr-1">
                                            <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>    
                                    </td>
                                </tr>
                            @endforeach
                        @endif                            
                    </tbody>
                </table>                 
            </div>
        </div>

            <div class="tab-pane" id="tabs-5" role="tabpanel">
                <div class="row mt-3">                
                    <div class="col-md-7 col-12">
                        <div class="page-title"> 
                            <h4>Roles</h4>
                            <span class="counts">{{ $totalRoles }}</span>
                        </div>
                    </div>
                    <div class="col-md-5 col-12">
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#{{ $roleForm['modal_id'] }}">{{ $roleForm['button_name'] }}</button>                                
                    </div>
                </div>

                <div class="table-responsive mt-2">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-top-0">Role Name</th>
                                <th class="border-top-0">Permissions</th>
                                <th class="border-top-0 text-end" width="150">Counts</th>
                                <th class="border-top-0 text-end" width="150">Date</th>
                                <th class="border-top-0 text-end" width="100">Action</th>
                            </tr>
                        </thead>                     
                        <tbody>                    
                            @if($roles->isNotEmpty())
                                @foreach ($roles as $value)
                                    <tr>
                                        <td>
                                            <h5 class="mb-0">{{ $value->name }}</h5>                                            
                                        </td>
                                        <td>
                                            <p class="text-muted">                                                                                           
                                                @if($value->name == 'Super Admin')
                                                    <b>You're Super Admin, So not required any permission</b>
                                                @else
                                                    {{ $value->permissions->pluck('name')->implode(", ") }}
                                                @endif
                                            </p>
                                        </td>
                                        <td class="text-end">
                                            @if($value->name == 'Super Admin')
                                                <span class="count-sub">All permissions</span>
                                            @else
                                                <span class="count-sub">{{ $value->permissions->count('name') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">{{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}</td>
                                        <td>
                                            <a href="javascript:void(0)" class="editRoleModal"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#createRoleModal"
                                                data-action="{{ route('roles.update', $value->id) }}"
                                                data-name="{{ $value->name }}"                                                
                                                data-button="Update Page" >
                                                
                                                <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                            </a>

                                            <a href="javascript:void(0)" onclick="deleteRole({{ $value->id }})" class="text-danger w-4 h-4 mr-1">
                                                <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </a>                                            
                                        </td> 
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane" id="tabs-6" role="tabpanel">
                Users
            </div>
        </div>           
    </div>
</div>

@include('components.common-modal', [
    'modal' => $branchForm,
])

@include('components.common-modal', [
    'modal' => $tableForm,
])

@include('components.common-modal', [
    'modal' => $pageForm,
])

@include('components.common-modal', [
    'modal' => $permissionForm,
])

@include('components.common-modal', [
    'modal' => $roleForm,
])

@endsection
        
@section('customJs')
<script type="text/javascript">
    

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
                    window.location.href="{{ route('categories.index') }}"
                    /*if(response["status"]){
                        window.location.href="{{ route('categories.index') }}"
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

    $(document).on("click", ".user_dialog", function () {
        alert("H");
        var UserName = $(this).data('id');
        $(".modal-body #user_name").val( UserName );
    });

    $(document).on('click', '.editPageModal', function () {
        let action = $(this).data('action');
        let buttonText = $(this).data('button');                       

        $('#commonForm').attr('action', action);
        $('input[name="page_name"]').val($(this).data('page_name'));
        $('input[name="page_slug"]').val($(this).data('page_slug'));
        $('input[name="content"]').val($(this).data('content'));
        $('#submitBtn').text(buttonText);
    });    

    $(document).on('click', '.editPermissionModal', function () {
        let action = $(this).data('action');
        let buttonText = $(this).data('button');                       

        $('#commonForm').attr('action', action);
        $('input[name="permission_name"]').val($(this).data('permission_name'));
        $('#submitBtn').text(buttonText);
    });   

    $(document).on('click', '.editRoleModal', function () {
        let action = $(this).data('action');
        let buttonText = $(this).data('button');                       

        $('#commonForm').attr('action', action);
        $('input[name="name"]').val($(this).data('name'));
        $('#submitBtn').text(buttonText);
    });   

    $(document).ready(function () {
        $('.green').addClass('blink');
    });
</script>
@endsection