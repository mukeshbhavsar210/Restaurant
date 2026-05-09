@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Users <span class="count">{{ $totalUsers }}</span></h1>
            </div>
            <div class="col-sm-6 text-right">
                @can('create users')
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createUser">Create User</button>
                @endcan
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container-fluid">
        @include('admin.layouts.message')

        <div class="modal fade drawer right-align" id="createUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('users.store') }}" method="post">
                        @csrf
                        <div class="modal-body">                  
                              <div class="form-group">
                                <label for="" >Name</label>
                                <input value="{{ old('name') }}" name="name" placeholder="name" type="text" class="form-control"/>
                                @error('name')
                                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                @enderror
                            </div>  
                            
                            <div class="form-group">
                                <label for="" >Email</label>
                                <input value="{{ old('email') }}" name="email" placeholder="email" type="text" class="form-control"/>
                                @error('email')
                                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" >Password</label>
                                        <input value="{{ old('password') }}" name="password" placeholder="Password" type="password" class="form-control"/>
                                        @error('password')
                                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="confirm_password" >Confirm Password</label>
                                        <input value="{{ old('confirm_password') }}" name="confirm_password" placeholder="Confirm Password" type="password" class="form-control"/>
                                        @error('confirm_password')
                                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="checkboxSelect">
                                <div class="form-group">
                                    <label>Select Role</label>
                                </div>
                                <div class="btn-group" name="area_name" id="options" data-toggle="buttons">
                                    @if($roles->isNotEmpty())
                                        @foreach ($roles as $value)
                                            <label class="btn btn-default" for="role_{{ $value->id }}">{{ $value->name }}
                                                <input type="checkbox" name="role[]" id="role_{{ $value->id }}" class="btn-check" value="{{ $value->name }}">
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
                                        <a href="{{ route("users.edit", $value->id) }}">
                                            <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                        </a>
                                    @endcan   
                                    @can('delete users')
                                        <a href="javascript:void(0)" onclick="deleteUser({{ $value->id }})" class="text-danger w-4 h-4 mr-1">
                                            <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>    
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