@extends('layouts/contentNavbarLayout')

@section('title', 'Add Sercus Points')
@push('my-styles')
    <link rel="stylesheet" href="{{ asset('assets/libraries/parsely/parsley.css') }}" />
@endpush
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Settings /</span> Sercus Points
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        Available Points :
                        <span class="badge bg-label-primary rounded-pill">
                            {{ formatNumber($seventyPoolAvailablePoints ?? 0) }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin:store-sercus-points') }}" method="post" id="saveSercusPoints"
                        data-parsley-validate>
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-8">
                                <label for="points" class="form-label">Points</label>
                                <input id="points" class="form-control" type="number" name="points"
                                    value="{{ old('points') }}" required autofocus />

                                <x-input-error :messages="$errors->get('points')" class="mt-2" />
                            </div>
                            <div class="col-md-4">
                                <label for="addType" class="form-label">Type</label>
                                <select id="addType" class="form-select" name="type" required>
                                    <option value="">select</option>
                                    <option value="add" @selected(old('type') === 'add')>Add</option>
                                    <option value="remove" @selected(old('type') === 'remove')>Remove</option>
                                </select>

                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('my-script')
    <script src="{{ asset('assets/libraries/parsely/parsley.min.js') }}"></script>
    <script>
        $('#saveSercusPoints').parsley()
    </script>
@endpush
