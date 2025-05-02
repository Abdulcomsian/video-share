@extends('layouts/contentNavbarLayout')

@section('title', 'Customer Details')

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Customers /</span> View Details
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body pb-0">
                    <div class="d-flex gap-4">
                        <img src="{{ $customer->image_path }}" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded"
                            id="uploadedAvatar" />
                        <div class="user-info-wrapper">
                            <div class="row">
                                <div class="col-7">
                                    <p class="mb-1"><strong>First Name:</strong> <span>{{ $customer->first_name }}</span></p>
                                    <p class="mb-1"><strong>Last Name:</strong> <span>{{ $customer->last_name }}</span></p>
                                    <p class="mb-1"><strong>Email:</strong> <span>{{ $customer->email }}</span></p>
                                    <p class="mb-1"><strong>Contact No#:</strong> <span>{{ $customer->country_code }}{{ $customer->phone_number }}</span></p>
                                </div>
                                <div class="col-5">
                                    <p class="mb-1"><strong>DOB:</strong> <span>{{ $customer->dob ? $customer->dob->format('d-m-Y') : '' }}
                                    </span></p>
                                    <p class="mb-1"><strong>Gender:</strong> <span>{{ ucfirst($customer->gender->value ?? '') }}</span></p>
                                    <p class="mb-1"><strong>Status:</strong> <span class="badge rounded-pill bg-label-{{ $customer->is_active ? 'success' : 'danger' }} me-1 small-text">{{ $customer->is_active ? 'Active' : 'Inactive' }}</span> <span class="">{{ ($customer->status) }}</span></p>
                                </div>
                                <div class="col-12">
                                    <p class="mb-1"><strong>Address:</strong> <span>{{ $customer->address }}</span></p>
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
                        <span class="badge rounded-pill bg-label-primary me-1 small-text">30 Account: <strong>{{ $customer->total_available_points }}</strong></span>
                        <span class="badge rounded-pill bg-label-primary me-1 small-text">70 Account: <strong>{{ $customer->total_available_points_seventy_account }}</strong></span>
                        <span class="badge rounded-pill bg-label-primary me-1 small-text">Sercus Balance: <strong>{{ $customer->total_customer_sercus_balance }}</strong></span>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin:customers') }}" class="btn btn-primary btn-sm me-2">Customers List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
