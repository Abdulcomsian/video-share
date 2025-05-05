@extends('layout.contentNavbarLayout')

@section('title', 'Job Details')

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Jobs /</span> View Details
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h4 class="mb-0">
                        Title : {{ $job->title }}
                    </h4>

                </div>

                <hr>
                <div class="card-body pt-0">

                    {{-- Job Details --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>Job Details</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="mb-1"><strong>Budget:</strong> {{ $job->budget }}</p>
                                    <p class="mb-1"><strong>Status:</strong> <span
                                        class="badge bg-label-{{ $job->status !== 'unawarded' ? 'success' : 'danger' }} rounded-pill">
                                        {{ ucfirst($job->status) }}
                                    </span></p>
                                    <p class="mb-1"><strong>Description:</strong> {{ $job->description }}</p>
                                    <p class="mb-1"><strong>Awarded Date:</strong> {{ $job->awarded_date }}</p>
                                    <p class="mb-1"><strong>Extended Delivery Date:</strong> {{ $job->extended_delivery_date }}</p>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>Client Details</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="mb-1"><strong>Name:</strong> {{ $job->user->full_name }}</p>
                                    <p class="mb-1"><strong>Email:</strong> {{ $job->user->email }}</p>
                                    <p class="mb-1"><strong>Coontact No:</strong> {{ $job->user->phone_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="mb-4">
                        <h5>Folder: {{ $job->jobFolder->name ?? '' }} / Files</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($job->jobFolder->files as $file)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $file->path }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2">No files found...</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="card-body mt-4">
                            <a href="{{ route('admin:jobs.list') }}" class="btn btn-primary btn-sm me-2">Jobs List</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
