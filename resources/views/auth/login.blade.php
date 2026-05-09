<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }} ">
<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="font-sans antialiased">

    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                    <div class="text-center p-3">                                        
                                        <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Let's Get Restaurant</h4>   
                                        <p class="text-muted fw-medium mb-0">Sign in to continue to Restaurant</p>  
                                    </div>
                                </div>
                                <div class="card-body pt-0"> 
                                    <form method="POST" action="{{ route('login') }}" class="my-4">
                                        @csrf
                                        
                                        <div class="form-group mb-2">
                                            <label for="email" class="form-label" >Email</label>
                                            <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>
                                        
                                        <div class="form-group mb-2">
                                            <label for="password" class="form-label">Password</label>
                                            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <label for="remember_me" class="inline-flex items-center">
                                                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                                                </label>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                @if (Route::has('password.request'))
                                                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                                        {{ __('Forgot your password?') }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                                                                
                                        <button class="btn btn-primary">Log in <i class="fas fa-sign-in-alt ms-1"></i></button>
                                    </form>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>                                        
    </div>
</body>
</html>