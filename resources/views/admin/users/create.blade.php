@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">        
        <div class="row">
            <div class="col-sm-6">
                <h4>Create User</h4>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('users.index') }}" class="btn btn-primary float-end">Back</a>
            </div>
        </div>
            
        <form action="{{ route('users.store') }}" method="post">
            @csrf                
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" >Name</label>
                        <input value="{{ old('name') }}" name="name" placeholder="name" type="text" class="form-control"/>
                        @error('name')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" >Email</label>
                        <input value="{{ old('email') }}" name="email" placeholder="email" type="text" class="form-control"/>
                        @error('email')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="password" >Password</label>
                        <input value="{{ old('password') }}" name="password" placeholder="Password" type="password" class="form-control"/>
                        @error('password')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="confirm_password" >Confirm Password</label>
                        <input value="{{ old('confirm_password') }}" name="confirm_password" placeholder="Confirm Password" type="password" class="form-control"/>
                        @error('confirm_password')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex-2 mt-2">
                @if($roles->isNotEmpty())
                    @foreach ($roles as $value)                        
                        <label class="custom-checkbox" for="role-{{ $value->id }}">
                            <input type="checkbox" id="role-{{ $value->id }}" class="rounded" name="role[]" value="{{ $value->name }}" />                            
                            <span class="checkmark"></span>
                            {{ $value->name }}
                        </label>                        
                    @endforeach
                @endif
            </div>
        
            <button class="btn btn-primary mt-3">Create</button>
        </form>
    </div>
</div>    
@endsection