@extends('layout.contentNavbarLayout')

@section('title', 'Profile')
@push('my-styles')
    <link rel="stylesheet" href="{{ asset('assets/libraries/parsely/parsley.css') }}" />
@endpush
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Profile /</span> Update Profile
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin:profile.update') }}" method="post" id="updateProfile"
                        data-parsley-validate>
                        @csrf
                        @method('patch')
                        <div class="row mb-2">
                            <div class="col-md-6 mb-2">
                                <label for="fullName" class="form-label">Name</label>
                                <input id="fullName" class="form-control" type="text" name="name"
                                    value="{{ old('name', $user->full_name) }}" required autofocus />

                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" class="form-control" type="email" name="email"
                                    value="{{ old('email', $user->email) }}" required />

                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                <input id="phoneNumber" class="form-control" type="text" name="phone_number"
                                    value="{{ old('phone_number', $user->phone_number) }}" required />

                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Update Password </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin:password.update') }}" method="post" id="updatePassword"
                        data-parsley-validate>
                        @csrf
                        @method('put')
                        <div class="row mb-2">
                            <div class="col-md-6 mb-2">
                                <label for="update_password_current_password" class="form-label">Current Password</label>
                                <input id="update_password_current_password" class="form-control" type="password" name="current_password" required autofocus />

                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="update_password_password" class="form-label">New Password</label>
                                <input id="update_password_password" class="form-control" type="password" name="password" required />

                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
                                <input id="update_password_password_confirmation" class="form-control" type="password" name="password_confirmation" required />

                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('my-script')
    <script src="{{ asset('assets/libraries/parsely/parsley.min.js') }}"></script>
    <script>
        $('#saveSercusPoints').parsley()
    </script>
@endpush

