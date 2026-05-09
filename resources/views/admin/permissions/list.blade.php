@extends('admin.layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-9">
                <h1>Permissions <span class="count">{{ $totalPermissions }}</span></h1>
            </div>
            <div class="col-sm-3">
                <a href="javascript:void(0)" class="addBtn btn btn-primary float-end">Add Permission</a>
                <div class="smallForm">
                    <form action="{{ route('permissions.store') }}" method="post" class="form">
                        @csrf
                        <div class="form-group">
                            <input value="{{ old('name') }}" name="name" placeholder="Permission name" type="text" class="form-control"/>
                            @error('name')
                                <p class="text-red-400 font-small">{{ $message }}</p>
                            @enderror
                            <button class="tickBtn">
                                <svg fill="#000000" width="23px" height="23px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12,1A11,11,0,1,0,23,12,11.013,11.013,0,0,0,12,1Zm0,20a9,9,0,1,1,9-9A9.011,9.011,0,0,1,12,21ZM17.737,8.824a1,1,0,0,1-.061,1.413l-6,5.5a1,1,0,0,1-1.383-.03l-3-3a1,1,0,0,1,1.415-1.414l2.323,2.323,5.294-4.853A1,1,0,0,1,17.737,8.824Z"/></svg>
                            </button>
                        </div>
                        <a href="javascript:void(0)" class="removeBtn" >
                            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.16998 14.83L14.83 9.17004" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14.83 14.83L9.16998 9.17004" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </form>
                </div>                
            </div>
        </div>
    </div>
</section>

<div class="container-fluid">
    @include('admin.layouts.message')
        <table class="table border">
            <thead>
                <tr>
                    <th>Name</th>                        
                    <th>Email</th>    
                    <th>Action</th>                                
                </tr>
            </thead>
            <tbody class="bg-white">                    
                @if($permissions->isNotEmpty())
                    @foreach ($permissions as $value)
                        <tr>
                            <td>{{ $value->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d M, Y') }}</td>
                            <td>
                                @can('edit permissions')
                                    <a href="{{ route("permissions.edit", $value->id) }}">
                                        <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </a>
                                @endcan   
                                @can('delete permissions')
                                    <a href="javascript:void(0)" onclick="deletePermission({{ $value->id }})" class="text-danger w-4 h-4 mr-1">
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
            {{ $permissions->links() }}
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