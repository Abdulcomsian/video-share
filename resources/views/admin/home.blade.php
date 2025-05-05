@extends('layout.contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')
<div class="row gy-4">
  <!-- Welcome card -->
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-1">Welcome {{ Auth::guard('web')->user()->full_name }}! </h4>
        <p class="pb-0">We’re glad to have you back!</p>
        <h4 class="text-primary mb-1">Let’s get started </h4>
        <p class="mb-2 pb-1">Explore your dashboard to manage your activities.</p>
      </div>
    </div>
  </div>
    <!--/ Welcome card -->
  <div class="col-md-6 col-lg-4">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Total Clients</h4>
        <h4 class="text-primary mb-2">{{ $clientCount }}</h4>
        {{-- <a href="#" class="btn btn-sm btn-primary">View Customers</a> --}}
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-4">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Total Editors</h4>
        <h4 class="text-primary mb-2">{{ $editorCount }}</h4>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-4">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Active Jobs</h4>
        <h4 class="text-primary mb-2">{{ $activeJobCount }}</h4>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-4">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Completed Jobs</h4>
        <h4 class="text-primary mb-2">{{ $completedJobCount }}</h4>
      </div>
    </div>
  </div>

</div>
@endsection
