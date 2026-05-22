@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">    
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Edit Permission</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('roles.index') }}" class="btn btn-primary float-end">Back</a>
                </div>
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
