<table class="table table-bordered align-middle">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th class="text-center">Budget</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($requests as $editorRequest)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $editorRequest->editor->full_name ?? '' }}</td>
                <td>{{ $editorRequest->editor->email ?? '' }}</td>
                <td class="text-center">{{ $editorRequest->proposal->bid_price ?? 0 }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No requests found...</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3 d-flex justify-content-end">
    {{ $requests->links() }}
</div>
