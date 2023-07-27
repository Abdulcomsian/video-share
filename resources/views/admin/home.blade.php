@extends('layout.dashboard-master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card-counter dashboard-card">
                  <i class="fas fa-folder-open"></i>
                  <span class="count-numbers">12</span>
                  <span class="count-name">Flowz</span>
                </div>
              </div>
          
              <div class="col-md-3">
                <div class="card-counter dashboard-card">
                    <i class="fas fa-file-video"></i>
                  <span class="count-numbers">599</span>
                  <span class="count-name">Instances</span>
                </div>
              </div>
          
              <div class="col-md-3">
                <div class="card-counter dashboard-card">
                    <i class="fas fa-photo-video"></i>
                  <span class="count-numbers">6875</span>
                  <span class="count-name">Data</span>
                </div>
              </div>
          
              <div class="col-md-3">
                <div class="card-counter dashboard-card">
                  <i class="fa fa-users"></i>
                  <span class="count-numbers">35</span>
                  <span class="count-name">Users</span>
                </div>
              </div>
              
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card-counter dashboard-card">
                    <i class="fas fa-briefcase"></i>
                  <span class="count-numbers">35</span>
                  <span class="count-name">Users</span>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card-counter dashboard-card">
                    <i class="far fa-handshake"></i>
                  <span class="count-numbers">35</span>
                  <span class="count-name">Users</span>
                </div>
              </div>
        </div>
    </div>
@endsection