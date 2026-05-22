@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Users</h4>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('users.create') }}" class="btn btn-primary float-end">Create User</a>
                    @can('create users')
                        
                    @endcan
                </div>
            </div>
                       
            <div class="table-responsive mt-2">
                <table class="table mb-0">
                    <thead class="table-light">                
                        <tr>                            
                            <th>Name</th>                        
                            <th>Email</th> 
                            <th>Created</th>
                            <th>Action</th>                                
                        </tr>
                    </thead>
                    <tbody class="bg-white">                    
                        @if($users->isNotEmpty())
                            @foreach ($users as $value)
                                <tr>
                                    <td>
                                        <h5 class="mb-0">{{ $value->name }}</h5>
                                        <p class="text-muted">{{ $value->roles->pluck('name')->implode(', ') }}</p>
                                        <p class="text-muted tiny-font">{{ $value->id }}</p>
                                    </td>
                                    <td>{{ $value->email }}</td>                                    
                                    <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}</td>
                                    <td>
                                        <div class="flex">
                                            <a href="{{ route("users.edit", $value->id) }}" class="edit-icon">
                                                <span class="sprites"></span>
                                            </a>
                                            <a href="javascript:void(0)" onclick="deleteUser({{ $value->id }})" class="delete-icon">
                                                <span class="sprites"></span> 
                                            </a>              
                                        </div> 
                                        {{-- @can('edit users')
                                            <a href="{{ route("users.edit", $value->id) }}" class="btn-primary btn">Edit</a>
                                        @endcan   
                                        <a href="javascript:void(0)" onclick="deleteUser({{ $value->id }})" class="btn btn-danger">Delete</a>    
                                        @can('delete users')
                                            
                                        @endcan                                  --}}
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