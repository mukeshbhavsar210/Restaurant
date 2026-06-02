@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body">
        <div class="page-title"> 
            <h4>Profile</h4>
        </div>

        <div class="d-flex align-items-center mt-2">
            <div class="flex-shrink-0">
                <img src="{{ asset('uploads/users/' . auth()->user()->image) }}" alt="" class="rounded" style="width: 120px" />
            </div>
            <div class="flex-grow-1 ms-3 text-truncate">
                <h4 class="mb-1 mt-2 fw-semibold">{{ auth()->user()->name }}</h4>
                <p class="text-muted mb-0 font-13">Email: {{ auth()->user()->email }}</p>
                <p class="text-muted mb-1 font-13">Mobile: {{ auth()->user()->mobile }}</p>

                <a href="javascript:void(0)"
                    class="editProfile btn btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#updateProfileModal"
                    data-action="{{ route('profile.update') }}"
                    data-method="PUT"
                    data-title="Edit Profile"                          
                    data-name="{{ auth()->user()->name }}"
                    data-email="{{ auth()->user()->email }}"
                    data-mobile="{{ auth()->user()->mobile }}"                                                                            
                    >                            
                    Edit Profile
                </a>
            </div>
        </div>                     
    </div>
</div>

@include('components.common-modal', [
    'modal' => $profileForm,
])

@endsection    