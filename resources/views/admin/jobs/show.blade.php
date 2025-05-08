@extends('layout.contentNavbarLayout')

@section('title', 'Job Details')

@push('my-styles')
    <style>
        .fs-0-9 {
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Jobs /</span> View Details
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex flex-column justify-content-between pb-0">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="mb-2">
                                Title : {{ $job->title }}
                            </h4>
                            <p class="mb-1 fs-0-9"><strong>Budget:</strong> {{ $job->budget ?? 0 }}</p>
                            <p class="mb-1 fs-0-9"><strong>Awarded Budget:</strong>
                                {{ $job->acceptedRequest->proposal->bid_price ?? 0 }}</p>

                        </div>
                        <div class="col-12">
                            @forelse ($job->skills as $skill)
                                <span class="badge bg-label-primary my-1">{{ $skill->title }}</span>
                            @empty
                                <span class="badge bg-label-danger">No skills found...</span>
                            @endforelse
                        </div>
                    </div>

                </div>

                <hr>
                <div class="card-body pt-0">

                    {{-- Job Details --}}
                    <div class="row mb-4">
                        <div class="col-7">
                            <h5>Job Details</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="mb-1"><strong>Budget:</strong> {{ $job->budget ?? 0 }}</p>
                                    <p class="mb-1"><strong>Awarded Budget:</strong>
                                        {{ $job->acceptedRequest->proposal->bid_price ?? 0 }}</p>
                                    <p class="mb-1"><strong>Status:</strong> <span
                                            class="badge bg-label-{{ $job->status !== 'unawarded' && $job->status !== 'canceled' ? 'success' : 'danger' }} rounded-pill">
                                            {{ ucfirst($job->status) }}
                                        </span></p>
                                    <p class="mb-1"><strong>Awarded Date:</strong>
                                        {{ $job->awarded_date != '' ? date('d-m-Y', strtotime($job->awarded_date)) : '' }}
                                    </p>
                                    <p class="mb-1"><strong>Extended Delivery Date:</strong>
                                        {{ $job->extended_delivery_date }}</p>
                                    <p class="mb-1"><strong>Description:</strong> {{ $job->description }}</p>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-5">
                            <h5>Payment</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="mb-1"><strong>Client Released:</strong> <span
                                            class="badge bg-label-{{ $job->payment->client_transfer_status ? 'success' : 'danger' }} rounded-pill">
                                            {{ $job->payment->client_transfer_status ? 'YES' : 'NO' }}
                                        </span></p>
                                    <p class="mb-1"><strong>Client Released Date:</strong>
                                        {{ $job->client_payment_date != '' ? date('d-m-Y', strtotime($job->client_payment_date)) : '' }}
                                    </p>
                                    <p class="mb-1"><strong>Editor Received:</strong> <span
                                            class="badge bg-label-{{ $job->payment->editor_transfer_status ? 'success' : 'danger' }} rounded-pill">
                                            {{ $job->payment->editor_transfer_status ? 'YES' : 'NO' }}
                                        </span></p>
                                    <p class="mb-1"><strong>Editor Received Date:</strong>
                                        {{ $job->editor_payment_date != '' ? date('d-m-Y', strtotime($job->editor_payment_date)) : '' }}
                                    </p>
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
                                    <p class="mb-1"><strong>Contact No:</strong> {{ $job->user->phone_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr />

                    {{-- <div class="mb-4">
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
                    </div> --}}

                    <div class="mb-4">
                        <h5>Proposals</h5>
                        <div class="table-responsive" id="proposals-table-wrapper">
                            {{-- proposals will show here --}}
                        </div>
                    </div>

                    <hr>

                    <div class="card-body mt-4 px-0">
                        <a href="{{ route('admin:jobs.list') }}" class="btn btn-primary btn-sm me-2">Jobs List</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@push('my-script')
    <script>
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            fetchProposals(url);
        });
        let fetchProposalsUrl = "{{ route('admin:jobs.proposals-list', $job->id) }}";
        fetchProposals(fetchProposalsUrl);

        function fetchProposals(url) {
            $.ajax({
                url: url,
                success: function(data) {
                    $('#proposals-table-wrapper').html(data);
                },
                error: function() {
                    alert('Something went wrong while fetching proposals.');
                }
            });
        }

        $(document).on('click', '.proposal-toggle-details', function() {
            let index = $(this).data('index');
            let row = $('.proposal-details-row[data-index="' + index + '"]');
            row.slideToggle(); // Smooth toggle
        });
    </script>
@endpush
