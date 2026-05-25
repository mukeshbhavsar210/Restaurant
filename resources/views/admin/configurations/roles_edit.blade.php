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
                    'active' => true,
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
                    <a class="nav-link pt-0 {{ !empty($tab['active']) ? 'active' : '' }}" href="{{ route('configurations.index') }}" >
                        {{ $tab['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-3">        
            <div class="page-title">
                <h4>Roles</h4>
                <span class="counts">{{ $totalRoles }}</span>
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

            <div class="modal fade drawer right-align show" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit {{ ucwords(old('name', $role->name)) }} Role</h5>
                            <a href="{{ route('configurations.index') }}" class="btn-close"></a>                            
                        </div>
        
                        <form action="{{ route('roles.update',$role->id) }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">                            
                                    <label for="" >Name</label>
                                    <input value="{{ old('name', $role->name) }}" name="name" placeholder="Permission name" type="text" class="form-control"/>
                                    @error('name')
                                        <p class="text-red-400 font-small">{{ $message }}</p>
                                    @enderror
                                </div>

                                <label for="" >Permissions</label>
                                <div class="row mt-2">
                                    @if($permissions->isNotEmpty())
                                        @foreach ($permissions as $value)
                                            <div class="col-6 mb-1">
                                                <label class="custom-checkbox" for="permission_{{ $value->id }}">
                                                    <input {{ ($hasPermissions->contains($value->name)) ? 'checked' : '' }} type="checkbox" id="permission_{{ $value->id }}" class="rounded" name="permission[]" value="{{ $value->name }}" />
                                                    <span class="checkmark"></span>
                                                    {{ $value->name }}
                                                </label>
                                            </div>        
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Update Role</button>
                        </div>
                    </form> 
                </div>
            </div>
        </div>

<div class="modal-backdrop fade show"></div>
@endsection