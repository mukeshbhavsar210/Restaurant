@extends('layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Users</h1>
            </div>
            <div class="col-sm-6 text-right">
                {{-- @can('create users') --}}
                    <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
                {{-- @endcan --}}
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        @include('layouts.message')
        <div class="card">
            <table class="table border">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>                        
                        <th>Email</th>    
                        <th>Roles</th> 
                        <th>Created</th>
                        <th>Action</th>                                
                    </tr>
                </thead>
                <tbody class="bg-white">                    
                    @if($users->isNotEmpty())
                        @foreach ($users as $value)
                            <tr>
                                <td>{{ $value->id }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->email }}</td>
                                <td>{{ $value->roles->pluck('name')->implode(', ') }}</td>
                                <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}</td>
                                <td>
                                    @can('edit users')
                                        <a href="{{ route("users.edit", $value->id) }}" class="btn-primary btn">Edit</a>
                                    @endcan   
                                    <a href="javascript:void(0)" onclick="deleteUser({{ $value->id }})" class="btn btn-danger">Delete</a>    
                                    @can('delete users')
                                        
                                    @endcan                                 
                                </td>
                            </tr>
                        @endforeach
                    @endif                            
                </tbody>
            </table>
            <div class="my-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
</section>
@endsection
    
@section('customJs')
<script type="text/javascript">
    function deleteUser(id) {
        if (confirm("Are you sure you want to delete?")) {
            $.ajax({
                url: '{{ route("users.destroy") }}',
                type: 'delete',
                data: {id:id},
                dataType: 'json',                    
                headers: {
                    'x-csrf-token' : '{{ csrf_token() }}'
                },
                success: function(response) {
                    window.location.href="{{ route('users.index') }}"
                }
            });
        }
    }
</script>
@endsection