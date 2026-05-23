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
            <div class="flex-2">
                <h4>Edit Permission</h4>            
                <a href="{{ route('configurations.index') }}" >Back</a>
            </div>
                    
            <form action="{{ route('permissions.update',$permission->id) }}" method="post">
                @csrf
                <div class="row mt-2">
                    <div class="col-md-3">                    
                        <div class="form-group">
                            <label for="" >Name</label>
                            <input value="{{ old('name', $permission->name) }}" name="name" placeholder="Permission name" type="text" class="form-control"/>
                            @error('name')
                                <p class="text-red-400 font-small">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col">
                        <button class="btn btn-primary mt-3">Update</button>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>
@endsection