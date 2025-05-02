@extends('layouts/contentNavbarLayout')

@section('title', 'Merchant Details')

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Merchants /</span> View Details
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body pb-0">
                    <div class="d-flex gap-4">
                        <img src="{{ $merchant->image_path }}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded"
                            id="uploadedAvatar" />
                        <div class="user-info-wrapper">
                            <div class="row">
                                <div class="col-7">
                                    <p class="mb-1"><strong>First Name:</strong> <span>{{ $merchant->first_name }}</span></p>
                                    <p class="mb-1"><strong>Last Name:</strong> <span>{{ $merchant->last_name }}</span></p>
                                    <p class="mb-1"><strong>Email:</strong> <span>{{ $merchant->email }}</span></p>
                                    <p class="mb-1"><strong>Contact No#:</strong> <span>{{ $merchant->country_code }}{{ $merchant->phone_number }}</span></p>
                                </div>
                                <div class="col-5">
                                    <p class="mb-1"><strong>DOB:</strong> <span>{{ $merchant->dob ? $merchant->dob->format('d-m-Y') : '' }}
                                    </span></p>
                                    <p class="mb-1"><strong>Gender:</strong> <span>{{ ucfirst($merchant->gender->value ?? '' ) }}</span></p>
                                    <p class="mb-1"><strong>Status:</strong> <span class="badge rounded-pill bg-label-{{ $merchant->is_active ? 'success' : 'danger' }} me-1 small-text">{{ $merchant->is_active ? 'Active' : 'Inactive' }}</span> <span class="">{{ ($merchant->status) }}</span></p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Address:</strong> <span>{{ $merchant->address }}</span></p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <hr />
                </div>
                <div class="card-header pt-0 pb-0">
                    <h5 class="">Sercus Details</h5>
                </div>
                <div class="card-body">
                    <div>
                        <span class="badge rounded-pill bg-label-primary me-1 small-text">Sercus Balance: <strong>{{ $merchant->total_merchant_sercus_balance }}</strong></span>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin:merchants') }}" class="btn btn-primary btn-sm me-2">Merchants List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
