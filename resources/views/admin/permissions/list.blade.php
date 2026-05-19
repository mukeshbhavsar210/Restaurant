@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body"> 
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active py-2" data-bs-toggle="tab" href="#tabs-1" role="tab" aria-selected="true">Permissions </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link py-2" data-bs-toggle="tab" href="#tabs-2" role="tab" aria-selected="true">Roles</a>
            </li>                        
        </ul>

       <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-md-7 col-12">     
                        <div class="page-title"> 
                            <h4>Permissions</h4>
                            <span class="counts">{{ $totalPermissions }}</span>
                        </div>
                    </div>

                    <div class="col-md-5 col-12 float-end">                
                        <div class="flexContainer">
                            <form action="" method="get" >
                                <div class="d-flex">
                                    <div class="card-title mr-3">
                                        <a href="javascript:0" onclick="window.location.href='{{ route('configurations.index') }}'" class="refresh-icon" >
                                            <span class="sprites"></span>                                            
                                        </button>
                                    </div>
                
                                    <div class="card-tools">
                                        <div class="input-group input-group searchMain">
                                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
                
                                            <div class="input-group-append">
                                                <button type="submit" class="btn">
                                                    <i class="iconoir-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <a href="javascript:0" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#addPermissionModal">Add Permission</a>
                        </div>                
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
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->guard_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}</td>
                                    <td class="text-end">                                
                                        <a href="{{ route("permissions.edit", $value->id) }}">
                                            <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                        </a>
                                    
                                        <a href="javascript:void(0)" onclick="deletePermission({{ $value->id }})" class="text-danger w-4 h-4 mr-1">
                                            <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>    
                                        
                                        {{-- @can('edit permissions')
                                            <a href="{{ route("permissions.edit", $value->id) }}">
                                                <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                            </a>
                                        @endcan   
                                        @can('delete permissions')
                                            <a href="javascript:void(0)" onclick="deletePermission({{ $value->id }})" class="text-danger w-4 h-4 mr-1">
                                                <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </a>    
                                        @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif                            
                    </tbody>
                    </table>                    
                    {{ $permissions->links() }}                    
                </div>
            </div>

            <div class="tab-pane" id="tabs-2" role="tabpanel">
                <div class="row mt-3">                
                    <div class="col-md-7 col-12">
                        <div class="page-title"> 
                            <h4>Roles</h4>
                            <span class="counts">{{ $totalRoles }}</span>
                        </div>
                    </div>
                    <div class="col-md-5 col-12">
                        <a href="javascript:0" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addRoleModal">Add Role</a>
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
                                            <a href="{{ route("roles.edit", $value->id) }}">
                                                <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                            </a>
                                            <a href="javascript:void(0)" onclick="deleteRole({{ $value->id }})" class="text-danger w-4 h-4 mr-1">
                                                <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                            {{-- @can('edit roles')
                                                <a href="{{ route("roles.edit", $value->id) }}">
                                                    <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                    </svg>
                                                </a>
                                            @endcan 
                                            @can('delete roles')
                                                <a href="javascript:void(0)" onclick="deleteRole({{ $value->id }})" class="text-danger w-4 h-4 mr-1">
                                                    <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </a>
                                            @endcan
                                            --}}
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

<div class="modal fade drawer right-align" id="addPermissionModal" tabindex="-1" aria-labelledby="addPermissionModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form {{ route('products.store') }} method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    Form                                                  
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade drawer right-align" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('roles.store') }}" method="post">
                @csrf
                <div class="modal-body">  
                    <div class="form-group">
                        <label for="name">Role Name</label>
                        <input value="{{ old('name') }}" name="name" placeholder="Role name" type="text" class="form-control"/>
                        @error('name')
                            <p class="text-red-400 font-small">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Select Permission</label>
                        @if($permissions->isNotEmpty())
                            @foreach ($permissions as $value)
                                <label class="custom-checkbox" for="permission_{{ $value->id }}">{{ $value->name }}                                        
                                    <input type="checkbox" name="permission[]" id="permission_{{ $value->id }}" class="btn-check" value="{{ $value->name }}">                                        
                                    <span class="checkmark"></span>
                                </label>
                            @endforeach
                        @endif                        
                    </div>
                </div>
                <div class="modal-footer">                    
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>            
        </div>
    </div>
</div>
@endsection
        
@section('customJs')
<script type="text/javascript">
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

     function deleteRole(id) {
        if (confirm("Are you sure you want to delete?")) {
            $.ajax({
                url: '{{ route("roles.destroy") }}',
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

    $(document).on("click", ".user_dialog", function () {
        alert("H");
        var UserName = $(this).data('id');
        $(".modal-body #user_name").val( UserName );
    });
</script>
@endsection