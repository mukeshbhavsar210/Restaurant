@extends('layouts.app')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Profile</h1>
            </div>            
        </div>
    </div>
</section>

<div class="container-fluid">
    @include('layouts.message')
        <div class="card">
            <div class="card-body">
                @include('admin.profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @include('admin.profile.partials.update-password-form')
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @include('admin.profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
