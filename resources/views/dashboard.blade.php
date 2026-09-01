<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RFInsiDe</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fontisto@v3.0.4/css/fontisto/fontisto.min.css">
</head>
<body>
<style>
body {
    background:#f5f7fb;
    overflow-x:hidden;
    overflow-y:auto;
}
.dashboard-container {
    padding-top:16px;
    padding-bottom:32px;
}
.dashboard-container .card {
    border:0;
    border-radius:10px;
}
.dashboard-container .card-header {
    border-bottom:1px solid rgba(0,0,0,.06);
}
.dashboard-container .stats-card {
    min-height:132px;
}
</style>

        <!-- nav bar -->
    @include('sidebar')
    

<main role="main" class="page-content">
            @php
                $role = (int) (session('user_role') ?? 0);
            @endphp
            <div class="container-fluid dashboard-container px-4">
                
                <div class="mb-2 d-flex justify-content-end " style="radius:10px;">
                    </div>
            <div class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">

            <div class="col">
                <div class="card stats-card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Rooms</h6>
                        <div class="display-4 fw-bold text-primary mb-0">{{ $countRoom ?? 0}}</div>

                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card stats-card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Occupied</h6>
                        <div class="display-4 fw-bold text-success mb-0">{{$occupiedRooms ?? 0}}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card stats-card h-100 shadow-sm">

                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Faculty</h6>
                        {{-- Count accounts for: Faculty (4), Program Head (5), Dean (2), Assistant Dean (3) --}}
                        <div class="display-4 fw-bold text-info mb-0">{{ $facultyCount ?? 0 }}</div>
                    </div>
                </div> 
            </div>

            
                <div class="col">
                <div class="card stats-card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Pending RFID</h6>
                        <div class="display-4 fw-bold text-warning mb-0">{{ $pending_count ?? 0 }}</div>
                    </div>
                </div>
            </div>
            
           

            </div>

        <!-- Main Content Row -->
        <div class="row g-4 mb-4">


            <!-- Live Classroom Status -->
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary bg-opacity-10">
                        <h5 class="mb-0 fw-bold text-dark">Live Classroom Status</h5>
                    </div>

                    {{-- add a navbar of buildings (click to filter classrooms) --}}
                    <div class="card-body p-0">
                        <p class="p-4 pb-3 text-muted small">Rooms update automatically as Faculty tap registered RFID Cards.</p>

                        {{-- Building selector --}}
                        <div class="px-3 pb-2">
                            <div class="nav nav-pills flex-column flex-sm-row gap-2" role="tablist" aria-label="Buildings">
                                @php
                                    $bldng = $buildings ?? null;
                                @endphp

                                @if($bldng && $buildings->count())
                                    @foreach($buildings as $bIndex => $b)
                                        @php
                                            $abbr = $b->bldg_abbr ?? $b->abbr ?? $b->name ?? ('B' . ($bIndex + 1));
                                            $name = $b->bldg_name ?? $abbr;
                                        @endphp

                                        <button
                                            class="nav-link building-tab {{ $bIndex === 0 ?  : '' }}"
                                            type="button"
                                            data-building="{{ $abbr }}"
                                            data-building-name="{{ $name }}"
                                        >
                                            {{ $abbr }}
                                        </button>
                                    @endforeach
                                @else
                                    <button class="nav-link building-tab" type="button" data-building="None">N/A</button>
                                @endif

                            </div>
                        </div>

                        {{-- Building header --}}
                        <div class="card-body" style="">
                            <h3 class="building-title"></h3>
                        </div>

                        {{-- Classroom grid (populated by JS when clicking a building) --}}
                        <div class="row row-cols-1 row-cols-md-2 g-3 px-4 pb-4" id="classroomGrid"></div>

                        {{-- Room data for the selected building tab --}}
                        @php
                        
                            // If not present, fall back to empty.
                            $roomsForGrid = $rooms ?? collect();

                            // Build the grid data in PHP so the @json directive only receives a simple variable.
                            $roomsGridData = $roomsForGrid->map(function ($r) {
                                return [
                                    'id' => $r->id ?? null,
                                    'name' => $r->room_name ?? '',
                                    'type' => $r->room_type ?? '',
                                    'bldg_abbr' => optional($r->building)->bldg_abbr ?? ($r->building_abbr ?? ''),
                                    'bldg_name' => optional($r->building)->bldg_name ?? ($r->building_name ?? ''),
                                    'status' => $r->status ?? 'unknown',
                                ];
                            })->values();
                        @endphp

                        <script>
                            window.__roomsGrid = @json($roomsGridData);
                        </script>


                    </div>
                </div>
            </div>

            <script>
                (function () {
                    const grid = document.getElementById('classroomGrid');
                    const buildingTitle = document.querySelector('.building-title');
                    const rooms = window.__roomsGrid || [];

                    function renderForBuilding(abbr, name) {
                        if (!grid) return;

                        const filtered = rooms.filter(r => (r.bldg_abbr || '') === (abbr || ''));

                        const title = name || (abbr ? `Building ${abbr}` : '');

                        if (!filtered.length) {
                            grid.innerHTML = `
                                <div class="col-12">
                                    <div class="alert alert-light border" role="alert">
                                        No rooms found for building <strong>${title}</strong>.
                                    </div>
                                </div>
                            `;
                            if (buildingTitle) buildingTitle.textContent = title;
                            return;
                        }

                        grid.innerHTML = filtered.map(r => {
                            const subtitle = [r.type].filter(Boolean).join(' ');
                            return `
                                <div class="col">
                                    <div class="card shadow-sm h-100 border-1">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <div>
                                                    <h6 class="mb-1 fw-bold small">${r.name || ''}</h6>
                                                    <p class="mb-0 text-muted fs-6">${r.bldg_abbr ? r.bldg_abbr : ''}</p>
                                                </div>
                                                <span class="badge ${r.status === 'vacant' ? 'bg-success' : r.status === 'occupied' ? 'bg-danger' : 'bg-secondary'} rounded-pill px-2 py-1 fs-6">${r.status || 'unknown'}</span>
                                                
                                            </div>
                                        </div>
                                        <div class="card-footer p-2 pt-1 bg-transparent border-0">
                                            <p class="mb-0 text-muted fs-6"><small class="fw-bold text-uppercase">Faculty:</small> None</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');

                        if (buildingTitle) buildingTitle.textContent = title;
                    }

                    document.addEventListener('DOMContentLoaded', function () {
                        const activeBtn = document.querySelector('.building-tab.active');
                        const initialAbbr = activeBtn ? activeBtn.getAttribute('data-building') : '';
                        const initialName = activeBtn ? activeBtn.getAttribute('data-building-name') : '';

                        if (initialAbbr !== undefined) renderForBuilding(initialAbbr, initialName);

                        document.querySelectorAll('.building-tab').forEach(btn => {
                            btn.addEventListener('click', function () {
                                const abbr = this.getAttribute('data-building') || '';
                                const name = this.getAttribute('data-building-name') || '';
                                renderForBuilding(abbr, name);
                            });
                        });
                    });
                })();
            </script>


                </div>
            </div>

            @php
                $role = (int) (session('user_role') ?? 0);
            @endphp



            <div class="row g-3 mb-5">

            <!-- Attendance -->
            @if (in_array(session('user_role'), [2,3,4,5]))
<div class="col-lg-12 mt-3 width-100">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="mb-0 fw-bold">My Attendance</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Class</th>
                                                <th>Faculty Name</th>
                                                <th>Room</th>
                                                <th>Time In</th>
                                                <th>Time Out</th>
                                                <th>Course</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(($myAttendance ?? collect()) as $attendance)
                                                @php $schedule = $attendance->schedule; @endphp
                                                <tr>
                                                    <td>{{ $schedule?->course?->course_code ?? 'N/A' }}</td>
                                                    <td>{{ trim(($schedule?->user?->first_name ?? '') . ' ' . ($schedule?->user?->last_name ?? '')) ?: 'N/A' }}</td>
                                                    <td>{{ $schedule?->room?->room_name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if ($attendance->time_in)
                                                            {{ \Illuminate\Support\Carbon::parse($attendance->time_in)->format('g:i A') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td></td>
                                                    <td>{{ $schedule?->course?->course_name ?? 'N/A' }}</td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $attendance->status_in ?? 'N/A')) }}</td>
                                                </tr>
                                            @empty
                                                <tr class="table-active">
                                                    <td colspan="6" class="text-center py-4 text-muted">No attendance records found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
            </div>                
            @endif

               

                    <script>
    let lastHandledUid = null;

    setInterval(async () => {
        try {
            const response = await fetch('/api/check-latest-attendance');
            const data = await response.json();

            if (data.scan_data && data.uid !== lastHandledUid) {
                const scan = data.scan_data;

                if (scan.status === 'accepted') {
                    lastHandledUid = data.uid;

                    // Locate table row by attendance ID or User ID
                    const statusBadge = document.querySelector(`#status-badge-${scan.attendance_id}`) 
                                     || document.querySelector(`#status-user-${scan.user.id}`);
                    const timeInCell = document.querySelector(`#time-in-${scan.attendance_id}`);
                    const timeOutCell = document.querySelector(`#time-out-${scan.attendance_id}`);

                    if (statusBadge) {
                        statusBadge.textContent = scan.status_in.toUpperCase();

                        // Set badge colors according to enum value
                        let badgeClass = 'badge ';
                        switch(scan.status_in) {
                            case 'attended': badgeClass += 'bg-success'; break;
                            case 'late': badgeClass += 'bg-warning text-dark'; break;
                            case 'on_leave': badgeClass += 'bg-info text-dark'; break;
                            case 'absent': badgeClass += 'bg-danger'; break;
                            default: badgeClass += 'bg-secondary';
                        }
                        statusBadge.className = badgeClass;
                    }

                    if (timeInCell && scan.time_in) {
                        timeInCell.textContent = scan.time_in;
                    }
                    if (timeOutCell && scan.time_out) {
                        timeOutCell.textContent = scan.time_out;
                    }
                }
            }
        } catch (err) {
            console.error('Scan polling error:', err);
        }
    }, 2000);
</script>
            </div>
        </div>
    </main>
    @include('partials.notifications-modal')
</body>
</html>





