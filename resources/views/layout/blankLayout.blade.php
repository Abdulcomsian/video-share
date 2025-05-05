@extends('layout.commonMaster' )

@push('my-styles')

<style>
    .my-app-logo{
        width: 80px;
        height: 80px;
        object-fit: cover;
    }
</style>

@endpush
@section('layoutContent')

<!-- Content -->
@yield('content')
<!--/ Content -->

@endsection
