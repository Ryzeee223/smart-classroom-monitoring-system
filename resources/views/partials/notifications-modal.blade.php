<div class="modal fade " id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true" style="">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationsModalLabel">Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
          

                <div class="d-flex align-items-center justify-content-between mb-2">
    <h6 class="mb-0 fw-bold">Requests</h6>
    <span class="badge bg-primary">{{ ($req ?? collect())->count() }}</span>
</div>

{{-- Request inbox --}}
@if(in_array((int)(session('user_role') ?? 0), [2, 3], true))
    <div id="leave-requests-modal-content">
        <p class="text-muted small mb-3">Click a name to view request details.</p>
        <div class="list-group">
            @forelse(($req ?? collect()) as $requesterUserId => $requests)
                @php
                    $first = $requests->first();
                    $userObj = $first->user ?? null;
                    $requesterName = trim(($userObj->first_name ?? $first->first_name ?? '').' '.($userObj->last_name ?? $first->last_name ?? ''));
                    $roleMap = [2 => 'Dean', 3 => 'Assistant Dean', 4 => 'Faculty', 5 => 'Program Head'];
                    $requesterRoleCode = $userObj->role ?? $first->role ?? 0;
                    $requesterRole = $roleMap[(int)$requesterRoleCode] ?? 'Unknown';
                    $collapseId = 'modal-faculty-requests-' . $requesterUserId;
                @endphp
                <button
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-3 py-2"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                    aria-expanded="false"
                    aria-controls="{{ $collapseId }}"
                >
                    <span class="fw-bold small">{{ !empty($requesterName) ? $requesterName : 'Unknown Faculty' }}</span>
                    <span class="small text-muted">{{ $requesterRole }}</span>
                    <span class="badge bg-primary rounded-pill">{{ $requests->count() }}</span>
                </button>

                <div id="{{ $collapseId }}" class="collapse border border-dark-1">
                    <div class="p-3" style="border-top:1px solid rgba(0,0,0,.08)">
                        @foreach($requests as $r)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold small">{{ $r->reason }}</div>
                                    <div class="text-muted" style="font-size:12px;">
                                        {{ !empty($r->created_at) ? \Carbon\Carbon::parse($r->created_at)->format('Y-m-d') : '-' }}
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    Reason: {{ $r->letter }}
                                    <br>
                                    <div class="d-flex gap-2 mt-2">
                                        @if (($r->reason ?? '') === 'official business leave')
                                            <form action="{{ route('showReason', $r->id ?? 0) }}" method="GET">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm">Accept</button>
                                            </form>
                                            <form action="{{ route('requests.decline', $r->id ?? 0) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Decline</button>
                                            </form>
                                        @elseif (($r->reason ?? '') === 'Sick leave')
                                            <form action="{{ route('requests.approve', $r->id ?? 0) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm">Accept</button>
                                            </form>
                                            <form action="{{ route('requests.decline', $r->id ?? 0) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Decline</button>
                                            </form>
                                        @elseif (($r->reason ?? '') === 'Summer class')
                                            <a href="{{ route('schedules') }}?user_id={{ $r->user_id ?? '' }}" class="btn btn-outline-success btn-sm">Set Schedule</a>
                                            <form action="{{ route('requests.decline', $r->id ?? 0) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Decline</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">No pending requests for your college.</div>
            @endforelse
        </div>
    </div>
@else
    <div class="text-center text-muted py-4">Your requests will be reviewed by the Dean.</div>
@endif

<div class="mt-4">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="mb-0 fw-bold">Recent Faculty Added</h6>
        <span class="badge bg-info">{{ count($recent_faculty ?? []) }}</span>
    </div>

    <div class="list-group">
        @forelse($recent_faculty ?? [] as $faculty)
            <div class="list-group-item">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-1 small fw-bold">{{ $faculty->first_name }} {{ $faculty->last_name }}</h6>
                        <p class="text-xs mb-0 text-muted">{{ $faculty->email }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="list-group-item text-center text-muted">No recent faculty added in your college.</div>
        @endforelse
    </div>
</div>
            </div>
        </div>
    </div>
</div>

{{-- Set Schedule modal --}}
<div class="modal fade" id="setschedmodal" tabindex="-1" aria-labelledby="setschedmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setschedmodalLabel">Set Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <p>Set schedule for this faculty.</p>
                {{-- The form fields below are intentionally simple -- encode your own inputs here --}}
                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Faculty</label>
                        <input type="text" name="faculty_name" class="form-control" placeholder="Faculty name" value="">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Program</label>
                            <input type="text" name="program_id" class="form-control" placeholder="Program">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course</label>
                            <input type="text" name="Course" class="form-control" placeholder="Course">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room</label>
                            <input type="text" name="Room" class="form-control" placeholder="Room">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Day</label>
                            <input type="text" name="Day" class="form-control" placeholder="e.g. Monday, Tuesday">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="Start_time" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Time</label>
                            <input type="time" name="End_time" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
