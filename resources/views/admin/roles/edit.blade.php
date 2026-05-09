@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="row">
        <div class="col-sm-6">
            <h1>Edit Role</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('roles.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>    
</section>

<section>
@include('admin.layouts.message')
    <form action="{{ route('roles.update',$role->id) }}" method="post">
        @csrf
        <div class="card">
            <div class="card-body">        
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">                            
                            <label for="" >Name</label>
                            <input value="{{ old('name', $role->name) }}" name="name" placeholder="Permission name" type="text" class="form-control"/>
                            @error('name')
                                <p class="text-red-400 font-small">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="checkboxSelectEdit">
                    <div class="form-group">
                        <label>Select permission</label>
                    </div>
                    <div class="btn-group" name="area_name" id="options" data-toggle="buttons">
                        @if($permissions->isNotEmpty())
                            @foreach ($permissions as $value)
                                <label class="btn btn-default" for="permission_{{ $value->id }}">{{ $value->name }}
                                    <input {{ ($hasPermissions->contains($value->name)) ? 'checked' : '' }} type="checkbox" name="permission[]" id="permission_{{ $value->id }}" class="btn-check" value="{{ $value->name }}">
                                </label>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary">Update</button>
            </div>
        </form> 
    </div>               
</section>
@endsection
