<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Approval-emonitor</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>

    @include('sidebar')

    <!-- 1. Request Approval View -->
    <div class="container-fluid py-4" style="margin-left: 260px;">
        <div class="card bg-light mb-4">
            <div class="card-body text-center">
                <h1>Summer Class Approval</h1>
                <p>Please review the academic schedule details below.</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Academic Schedule Details</h5>
                <p class="card-text"><strong>Reason:</strong> {{ $RequestRecord->reason ?? 'N/A' }}</p>
                <p class="card-text"><strong>Details:</strong> {{ $RequestRecord->letter ?? 'N/A' }}</p>
                <p class="card-text"><strong>Status:</strong> {{ $RequestRecord->status ?? 'N/A' }}</p>
                <p class="card-text"><strong>Date Submitted:</strong> {{ !empty($RequestRecord->created_at) ? \Carbon\Carbon::parse($RequestRecord->created_at)->format('Y-m-d') : 'N/A' }}</p>
            </div>
        </div>

        @if (!empty($RequestRecord))
            <div class="text-center mb-4">
                <form action="{{ route('requests.approve', $RequestRecord->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
                <form action="{{ route('requests.decline', $RequestRecord->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Decline</button>
                </form>
                <a href="{{ route('schedules', ['user_id' => $RequestRecord->user_id ?? '']) }}" class="btn btn-primary" style="display: inline-block;">
                    Set Schedule for this Faculty
                </a>
            </div>
        @endif

        <!-- 2. Pending Requests -->
        <div class="mb-4">
            <h5>Pending Requests</h5>
            @if (count($pending_requests ?? []) > 0)
                <div class="accordion" id="pendingRequestsAccordion">
                    @forelse($pending_requests as $r)
                        <div class="accordion-item">
                            <div class="accordion-header" id="heading{{ $r->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $r->id }}" aria-expanded="false" aria-controls="collapse{{ $r->id }}">
                                    Request ID: {{ $r->id }} - Reason: {{ $r->reason }}
                                </button>
                            </div>
                            <div id="collapse{{ $r->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $r->id }}" data-bs-parent="#pendingRequestsAccordion">
                                <div class="accordion-body">
                                    <p><strong>Details:</strong> {{ $r->letter }}</p>
                                    <p><strong>Status:</strong> {{ $r->status }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center">No pending requests.</div>
                    @endforelse
                </div>
            @else
                <div class="alert alert-info text-center">No pending requests.</div>
            @endif
        </div>
    </div>

</body>
</html>

