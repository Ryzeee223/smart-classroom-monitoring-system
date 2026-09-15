@php
    $records = isset($requests)
        ? collect($requests)
        : (isset($RequestRecord) ? collect([$RequestRecord]) : collect());
@endphp

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Request Approval</h4>
        <span class="badge bg-primary">{{ $records->count() }}</span>
    </div>

    @forelse($records as $requestRecord)
        @php
            $requester = $requestRecord->user ?? null;
            $requesterName = trim(($requester->first_name ?? $requestRecord->first_name ?? '') . ' ' . ($requester->last_name ?? $requestRecord->last_name ?? ''));
        @endphp

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="card-title mb-1">{{ $requesterName ?: 'Unknown requester' }}</h5>
                        <div class="text-muted small">{{ $requestRecord->reason }}</div>
                    </div>
                    <span class="badge bg-warning text-dark">{{ ucfirst($requestRecord->status ?? 'pending') }}</span>
                </div>

                <p class="mb-3">{{ $requestRecord->letter }}</p>

                <div class="d-flex gap-2">
                    <form action="{{ route('requests.approve', $requestRecord->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('requests.decline', $requestRecord->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Decline</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-secondary mb-0">No requests found.</div>
    @endforelse
</div>
