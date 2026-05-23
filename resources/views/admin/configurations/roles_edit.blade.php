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
            <div class="flex-2">
                <h4>Edit {{ ucfirst(old('name', $role->name)) }} role</h4>            
                <a href="{{ route('configurations.index') }}" >Back</a>
            </div>
        
            <form action="{{ route('roles.update',$role->id) }}" method="post">
                @csrf
                    <div class="form-group">                            
                        <label for="" >Name</label>
                        <input value="{{ old('name', $role->name) }}" name="name" placeholder="Permission name" type="text" class="form-control"/>
                        @error('name')
                            <p class="text-red-400 font-small">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="row mt-2">
                        @if($permissions->isNotEmpty())
                            @foreach ($permissions as $value)
                                <div class="col-md-3 mb-1">
                                    <label class="custom-checkbox" for="permission_{{ $value->id }}">
                                        <input {{ ($hasPermissions->contains($value->name)) ? 'checked' : '' }} type="checkbox" id="permission_{{ $value->id }}" class="rounded" name="permission[]" value="{{ $value->name }}" />
                                        <span class="checkmark"></span>
                                        {{ $value->name }}
                                    </label>
                                </div>        
                            @endforeach
                        @endif
                    </div>
                
                    <button class="btn btn-primary mt-3">Update</button>                    
                </form> 
            </div>               
        </div>
    </div>
@endsection
