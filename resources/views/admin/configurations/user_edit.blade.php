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
                ],
                [
                    'id' => 'tabs-6',
                    'title' => 'Users',
                    'active' => true,
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
                <h4>Users</h4>
                <span class="counts">{{ $userCounts }}</span>
            </div>

            <div class="table-responsive mt-2">
                <table class="table mb-0 checkbox-all dataTable-table">
                    <thead class="table-light">
                        <tr>
                            <th>Customer</th>
                            <th>Phone No</a></th>
                            <th>Status</a></th>
                            <th>Action</th>
                        </tr>
                    </thead>                  
                    <tbody>                    
                        @if($users->isNotEmpty())
                            @foreach ($users as $value)
                                <tr>                               
                                    <td>
                                        <div class="product-row">
                                            <img src="assets/images/users/avatar-2.jpg" alt="" class="thumb-md d-inline rounded-circle me-3">
                                            <div class="flex-grow-2 text-truncate">
                                                <h5 class="mb-0">{{ ucfirst($value->name) }}</h5>
                                                <p class="text-muted tiny-font">{{ ucfirst($value->roles->pluck('name')->implode(', ')) }}</p>
                                            </div>
                                        </div>
                                    </td>                                
                                    <td>
                                        <p class="text-muted tiny-font">{{ $value->email }}</p>
                                        <p class="text-muted tiny-font">{{ $value->mobile }}</p>
                                    </td>
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
                                                <a href="{{ route("users.edit", $value->id) }}">
                                                    <i class="las la-pen text-secondary fs-18"></i>
                                                </a>
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
        
            <div class="modal fade drawer right-align show" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit User</h5>
                            <a href="{{ route('configurations.index') }}" class="btn-close"></a>                            
                        </div>

                        <form action="{{ route('users.update',$user->id) }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="" >Name</label><br />
                                    <input value="{{ old('name', $user->name) }}" name="name" placeholder="name" type="text" class="form-control"/>
                                    @error('name')
                                        <p class="text-red-400 font-small">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="" >Email</label><br />
                                    <input value="{{ old('email', $user->email) }}" name="email" placeholder="email" type="text" class="form-control"/>
                                    @error('email')
                                        <p class="text-red-400 font-small">{{ $message }}</p>
                                    @enderror
                                </div>
                                        
                                <label for="role" >Role</label><br />
                                <div class="row mt-2">                                    
                                    @if($roles->isNotEmpty())
                                        @foreach ($roles as $value)
                                            <div class="col-6 mb-1">
                                                <label class="custom-checkbox" for="role-{{ $value->id }}">
                                                <input {{ ($hasRoles->contains($value->id)) ? 'checked' : '' }} type="checkbox" id="role-{{ $value->id }}" class="rounded" name="role[]" value="{{ $value->name }}" />
                                                    <span class="checkmark"></span>
                                                    {{ ucfirst($value->name) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-backdrop fade show"></div>
@endsection  