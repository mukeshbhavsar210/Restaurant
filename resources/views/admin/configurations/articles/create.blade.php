@extends('layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Create Article</h1>
            </div>
            <div class="col-sm-6 text-right">
                @can('create articles')
                    <a href="{{ route('articles.index') }}" class="btn btn-primary">Back</a>
                @endcan                
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @include('layouts.message')
        <form action="{{ route('articles.store') }}" method="post">
            <div class="card">
                <div class="card-body">
                    @csrf
                    <div class="form-group">
                        <label for="" >Title</label><br />
                        <input value="{{ old('title') }}" name="title" placeholder="Title" type="text" class="form-control"/>
                        @error('title')
                            <p class="text-red-400 font-small">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="" >Content</label><br />
                        <textarea cols="10" rows="4" id="text" name="text" placeholder="Content" type="text" class="form-control">{{ old('text') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="" >Author</label><br />
                        <input value="{{ old('author') }}" name="author" placeholder="Author" type="text" class="form-control"/>
                        @error('author')
                            <p class="text-red-400 font-small">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection