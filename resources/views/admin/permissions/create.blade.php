@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Create Permission</h4>
                </div>
                <div class="col-sm-6 text-right">
                    @can('create permissions')
                        <a href="{{ route('permissions.index') }}" class="btn btn-primary float-end">Back</a>
                    @endcan                
                </div>
            </div>
        
            <form action="{{ route('permissions.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="name" >Name</label>
                        <input value="{{ old('name') }}" name="name" placeholder="Permission name" type="text" class="form-control"/>
                        @error('name')
                            <p class="text-red-400 font-small">{{ $message }}</p>
                        @enderror
                </div>
                <button class="btn btn-primary">Submit</button>                
            </form>
            </div>
        </div>
    </div>    
@endsection