{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password1') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}


@extends('layout.blankLayout')

@section('title', 'FORGOT PASSWORD')

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
@endsection

@section('content')
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">

                <!-- Forgot Password -->
                <div class="card p-2">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="{{ route('login') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros')</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <div class="card-body mt-2">
                        <h4 class="mb-2">Forgot Password? 🔒</h4>
                        <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-3" action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="form-floating form-floating-outline mb-3">
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter your email" autofocus>
                                <label>Email</label>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 mb-3" />
                            <button type="submit" class="btn btn-primary d-grid w-100">Send Reset Link</button>
                        </form>
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-chevron-left scaleX-n1-rtl mdi-24px"></i>
                                Back to login
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Forgot Password -->
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
