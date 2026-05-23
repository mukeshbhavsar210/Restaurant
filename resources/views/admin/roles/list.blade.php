@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Roles</h4>
                </div>
                <div class="col-sm-6 text-right">                    
                    @can('create roles')
                        <a href="{{ route('roles.create') }}" class="btn btn-primary float-end">Create</a>    
                    @endcan                
                </div>
            </div>        

        <div class="table-responsive mt-2">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-top-0">Name</th>                        
                        <th class="border-top-0" width="150">Date</th>
                        <th class="border-top-0 text-end" width="100">Action</th>
                    </tr>
                </thead>                     
                <tbody>
            @if($roles->isNotEmpty())
                @foreach ($roles as $value)                    
                    <tr>
                        <td>
                            <h5 class="mb-1">{{ ucfirst($value->name) }}</h5>
                            @if($value->name == 'superadmin')
                                <p class="text-muted">All permissions</p>
                            @else
                                <p class="text-muted">{{ $value->permissions->pluck('name')->map(fn($name) => ucwords($name))->implode(', ') }}</p>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}</td>
                        <td>
                            <div class="flex">
                                @can('edit roles')
                                    <a href="{{ route("roles.edit", $value->id) }}" class="edit-icon">
                                        <span class="sprites"></span>
                                    </a>
                                @endcan
                                @can('delete roles')
                                    <a href="javascript:void(0)" onclick="deleteRole({{ $value->id }})" class="delete-icon">
                                        <span class="sprites"></span>
                                    </a>
                                @endcan                                
                            </div>                            
                        </td>                        
                    </tr>
                @endforeach
            @endif             
        </tbody>
        </table>             
            {{ $roles->links() }}        
    </div>
</div>
</div>
</div>
@endsection
    
@section('customJs')
    <script type="text/javascript">
        function deleteRole(id) {
            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    url: '{{ route("roles.destroy") }}',
                    type: 'delete',
                    data: {id:id},
                    dataType: 'json',                    
                    headers: {
                        'x-csrf-token' : '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        window.location.href="{{ route('configurations.index') }}"
                    }
                });
            }
        }
    </script>
@endsection