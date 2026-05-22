@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Permissions</h4>
                </div>
                <div class="col-sm-6 text-right">                    
                    @can('create permissions')
                        <a href="{{ route('permissions.create') }}" class="btn btn-primary float-end">Create</a>    
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
                @if($permissions->isNotEmpty())
                    @foreach ($permissions as $value)
                        <tr>
                            <td>
                                <h5 class="mb-0">{{ $value->name }}</h5>
                                <p class="text-muted tiny-font">{{ $value->guard_name }}</p>
                            </td>                            
                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}</td>
                            <td class="text-end">
                                <div class="flex">
                                    <a href="{{ route("permissions.edit", $value->id) }}" class="edit-icon">
                                        <span class="sprites"></span>
                                    </a>
                                    <a href="javascript:void(0)" onclick="deletePermission({{ $value->id }})" class="delete-icon">
                                        <span class="sprites"></span> 
                                    </a>              
                                </div>                  

                                {{-- @can('edit permissions')
                                    <a href="{{ route("permissions.edit", $value->id) }}" class="btn-primary btn">Edit</a>
                                @endcan
                                @can('delete permissions')
                                    <a href="javascript:void(0)" onclick="deletePermission({{ $value->id }})" class="btn btn-danger">Delete</a>
                                @endcan --}}

                                {{-- <div class="flex">
                                    <a href="javascript:void(0)" class="edit-icon editPermissionModal"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#createPermissionModal"
                                        data-action="{{ route('permissions.update', $value->id) }}"
                                        data-permission_name="{{ $value->permission_name }}"
                                        data-button="Update Permission" >
                                        <span class="sprites"></span>                                            
                                    </a>

                                    <a href="javascript:void(0)" class="delete-icon commonDeleteBtn"
                                        data-bs-toggle="modal" data-bs-target="#commonDeleteModal"
                                        data-url="{{ route('permissions.delete', $value->id) }}" data-title="Permission">
                                        <span class="sprites"></span>
                                    </a>
                                </div> --}}
                            </td>
                        </tr>
                    @endforeach
                @endif                            
            </tbody>
        </table>  
            <div class="my-3">
                {{ $permissions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
        
@section('customJs')
<script type="text/javascript">
    function deletePermission(id) {
        if (confirm("Are you sure you want to delete?")) {
            $.ajax({
                url: '{{ route('permissions.destroy') }}',
                type: 'delete',
                data: {id:id},
                dataType: 'json',                    
                headers: {
                    'x-csrf-token' : '{{ csrf_token() }}'
                },
                success: function(response) {
                    window.location.href="{{ route('permissions.index') }}"
                }
            });
        }
    }
</script>
@endsection