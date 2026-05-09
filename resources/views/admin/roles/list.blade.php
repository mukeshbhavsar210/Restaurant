@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="row">
        <div class="col-sm-6">
            <h1>Roles <span class="count">{{ $totalRoles }}</span></h1>
        </div>
        <div class="col-sm-6 text-right">
            @can('create roles')
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createRole">Create Role</button>
            @endcan                
        </div>
    </div>
</section>

<div class="container-fluid">
    @include('admin.layouts.message')

    <div class="modal fade drawer right-align" id="createRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Roles</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('roles.store') }}" method="post">
                    @csrf
                    <div class="modal-body">  
                        <div class="form-group">
                            <label for="name">Role Name</label>
                            <input value="{{ old('name') }}" name="name" placeholder="Role name" type="text" class="form-control"/>
                            @error('name')
                                <p class="text-red-400 font-small">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="checkboxSelect">
                            <div class="form-group">
                                <label>Select permission</label>
                            </div>
                            <div class="btn-group" name="area_name" id="options" data-toggle="buttons">
                                @if($permissions->isNotEmpty())
                                    @foreach ($permissions as $value)
                                        <label class="btn btn-default" for="permission_{{ $value->id }}">{{ $value->name }}
                                            <input type="checkbox" name="permission[]" id="permission_{{ $value->id }}" class="btn-check" value="{{ $value->name }}">
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
    </div>

    <div id="accordion" class="accordion">
        <div class="card mb-0">
            @if($roles->isNotEmpty())
                @foreach ($roles as $value)
                    <div class="card-header collapsed" data-toggle="collapse" href="#collapse_{{ $value->id }}">
                        <div class="row">
                            <div class="col-md-11">
                                <a class="card-title">{{ $value->name }} 
                                    @if($value->name == 'Super Admin')
                                    <span class="count-sub">All permissions</span>
                                    @else
                                        <span class="count-sub">{{ $value->permissions->count('name') }}</span>
                                    @endif                                    
                                </a>
                            </div>
                            <div class="col-md-1">
                                @can('edit roles')
                                    <a href="{{ route("roles.edit", $value->id) }}">
                                        <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </a>
                                @endcan
                                @can('delete roles')
                                    <a href="javascript:void(0)" onclick="deleteRole({{ $value->id }})" class="text-danger w-4 h-4 mr-1">
                                        <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div id="collapse_{{ $value->id }}" class="card-body collapse" data-parent="#accordion" >
                        @if($value->name == 'Super Admin')
                            <p><b>You're Super Admin, So not required any permission</b></p>
                        @else
                            <p>{{ $value->permissions->pluck('name')->implode(", ") }}</p>
                        @endif
                            
                        <p>{{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}</p>
                    </div>
                @endforeach
            @endif  
        </div>
    </div>              
        <div class="my-3">
            {{-- {{ $roles->links() }} --}}
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
                        window.location.href="{{ route('roles.index') }}"
                    }
                });
            }
        }

        $(document).on("click", ".user_dialog", function () {
            alert("H");
            var UserName = $(this).data('id');
            $(".modal-body #user_name").val( UserName );
        });

    </script>
@endsection