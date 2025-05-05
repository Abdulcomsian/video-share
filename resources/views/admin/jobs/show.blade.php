@extends('layouts/contentNavbarLayout')

@section('title', 'Job Details')

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Jobs /</span> View Details
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        Title : {{ $job->title }}
                    </h4>

                </div>

                <div class="card-body">

                    <hr>

                    {{-- Payment Details --}}
                    <div class="row mb-4">
                        <div class="col-6">
                            <h5>Invoice Details</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="mb-1"><strong>Invoice Date:</strong>
                                        {{ $invoice->invoice_date->format('d-m-Y') }}</p>
                                    <p class="mb-1"><strong>Currency:</strong> {{ $invoice->currency }}</p>
                                    <p class="mb-1"><strong>Total Items:</strong> {{ $invoice->total_items }}</p>
                                    <p class="mb-1"><strong>Allow Tip:</strong> <span
                                            class="badge bg-label-{{ $invoice->is_allow_tips ? 'success' : 'danger' }} rounded-pill">
                                            {{ ucfirst($invoice->is_allow_tips ? 'yes' : 'no') }}
                                        </span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            @if ($invoice->payment)
                                <h5>Payment Details</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="mb-1"><strong>Payment Date:</strong>
                                            {{ $invoice->payment->created_at->format('d-m-Y') }}</p>
                                        <p class="mb-1"><strong>Subtotal:</strong> {{ formatNumber($invoice->subtotal) }}
                                        </p>
                                        <p class="mb-1"><strong>Tax:</strong> {{ formatNumber($invoice->total_tax) }}</p>
                                        <p class="mb-1"><strong>Tip Amount:</strong>
                                            {{ formatNumber($invoice->payment->tip_amount) }}</p>
                                        <p class="mb-1"><strong>Discount:</strong>
                                            {{ formatNumber($invoice->payment->discount_percentage) }}%</p>
                                        <p class="mb-1"><strong>30 Account (Points Redeemed):</strong>
                                            {{ formatNumber($invoice->payment->thirty_account_points_redeemed) }}pts(${{ formatNumber(convertPointsIntoAmount($invoice->payment->thirty_account_points_redeemed)) }})
                                        </p>
                                        <p class="mb-1"><strong>Cashback Used:</strong>
                                            {{ formatNumber($invoice->payment->cashback_points_redeemed) }}pts(${{ formatNumber(convertPointsIntoAmount($invoice->payment->cashback_points_redeemed)) }})
                                        </p>
                                        <p class="mb-1"><strong>Platform Fee:</strong>
                                            {{ formatNumber($invoice->payment->platform_fee) }}%</p>
                                    </div>
                                    <div class="col-md-12">
                                        @if ($stripePayment)
                                            <h5>Payment Method</h5>
                                            <div class="card p-3 bg-primary text-white">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Card Type:</strong>
                                                            {{ ucfirst($stripePayment['card']['brand'] ?? '-') }}</p>
                                                        <p class="mb-1"><strong>Card Last 4:</strong> **** **** ****
                                                            {{ $stripePayment['card']['last4'] ?? '-' }}</p>
                                                        <p class="mb-1"><strong>Expiry:</strong>
                                                            {{ $stripePayment['card']['exp_month'] ?? '--' }}/{{ $stripePayment['card']['exp_year'] ?? '--' }}
                                                        </p>
                                                        <p class="mb-1"><strong>Country:</strong>
                                                            {{ $stripePayment['card']['country'] ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>

                    <hr />

                    {{-- Invoice Items --}}
                    <div class="mb-4">
                        <h5>Invoice Items</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Item Name</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Tax</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoice->items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->item->category->name }}</td>
                                            <td>{{ $item->item->name }}</td>
                                            <td class="text-center">{{ $item->qty }}</td>
                                            <td class="text-center">{{ formatNumber($item->price) }}</td>
                                            <td class="text-center">{{ formatNumber($item->tax) }}</td>
                                            <td class="text-center">{{ formatNumber($item->amount) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No items found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    {{-- Grand Total Summary --}}
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Summary</h5>
                                    <ul class="list-unstyled">
                                        <li class="d-flex justify-content-between">
                                            <span>Subtotal:</span>
                                            <span>${{ formatNumber($invoice->subtotal) }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <span>Tax:</span>
                                            <span>${{ formatNumber($invoice->total_tax) }}</span>
                                        </li>
                                        @if ($invoice->payment)
                                            <li class="d-flex justify-content-between">
                                                <span>Tip:</span>
                                                <span>${{ formatNumber($invoice->payment->tip_amount) }}</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span>Discount:</span>
                                                <span>{{ formatNumber($invoice->payment->discount_percentage) }}%</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span>Platform Fee:</span>
                                                <span>{{ formatNumber($invoice->payment->platform_fee) }}%</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span>30 Account (Points Redeemed):</span>
                                                <span>
                                                    {{ formatNumber($invoice->payment->thirty_account_points_redeemed) }}pts(${{ formatNumber(convertPointsIntoAmount($invoice->payment->thirty_account_points_redeemed)) }})</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span>Cashback Used:</span>
                                                <span>{{ formatNumber($invoice->payment->cashback_points_redeemed) }}pts(${{ formatNumber(convertPointsIntoAmount($invoice->payment->cashback_points_redeemed)) }})</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span><strong>Total Payment:</strong></span>
                                                <span><strong>${{ formatNumber($invoice->payment->grand_total) }}</strong></span>
                                            </li>
                                        @endif
                                    </ul>
                                    @if ($invoice->thirtyAccountPoints && $invoice->seventyAccountPoints)
                                        <hr />
                                        <ul class="list-unstyled">
                                            <li class="d-flex justify-content-between">
                                                <span><strong>Points Earned:</strong></span>
                                                <span><strong>{{ formatNumber($invoice->thirtyAccountPoints->points + $invoice->seventyAccountPoints->points) }}pts</strong></span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span><strong>30 Account:</strong></span>
                                                <span><strong>{{ formatNumber($invoice->thirtyAccountPoints->points) }}pts</strong></span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span><strong>70 Account:</strong></span>
                                                <span><strong>{{ formatNumber($invoice->seventyAccountPoints->points) }}pts</strong></span>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body mt-4">
                            <a href="{{ route('admin:invoices') }}" class="btn btn-primary btn-sm me-2">Invoices List</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
