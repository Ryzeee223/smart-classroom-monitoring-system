<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - eMonitor</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>

</head>
<body>
<style>
body {
    overflow-x: auto;
    overflow-y:auto;
}
</style>

        <!-- nav bar -->
    @include('sidebar')

<main role="main" class="page-content">

            <div class="container p-4" style="padding-top:16px;">
                
                <div class="mb-2 d-flex justify-content-end " style="radius:10px;">
            <button type="button"
                    class="btn btn-sm btn-light shadow-sm rounded-circle d-flex align-items-center justify-content-center "
                    style="margin-right:50px; z-index: 10;"
                    aria-label="Open notifications"
                    data-bs-toggle="modal"
                    data-bs-target="#notificationsModal">
                <img src="{{ asset('storage/icons/bell.svg') }}" alt="Notifications" style="width:18px; height:18px; display:block;"/>
             </button>
                </div>
                <br>

              <!-- Stats Cards -->
        <div id="stats-cards" class="mt-2 position-relative">
      
        </div>
    
            <div class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">

            <div class="col">
                <div class="card h-100 shadow border-1">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Rooms</h6>
                        <div class="display-4 fw-bold text-primary mb-0">{{ $showrm ?? $roomnm ?? 0 }}</div>

                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow border-1">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Occupied</h6>
                        <div class="display-4 fw-bold text-success mb-0">0</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow border-1">

                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Faculty</h6>
                        {{-- Count accounts for: Faculty (4), Program Head (5), Dean (2), Assistant Dean (3) --}}
                        <div class="display-4 fw-bold text-info mb-0">{{ $faculty_count ?? 0 }}</div>
                    </div>
                </div> 
            </div>

            <div class="col">
                <div class="card h-100 shadow border-1">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Pending RFID</h6>
                        <div class="display-4 fw-bold text-warning mb-0">{{ $pending_count ?? 0 }}</div>
                    </div>
                </div>
            </div>

            </div>

            </div>
        </div>

        <!-- Main Content Row -->
        <div class="row g-3 mb-5 " style="overflow-y:auto; max-height:calc(100vh - 300px);">


            <!-- Live Classroom Status -->
            <div class="col-lg-12 ">
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
                                            class="nav-link building-tab {{ $bIndex === 0 ? 'active' : '' }}"
                                            type="button"
                                            data-building="{{ $abbr }}"
                                            data-building-name="{{ $name }}"
                                        >
                                            {{ $abbr }}
                                        </button>
                                    @endforeach
                                @else
                                    <button class="nav-link active building-tab" type="button" data-building="A">N/A</button>
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
                            // We expect controller to have $rooms (with building relationship loaded).
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
                                                <span class="badge bg-success rounded-pill px-2 py-1 fs-6">Vacant</span>
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

            <!-- Right Column -->
            <div class="col-lg-4">

                <!-- Leave Requests (Dean / Assistant Dean only) -->
                @php
                    $viewerRole = (int) (session('user_role') ?? 0);
                @endphp


@if(in_array($viewerRole, [2, 3], true))
                  
                @endif

                    @if (in_array($viewerRole, [1, 2, 3], true))
            </div>
</div>
@endif
                </div>

                <div>
                    @include('partials.notifications-modal')
                </div>

                {{-- Building tab + filter script (basic for current static cards) --}}


            <!-- Recent Logs (inside Right Column) -->
                    <div class="col-lg-12 mt-3 width-100">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="mb-0 fw-bold">Recent Logs</h5>
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
                                                <th>Course</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="table-active">
                                                <td colspan="6" class="text-center py-4 text-muted">No recent activity. Check back later</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
</div>
</body>
</html>





