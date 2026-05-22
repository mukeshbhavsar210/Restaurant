@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Edit User</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('users.index') }}" class="btn btn-primary float-end">Back</a>
                </div>
            </div>
        
            <form action="{{ route('users.update',$user->id) }}" method="post">
                @csrf
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" >Name</label><br />
                            <input value="{{ old('name', $user->name) }}" name="name" placeholder="name" type="text" class="form-control"/>
                            @error('name')
                                <p class="text-red-400 font-small">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" >Email</label><br />
                            <input value="{{ old('email', $user->email) }}" name="email" placeholder="email" type="text" class="form-control"/>
                            @error('email')
                                <p class="text-red-400 font-small">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex-2 mt-2">
                    @if($roles->isNotEmpty())
                        @foreach ($roles as $value)                            
                            <label class="custom-checkbox" for="role-{{ $value->id }}">
                            <input {{ ($hasRoles->contains($value->id)) ? 'checked' : '' }} type="checkbox" id="role-{{ $value->id }}" class="rounded" name="role[]" value="{{ $value->name }}" />
                                <span class="checkmark"></span>
                                {{ $value->name }}
                            </label>                            
                        @endforeach
                    @endif
                </div>            
            
                <button class="btn btn-primary mt-3">Update</button>
        </div>
    </form>
</div>    
@endsection
        