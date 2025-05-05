@extends('layout.contentNavbarLayout')

@section('title', 'Merchants List')

@push('my-styles')

    @include('_partials.dataTable-custom-css')

@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="py-3"><span class="text-muted fw-light">Manage Users /</span> Clients
            </h4>
        </div>

    </div>
    <!-- Hoverable Table rows -->
    <div class="card">
        <div class="card-body">

            <div class="table-responsive text-nowrap">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
    <!--/ Hoverable Table rows -->


@endsection


@push('my-script')


    <!-- DataTables JS -->
    @include('_partials.dataTable-cdns')

    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

@endpush
