<table class="table table-bordered align-middle">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th class="text-center">Budget</th>
            <th class="text-center">Status</th>
            {{-- <th class="text-center">Action</th> --}}
        </tr>
    </thead>
    <tbody>
        @forelse ($requests as $key => $editorRequest)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $editorRequest->editor->full_name ?? '' }}</td>
                <td>{{ $editorRequest->editor->email ?? '' }}</td>
                <td class="text-center">{{ $editorRequest->proposal->bid_price ?? 0 }}</td>
                <td class="text-center">
                    <span class="badge bg-label-{{ Helper::getProposalStatus($editorRequest->status) === 'unawarded' || Helper::getProposalStatus($editorRequest->status) === 'cancelled' ? 'danger' : 'success' }} rounded-pill">
                        {{ ucfirst(Helper::getProposalStatus($editorRequest->status)) }}
                    </span>
                </td>
                {{-- <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-sm btn-primary proposal-toggle-details" data-index="{{ $key }}">View</a>
                </td> --}}
            </tr>
            {{-- <tr class="proposal-details-row" data-index="{{ $key }}" style="display: none;">
                <td colspan="6">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <p class="mb-1 text-dark"><strong>Description:</strong></p>
                            <p class="mb-1 fs-0-9">{{ $editorRequest->proposal->description }}</p>
                        </div>
                    </div>
                </td>
            </tr> --}}
        @empty
            <tr>
                <td colspan="5">No requests found...</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3 d-flex justify-content-end">
    {{ $requests->links() }}
</div>
