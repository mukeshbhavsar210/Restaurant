@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Articles</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('articles.create') }}" class="btn btn-primary float-end">Create</a>
                    @can('create articles')
                        
                    @endcan                
                </div>
            </div>
                  
            <div class="table-responsive mt-2">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th>Title</th>                        
                            <th>Author</th>    
                            <th width="250">Created</th>
                            <th>Action</th>                                
                        </tr>
                    </thead>
                    <tbody>
                        @if($articles->isNotEmpty())
                            @foreach ($articles as $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->title }}</td>
                                    <td>{{ $value->author }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}
                                    </td>
                                    <td>
                                        <div class="flex">
                                            <a href="{{ route("articles.edit", $value->id) }}" class="edit-icon">
                                                <span class="sprites"></span>
                                            </a>
                                            <a href="javascript:void(0)" onclick="deleteArticle({{ $value->id }})" class="delete-icon">
                                                <span class="sprites"></span> 
                                            </a>              
                                        </div>  
                                        {{-- @can('edit articles')
                                            <a href="{{ route("articles.edit", $value->id) }}" class="btn-primary btn">Edit</a>    
                                        @endcan
                                        @can('delete articles')
                                            <a href="javascript:void(0)" onclick="deleteArticle({{ $value->id }})" class="btn btn-danger">Delete</a>    
                                        @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif                            
                    </tbody>
                </table>
            </div>

            <div class="my-3">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
@endsection
        
@section('customJs')
    <script type="text/javascript">
        function deleteArticle(id) {
            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    url: '{{ route("articles.destroy") }}',
                    type: 'delete',
                    data: {id:id},
                    dataType: 'json',                    
                    headers: {
                        'x-csrf-token' : '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        window.location.href="{{ route('articles.index') }}"
                    }
                });
            }
        }
    </script>
@endsection