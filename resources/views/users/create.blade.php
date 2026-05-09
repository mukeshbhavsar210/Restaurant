@extends('layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Create User</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @include('layouts.message')
            <form action="{{ route('users.store') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" >Name</label>
                                    <input value="{{ old('name') }}" name="name" placeholder="name" type="text" class="form-control"/>
                                    @error('name')
                                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" >Email</label>
                                    <input value="{{ old('email') }}" name="email" placeholder="email" type="text" class="form-control"/>
                                    @error('email')
                                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" >Password</label>
                                    <input value="{{ old('password') }}" name="password" placeholder="Password" type="password" class="form-control"/>
                                    @error('password')
                                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password" >Confirm Password</label>
                                    <input value="{{ old('confirm_password') }}" name="confirm_password" placeholder="Confirm Password" type="password" class="form-control"/>
                                    @error('confirm_password')
                                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @if($roles->isNotEmpty())
                                @foreach ($roles as $value)
                                    <div class="col-md-2">
                                        <input  type="checkbox" id="role-{{ $value->id }}" class="rounded" name="role[]" value="{{ $value->name }}" />
                                        <label for="role-{{ $value->id }}">{{ $value->name }}</label>
                                    </div>        
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
        