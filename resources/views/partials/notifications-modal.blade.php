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
                        <h6 class="mb-0 fw-bold">Leave Requests</h6>
                        <span class="badge bg-primary">{{ ($req ?? collect())->count() }}</span>
                    </div>
@if(in_array((int)(session('user_role') ?? 0), [2,3], true))
                        <div id="leave-requests-modal-content">
                            <p class="text-muted small mb-3">Click a faculty name to view leave request details.</p>
                            <div class="list-group">
                                @forelse(($req ?? collect()) as $facultyUserId => $req)
                                    @php
                                        $first = $req->first();
                                        $facultyName = trim(($first->first_name ?? '').' '.($first->last_name ?? ''));
                                        $collapseId = 'modal-faculty-requests-' . $facultyUserId;
                                    @endphp
                                    <button
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-3 py-2 "
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#{{ $collapseId }}"
                                        aria-expanded="false"
                                        aria-controls="{{ $collapseId }}"
                                    >
                                        <span class="fw-bold small">{{ $facultyName ?: 'Unknown Faculty' }}</span>
                                        <span class="badge bg-primary rounded-pill">{{ $req->count() }}</span>
                                    </button>
                                    <div id="{{ $collapseId }}" class="collapse border border-dark-1">
                                        <div class="p-3" style="border-top:1px solid rgba(0,0,0,.08)">
                                            @foreach($req as $r)
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1 ">
                                                        <div class="fw-bold small">{{ $r->reason }}</div>
                                                        <div class="text-muted" style="font-size:12px;">
                                                            {{ !empty($r->created_at) ? \Carbon\Carbon::parse($r->created_at)->format('Y-m-d') : '-' }}
                                                        </div>
                                                    </div>
                                                        <div class="text-muted small">Reason: {{ $r->letter }}

                                                        <br>
                                                        <div class="d-flex gap-2 mt-2">
                                                            <form action="{{ route('requests.approve', $r->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-outline-success btn-sm">Accept</button>
                                                            </form>
                                                            <form action="{{ route('requests.decline', $r->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-outline-danger btn-sm">Decline</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">No leave requests yet.</div>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">Your requests will be reviewed by the Dean.</div>
                    @endif
                </div>

                
                <div>
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
                            <div class="list-group-item text-center text-muted">No recent faculty added.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>