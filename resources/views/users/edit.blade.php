@extends('layouts.app')

@section('content')

<section class="content-header">
    <div class="row">
        <div class="col-sm-6">
            <h1>Edit User</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>
</section>

<section class="content">   
@include('layouts.message')
    <form action="{{ route('users.update',$user->id) }}" method="post">
        @csrf
        <div class="card">
            <div class="card-body">
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

                <div class="row">
                    @if($roles->isNotEmpty())
                        @foreach ($roles as $value)
                            <div class="col-md-2">
                                <input {{ ($hasRoles->contains($value->id)) ? 'checked' : '' }} type="checkbox" id="role-{{ $value->id }}" class="rounded" name="role[]" value="{{ $value->name }}" />
                                <label for="role-{{ $value->id }}">{{ $value->name }}</label>
                            </div>        
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>        
</section>
@endsection
        