@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">
        @php
            $tabs = [
                [
                    'id' => 'tabs-1',
                    'title' => 'Configurations',
                    'active' => true,
                ],
                [
                    'id' => 'tabs-2',
                    'title' => 'Branch',
                ],
                [
                    'id' => 'tabs-3',
                    'title' => 'Pages',
                ],
                [
                    'id' => 'tabs-4',
                    'title' => 'Permissions',
                ],
                [
                    'id' => 'tabs-5',
                    'title' => 'Roles',
                ],
                [
                    'id' => 'tabs-6',
                    'title' => 'Users',
                ],
            ];
        @endphp

        <ul class="nav nav-tabs" role="tablist">
            @foreach($tabs as $tab)
                <li class="nav-item" role="presentation">
                    <a class="nav-link pt-0 {{ !empty($tab['active']) ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#{{ $tab['id'] }}" role="tab" aria-selected="{{ !empty($tab['active']) ? 'true' : 'false' }}" >
                        {{ $tab['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
                                    
        <div class="tab-content mt-3">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <h4>Configurations</h4>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h5 class="accordion-header m-0" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Company Details
                            </button>
                        </h5>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                @if ($config->count())
                                    <div class="row pt-2">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <img style="width:100%;" src="{{ asset('uploads/logo/'.$config->pluck('logo')->implode('')) }}" />
                                                </div>
                                                <div class="col-md-9">
                                                    <h2 class="mb-1">{{ $config->pluck('name')->implode('') }}</h2>
                                                    <p>{{ $config->pluck('address')->implode('') }}<br />
                                                    Email: {{ $config->pluck('email')->implode('') }}<br />
                                                    Mobile: {{ $config->pluck('phone')->implode('') }}</p>
                                                    <a href="javascript:0" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#editCompanyModal">Edit</a>                                                    
                                                </div>
                                            </div>
                                        </div>                                                
                                    </div>    

                                    <div class="modal fade drawer right-align" id="editCompanyModal" tabindex="-1" aria-labelledby="editCompanyModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Update Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('configurations.update') }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">                                                    
                                                        <div class="form-group">
                                                            <label for="name">Restaurant Name*</label>
                                                            <input type="text" id="name" name="name" placeholder="Restaurant Name" class="form-control" value="{{ old('name', $config->name ?? '') }}">
                                                            <p></p>
                                                        </div>                                                                                                                                                                       
                                                        <div class="form-group">
                                                            <label for="email">Email*</label>
                                                            <input type="email" id="email" name="email" placeholder="Enter Email" class="form-control" value="{{ old('email', $config->email ?? '') }}">
                                                            <p></p>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-5">
                                                                <div class="form-group">
                                                                    <label for="phone">Phone*</label>
                                                                    <input type="text" id="phone" name="phone" placeholder="phone" class="form-control" value="{{ old('phone', $config->phone ?? '') }}">
                                                                    <p></p>
                                                                </div>
                                                            </div>
                                                            <div class="col-7">
                                                                <div class="form-group">
                                                                    <label for="image">Logo</label>
                                                                    <input type="file" class="form-control" name="logo" />
                                                                </div> 
                                                            </div>                                                            
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="address">Address*</label>
                                                            <textarea type="text" id="address" rows="3" cols="30" name="address" placeholder="address" class="form-control">{{ old('address', $config->address ?? '') }}</textarea>
                                                            <p></p>
                                                        </div> 
                                                        
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <label for="primary_color">Primary</label>
                                                                    <input name="primary_color" type="color" class="form-control" value="{{ old('primary_color', $config->primary_color ?? '') }}" />
                                                                    <div class="theme-primary" style="background-color: {{ $theme->pluck('primary_color')->implode('') }}"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <label for="secondary_color">Secondary</label>
                                                                    <input name="secondary_color" type="color" class="form-control" value="{{ old('secondary_color', $config->secondary_color ?? '') }}" />
                                                                    <div class="theme-primary" style="background-color: {{ $theme->pluck('secondary_color')->implode('') }}"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-5">
                                                                <div class="form-group">
                                                                    <label for="payment_key_id">Payment key id</label>
                                                                    <input type="text" id="payment_key_id" name="payment_key_id" placeholder="Payment key id" class="form-control" value="{{ old('payment_key_id', $config->payment_key_id ?? '') }}">                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="col-7">
                                                                <div class="form-group">
                                                                    <label for="payment_key_secret">Payment key secret</label>
                                                                    <input type="text" id="payment_key_secret" name="payment_key_secret" placeholder="Payment key secret" class="form-control" value="{{ old('payment_key_secret', $config->payment_key_secret ?? '') }}">
                                                                </div> 
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Update details</button>
                                                    </div>
                                                </form>
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
                        <h5 class="accordion-header m-0" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Payment Gateway
                            </button>
                        </h5>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">    
                                Payment Key Id: {{ $config->pluck('payment_key_id')->implode('') }}<br />
                                Payment Key Secret: {{ $config->pluck('payment_key_secret')->implode('') }}<br />
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h5 class="accordion-header m-0" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Plan
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
                        <div class="page-title">
                            <h4>Branches</h4> 
                            <span class="counts">{{ $branchCounts }}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-12">
                        <div class="flex float-end">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#{{ $branchForm['modal_id'] }}">{{ $branchForm['button_modal'] }}</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $tableForm['modal_id'] }}">{{ $tableForm['button_modal'] }}</button>
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

                                            {{ $value->area_name }} - {{ $value->total_seats }}
                                    </button>
                                </div>

                                <div id="collapse{{ $value->id }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $value->id }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="flex-justify py-2">                                            
                                            <div class="flex-2">                                                    
                                                @foreach ($value->seats as $seat)                                                        
                                                    <button type="button" class="btn btn-outline-secondary position-relative" data-bs-toggle="modal" data-bs-target="#QRModal_{{ $seat->id }}">
                                                        <div class="flex-2">
                                                            <p class="mb-0 mr-2">{{ $seat->table_name }}</p>
                                                            @if($seat->status == 'running')
                                                                <div class="dot-status green"></div>
                                                            @elseif($seat->status == 'available')
                                                                <div class="dot-status red"></div>
                                                            @endif
                                                        </div> 
                                                        
                                                        @if($seat->capacity)
                                                            <span class="position-absolute top-0 start-100 translate-middle bg-white border border-grey rounded-circle">
                                                                <small class="thumb-xs black">{{ $seat->capacity }}</small>
                                                            </span>                                                                                                                                
                                                        @endif                                                            
                                                    </button>                                                        
                                                    
                                                    <div class="modal fade drawer right-align" id="QRModal_{{ $seat->id }}" tabindex="-1" aria-labelledby="QRModalLabel" aria-hidden="true" style="display: none;">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">                                                                        
                                                                    <h5 class="modal-title" id="exampleModalLabel">Table Details</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="flex-justify">
                                                                        <h4 class="mb-1">{{ $value->area_name }}</h4>
                                                                        @if($seat->status == 'running')
                                                                            <p class="mb-0">Running Table</p>
                                                                        @elseif($seat->status == 'available')
                                                                            <p class="mb-0">Available</p>
                                                                        @endif
                                                                    </div>                                                                        
                                                                    <h5 class="mb-2">{{ $seat->table_name }} (Seats: {{ $seat->capacity }})</h5>
                                                                    <hr />
                                                                    <p>{!! DNS2D::getBarcodeHTML('http://127.0.0.1:8000/'.$value->area_slug.'/'.$seat->table_slug, 'QRCODE',11.5,11.5) !!}</p>                                                                    
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a href="{{ route('delete.table', $seat->id) }}" class="btn btn-outline-danger w-100">Delete {{ $seat->table_name }}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>                                            
                                                                                        
                                            <a href="javascript:void(0)" class="btn btn-outline-danger commonDeleteBtn"
                                                data-bs-toggle="modal" data-bs-target="#commonDeleteModal"
                                                data-url="{{ route('delete.branch', $value->id) }}" data-title="{{ $value->area_name }}">
                                                Remove Branch
                                            </a>
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
                        <div class="page-title">
                            <h4>Pages</h4> 
                            <span class="counts">{{ $pageCounts }}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-12">
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#{{ $pageForm['modal_id'] }}">{{ $pageForm['button_modal'] }}</button>                        
                    </div>            
                </div>                       

                <div class="accordion mt-2" >
                    @if ($pages->isNotEmpty())
                        @foreach ($pages as $key => $value)
                            <div class="accordion-item">
                                <div class="accordion-header" id="heading{{ $value->id }}">
                                    <button class="accordion-button {{ $key != 0 ? 'collapsed' : '' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $value->id }}"
                                            aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                            aria-controls="collapse{{ $value->id }}">

                                            {{ $value->page_name }}
                                    </button>                                            
                                </div>
                                
                                <div id="collapse{{ $value->id }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $value->id }}" data-bs-parent="#accordionExample">                                
                                    <div class="accordion-body">                                                
                                        {!! $value->content !!}

                                        <div class="flex">
                                            <a href="javascript:void(0)"
                                                class="editPage btn btn-outline-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#createPageModal"
                                                data-action="{{ route('pages.update', $value->id) }}"
                                                data-method="PUT"
                                                data-title="Edit Page"
                                                data-button="Update Page"
                                                data-page_name="{{ $value->page_name }}"
                                                data-page_slug="{{ $value->page_slug }}"
                                                data-content="{{ $value->content }}" >
                                                Edit
                                            </a>

                                            <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm commonDeleteBtn"
                                                data-bs-toggle="modal" data-bs-target="#commonDeleteModal"
                                                data-url="{{ route('pages.delete', $value->id) }}" data-title="{{ $value->page_name }}">
                                                Delete
                                            </a>
                                        </div>
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
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#{{ $permissionForm['modal_id'] }}">{{ $permissionForm['button_modal'] }}</button>                                
                    </div>
                </div>  
                
                <div class="card-small">  
                    @if($permissions->isNotEmpty())
                        @foreach ($permissions as $value)                    
                        <div class="card-small-body">
                            <div>{{ ucwords($value->name) }}</div>
                            <div class="flex">
                                @can('edit permissions')
                                    <a href="{{ route("permissions.edit", $value->id) }}" class="edit-icon">
                                        <span class="sprites"></span>
                                    </a>
                                @endcan
                                @can('delete permissions')
                                    <a href="javascript:void(0)" class="delete-icon commonDeleteBtn"
                                        data-bs-toggle="modal" data-bs-target="#commonDeleteModal"
                                        data-url="{{ route('permissions.destroy', $value->id) }}" data-title="{{ ucwords($value->name) }}">
                                        <span class="sprites"></span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                @endif                 
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
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#{{ $roleForm['modal_id'] }}">{{ $roleForm['button_modal'] }}</button>                                
                </div>
            </div>

            <div class="accordion mt-2" id="accordionExample">            
                @if($roles->isNotEmpty())
                    @foreach ($roles as $key => $value)
                        <div class="accordion-item">                                    
                            <h5 class="accordion-header" id="heading{{ $value->id }}">
                                <button class="accordion-button {{ request('open') != $value->id ? 'collapsed' : '' }}"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $value->id }}"
                                    aria-expanded="{{ request('open') == $value->id ? 'true' : 'false' }}"
                                    aria-controls="collapse{{ $value->id }}"
                                    data-id="{{ $value->id }}">

                                    {{ ucfirst($value->name) }}
                                    @if($value->name !== 'superadmin')
                                        - <span class="counts">{{ $value->permissions->count('name') }}</span>
                                    @endif                                        
                                </button>
                            </h5>                            

                            <div id="collapse{{ $value->id }}" class="accordion-collapse collapse {{ request('open') == $value->id ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $value->id }}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">                                       
                                    <p>
                                        @if($value->name !== 'superadmin')
                                            {{ ucwords($value->permissions->pluck('name')->implode(", ")) }}
                                        @endif
                                    </p>        
                                                                        
                                    <p class="text-muted">{{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}</p>

                                    <div class="flex">   
                                        @if($value->name !== 'superadmin')                                            
                                            @can('edit roles')
                                                <a href="{{ route("roles.edit", $value->id) }}" class="btn btn-outline-primary btn-sm">
                                                    Edit
                                                </a>
                                            @endcan
                                            @can('delete roles')
                                                <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm commonDeleteBtn"
                                                    data-bs-toggle="modal" data-bs-target="#commonDeleteModal"
                                                    data-url="{{ route('roles.destroy', $value->id) }}" data-title="{{ ucfirst($value->name) }}">
                                                    Delete
                                                </a>
                                            @endcan                                      
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>                   
                    @endforeach
                @endif
            </div>
        </div>

        <div class="tab-pane" id="tabs-6" role="tabpanel">
            <div class="row mt-3">                
                <div class="col-md-7 col-12">
                    <div class="page-title"> 
                        <h4>Users</h4>
                        <span class="counts">{{ $userCounts }}</span>
                    </div>
                </div>
                <div class="col-md-5 col-12">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#{{ $roleForm['modal_id'] }}">{{ $roleForm['button_modal'] }}</button>
                </div>
            </div>

            <div class="table-responsive mt-2">
                <table class="table mb-0 checkbox-all dataTable-table">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-0" data-sortable="" style="width: 18.5247%;">
                                <a href="#" class="dataTable-sorter">Customer</a>
                            </th>
                            <th data-sortable="" style="width: 27.4937%;">
                                <a href="#" class="dataTable-sorter">Email</a>
                            </th>
                            <th data-sortable="" style="width: 17.0997%;">
                                <a href="#" class="dataTable-sorter">Phone No</a>
                            </th>
                            <th data-sortable="" style="width: 13.3277%;">
                                <a href="#" class="dataTable-sorter">Status</a>
                            </th>
                            <th class="text-end" data-sortable="" style="width: 10.3101%;">
                                <a href="#" class="dataTable-sorter">Action</a>
                            </th>
                        </tr>
                    </thead>                  
                    <tbody>                    
                        @if($users->isNotEmpty())
                            @foreach ($users as $value)
                            <tr>                               
                                <td class="ps-0">
                                    <img src="assets/images/users/avatar-2.jpg" alt="" class="thumb-md d-inline rounded-circle me-1">
                                    <p class="d-inline-block align-middle mb-0">
                                        <span class="font-13 fw-medium">{{ $value->name }}</span> 
                                    </p>
                                </td>
                                <td>
                                    <a href="" class="d-inline-block align-middle mb-0 text-body">{{ $value->email }}</a> 
                                </td>
                                <td>{{ $value->mobile }}</td>
                                <td>
                                    @if($value->status == 1)
                                       <span class="badge bg-success-subtle text-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                    @endif                                    
                                </td>                                
                                <td class="text-end">                                        
                                    @if($value->name !== 'superadmin')                                    
                                        @can('edit users')
                                            <a href="{{ route("users.edit", $value->id) }}"><i class="las la-pen text-secondary fs-18"></i></a>                                            
                                        @endcan  
                                        @can('delete users')
                                            <a href="javascript:void(0)" class="commonDeleteBtn"
                                                data-bs-toggle="modal" data-bs-target="#commonDeleteModal"
                                                data-url="{{ route('users.destroy', $value->id) }}" data-title="{{ $value->name }}">
                                                <i class="las la-trash-alt text-secondary fs-18"></i>
                                            </a>
                                        @endcan
                                    @endif
                                </td>
                            </tr>                               
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
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
      
    $(document).on("click", ".user_dialog", function () {
        alert("H");
        var UserName = $(this).data('id');
        $(".modal-body #user_name").val( UserName );
    });      

    $(document).ready(function () {
        $('.green').addClass('blink');
    });

    $('.accordion-button').on('click', function () {
        let id = $(this).data('id');
        let url = new URL(window.location.href);

        url.searchParams.set('open', id);
        window.history.replaceState({}, '', url);
    });
</script>
@endsection