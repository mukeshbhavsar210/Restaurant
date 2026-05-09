@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Edit Permission</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('permissions.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</section>

    <div class="container-fluid">
        @include('admin.layouts.message')
            <div class="card">
                <div class="card-body">
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
        </div>
@endsection