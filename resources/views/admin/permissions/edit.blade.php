@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">
    <div class="card-body">
        <div class="row">                
            <div class="col-md-10 col-12">
                <div class="page-title"> 
                    <h4>Edit Permission</h4>                    
                </div>
            </div>
            <div class="col-md-2 col-12 float-end">  
                <a href="{{ route('configurations.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>    

        <form action="{{ route('permissions.update',$permission->id) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="" >Name</label>
                        <input value="{{ old('name', $permission->name) }}" name="name" placeholder="Permission name" type="text" class="form-control"/>
                        @error('name')
                            <p class="text-red-400 font-small">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary mt-4">Update</button>    
                </div>
            </div>
        </form>        
    </div>
</div>
@endsection