@extends('layout.blankLayout')

@section('title', 'Login')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('content')
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">

                <!-- Login -->
                <div class="card p-2">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="{{ route('login') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros')</span>

                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body mt-2">
                        <h4 class="mb-2">Welcome to {{ config('variables.templateName') }}! 👋</h4>
                        <p class="mb-4">Please sign-in to your account and start the adventure</p>
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-floating form-floating-outline mb-3">
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter your email" value="{{ old('email') }}" autofocus autocomplete="username" />
                                <label for="email">Email</label>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <div class="form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password" autocomplete="current-password"/>
                                            <label for="password">Password</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
                                        </div>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                            </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                    <label class="form-check-label" for="remember-me">
                                        Remember Me
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="float-end mb-1">
                                    <span>Forgot Password?</span>
                                </a>
                                @endif
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary d-grid w-100">Sign in</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Login -->
                <img src="{{ asset('assets/img/illustrations/tree-3.png') }}" alt="auth-tree"
                    class="authentication-image-object-left d-none d-lg-block">
                <img src="{{ asset('assets/img/illustrations/auth-basic-mask-light.png') }}"
                    class="authentication-image d-none d-lg-block" alt="triangle-bg">
                <img src="{{ asset('assets/img/illustrations/tree.png') }}" alt="auth-tree"
                    class="authentication-image-object-right d-none d-lg-block">
            </div>
        </div>
    </div>
@endsection

