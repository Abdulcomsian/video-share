@extends('layout.dashboard-master')
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
@endsection