{{-- @extends('layout.dashboard-master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card-counter dashboard-card">
                  <i class="fas fa-folder-open"></i>
                  <span class="d-flex flex-column text-center align-content-center">
                    <span class="count-numbers">{{$folderCount}}</span>
                    <span class="count-numbers"> Folder</span>
                  </span>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card-counter dashboard-card">
                    <i class="fas fa-file-video"></i>
                    <span class="d-flex flex-column text-center align-content-center">
                      <span class="count-numbers">{{$videoCount}}</span>
                      <span class="count-name">Videos</span>
                    </span>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card-counter dashboard-card">
                    <i class="fas fa-photo-video"></i>
                    <span class="d-flex flex-column text-center align-content-center">
                      <span class="count-numbers">{{$imageCount}}</span>
                      <span class="count-numbers"> Images</span>
                    </span>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card-counter dashboard-card">
                  <i class="fa fa-users"></i>
                  <span class="d-flex flex-column text-center align-content-center">
                    <span class="count-numbers">{{$userCount}}</span>
                    <span class="count-numbers"> Users</span>
                  </span>
                </div>
              </div>

        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card-counter dashboard-card">
                    <i class="fas fa-briefcase"></i>
                    <span class="d-flex flex-column text-center align-content-center">
                      <span class="count-numbers">{{$activeJobCount}}</span>
                      <span class="count-numbers"> Active Job</span>
                    </span>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card-counter dashboard-card">
                    <i class="far fa-handshake"></i>
                    <span class="d-flex flex-column text-center align-content-center">
                      <span class="count-numbers">{{$completedJobCount}}</span>
                      <span class="count-numbers"> Done Job</span>
                    </span>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card-counter dashboard-card">
                    <i class="fas fa-user-tie"></i>
                    <span class="d-flex flex-column text-center align-content-center">
                      <span class="count-numbers">{{$clientCount}}</span>
                      <span class="count-numbers"> Client</span>
                    </span>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card-counter dashboard-card">
                  <i class="fas fa-user-edit"></i>
                    <span class="d-flex flex-column text-center align-content-center">
                      <span class="count-numbers">{{$editorCount}}</span>
                      <span class="count-numbers"> Editor</span>
                    </span>
                </div>
              </div>

        </div>
    </div>
@endsection --}}

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
        <h4 class="card-title mb-4">Total Customers</h4>
        <h4 class="text-primary mb-2">{{ $totalCustomers ?? 0 }}</h4>
        <a href="#" class="btn btn-sm btn-primary">View Customers</a>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-4">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-4">Total Merchants</h4>
        <h4 class="text-primary mb-2">{{ $totalMerchants ?? 0 }}</h4>
        <a href="#" class="btn btn-sm btn-primary">View Merchants</a>
      </div>
    </div>
  </div>

</div>
@endsection
