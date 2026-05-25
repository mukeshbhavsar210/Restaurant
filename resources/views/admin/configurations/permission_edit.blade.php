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
                    'active' => true,
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
                    <a class="nav-link pt-0 {{ !empty($tab['active']) ? 'active' : '' }}" href="{{ route('configurations.index', $tab['id']) }}" >
                        {{ $tab['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="mt-3">                    
            <div class="page-title">
                <h4>Permissions</h4>
                <span class="counts">{{ $totalPermissions }}</span>
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

            <div class="modal fade drawer right-align show" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Permission</h5>
                            <a href="{{ route('configurations.index') }}" class="btn-close"></a>                            
                        </div>
                    
                        <form action="{{ route('permissions.update',$permission->id) }}" method="post">
                            @csrf
                            <div class="modal-body">                                
                                <div class="form-group">
                                    <label for="" >Name</label>
                                    <input value="{{ old('name', $permission->name) }}" name="name" placeholder="Permission name" type="text" class="form-control"/>
                                    @error('name')
                                        <p class="text-red-400 font-small">{{ $message }}</p>
                                    @enderror
                                </div>                                
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary">Update Permission</button>
                            </div>                        
                        </form>            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-backdrop fade show"></div>
@endsection