<div class="dropdown">
    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
        data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></button>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('admin:customers.view', ['uuid' => $row->uuid]) }}"><i
                class="mdi mdi-eye me-1"></i> View</a>
    </div>
</div>
