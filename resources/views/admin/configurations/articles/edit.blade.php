@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Edit Article</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('articles.index') }}" class="btn btn-primary float-end">Back</a>
                </div>
            </div>
                        
            <form action="{{ route('articles.update',$article->id) }}" method="post" class="mt-2">
                @csrf                    
                <div class="form-group">
                    <label for="" >Name</label>
                    <input value="{{ old('title', $article->title) }}" name="title" placeholder="Title" type="text" class="form-control"/>
                    @error('title')
                        <p class="text-red-400 font-small">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="" >Content</label>
                    <textarea cols="10" rows="4" id="text" name="text" placeholder="Content" type="text" class="form-control">{{ old('text', $article->text) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="" >Author</label>
                    <input value="{{ old('author', $article->author) }}" name="author" placeholder="Author" type="text" class="form-control"/>
                    @error('author')
                        <p class="text-red-400 font-small">{{ $message }}</p>
                    @enderror
                </div>
                <button class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>    
@endsection