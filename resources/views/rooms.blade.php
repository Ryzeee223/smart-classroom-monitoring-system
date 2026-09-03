<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - eMonitor</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<style>
    body {
        font-family: system-ui , "Segoe UI", Roboto, sans-serif;
        background-color: #f8f9fa;
        overflow-x: auto;
        overflow-y: auto;
    }
</style>

@include('sidebar')

@php
    
@endphp

<div class="page-content">
    <main class="container py-2">
        <h1 class="mb-4">Rooms and Buildings</h1>

        <div class="row g-4 mb-4">
            {{-- Left column --}}
            <div class="col-lg-8">

                {{-- Create building --}}
                <div class="card p-3 shadow-sm bg-white border-1 mb-4 ">
                    <h2 class="h5 mb-3">Create building</h2>
                    <form method="POST" action="{{ route('storeBldg.store') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="d-grid gap-1 align-items-center">
                                <label class="form-label form-label-sm">Select College</label>
                                <select name="college_id" class="form-select form-select-sm" id="college_id">
                                    <option value="">Select College</option>
                                    @forelse($colleges ?? [] as $c)
                                        <option value="{{ $c->id }}">{{ $c->college_name ?? $c->abbreviation }}</option>
                                    @empty
                                        <option value="" disabled>No colleges available</option>
                                    @endforelse
                                </select>

                                <label class="form-label form-label-sm" name="bldg_name">Building name</label>
                                <input type="text" placeholder="Building name" class="form-control form-control-sm" name="bldg_name" id="bldg_name" required>

                                <label class="form-label form-label-sm" name="bldg_abbr">Building abbreviation</label>
                                <input type="text" placeholder="Building abbreviation" class="form-control form-control-sm" name="bldg_abbr" id="bldg_abbr" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            <button class="btn btn-primary btn-sm w-100" style="height:50px;">Create Building</button>
                        </div>
                    </form>
                </div>

                {{-- Create Rooms --}}
                <div class="card p-3 shadow-sm bg-white border-1 mb-4">
                    <h2 class="h5 mb-3">Create Rooms</h2>

                    <form method="POST" action="{{ route('storeRoom.store') }}">
                        <div class="d-grid gap-2 align-items-center">
                            @csrf
                            <select name="bldg_id" class="form-select form-select-sm" id="bldg_id">
                                <option value="">Select Building</option>
                                @forelse($bldgModel ?? [] as $bldg)
                                    <option value="{{ $bldg->id }}">{{ $bldg->bldg_name }} ({{ $bldg->bldg_abbr }})</option>
                                @empty
                                    <option value="" disabled>No buildings available</option>
                                @endforelse
                            </select>


                            <select class="form-select form-select-sm" name="room_type" required>
                                <option value="">Select Type</option>
                                <option value="Lec">Lecture</option>
                                <option value="Lab">Laboratory</option>
                            </select>

                            <input type="text" placeholder="Enter Classroom code (eg. cc101)" class="form-control form-control-sm" name="room_name" required>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            <button class="btn btn-primary btn-sm w-100" style="height:50px;">Create Room</button>
                        </div>
                    </form>
                </div>

               
            </div>

            {{-- Right column --}}
            <div class="col-lg-4">
                <div class="card p-3 shadow-sm bg-white border-1 h-100">
                    <h5 class="mb-3 d-flex justify-content-between align-items-center"> 
                        <span>Existing buildings and rooms  </span>
                    </h5>

                        @if(session('error'))
                            <div class="alert alert-danger alert-sm mb-3">{{ session('error') }}</div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-sm mb-3">{{ session('success') }}</div>
                        @endif

                        {{-- College filter --}}
                            <div class="mb-3">
                            <label class="form-label form-label-sm mb-1">Filter by present College ID</label>
                            <select class="form-select form-select-sm" id="college-filter" onchange="filterBuildingsByCollege()">
                            <option value="">All colleges</option>
                            @forelse($colleges ?? [] as $c)
                                <option value="{{ $c->id }}">{{ $c->college_name ?? $c->abbreviation }}</option>
                            @empty
                                <option value="" disabled>No colleges available</option>
                            @endforelse
                             </select>
                            </div>

                    <div class="table-responsive mb-0">
                        {{-- slot container --}}
                        <div class="p-2" style="background:#f8f9fa; border-radius:6px;">
                            <div class="card-body p-0">
                                <p class="mb-2 text-muted" style="font-size: 0.9rem;">Existing buildings</p>
                            </div>

                            <div id="existing-buildings-container">
                                @forelse($bldgModel ?? [] as $b)
                                    <div class="d-flex justify-content-between align-items-center border rounded-2 px-2 py-2 mb-2 bg-white"
                                         data-college-id="{{ $b->college_id ?? '' }}">
                                        <div>
                                            <div class="fw-semibold">{{ $b->bldg_name }}</div>
                                            <div class="text-muted">({{ $b->bldg_abbr }})</div>
                                        </div>

                                        <form action="{{ route('building.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Delete this building?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="mb-0 text-muted" style="font-size: 0.9rem;">No buildings available.</p>
                                @endforelse
                            </div>


                            <div class="mt-4">
                                <div class="card-body p-0 mb-2">
                                    <p class="mb-2 text-muted" style="font-size: 0.9rem;">Existing rooms</p>
                                </div>

                                <div id="existing-rooms-container">
                                    @forelse($rooms ?? [] as $r)
                                        @php
                                            // Filtering should use the PRESENT college_id from related/created records
                                            $roomCollegeId = $r->college_id ?? (optional($r->building)->college_id ?? '');
                                            $roomBuildingAbbr = optional($r->building)->bldg_abbr;

                                        @endphp

                                        <div class="d-flex justify-content-between align-items-center border rounded-2 px-2 py-2 mb-2 bg-white"
                                             data-college-id="{{ $roomCollegeId }}">
                                            <div>
                                                <div class="fw-semibold">{{ $r->room_name }}</div>
                                                <div class="text-muted" style="font-size:0.85rem;">
                                                    {{ $r->room_type ?? '' }}
                                                    {{ $roomBuildingAbbr ? '• ' . $roomBuildingAbbr : '' }}
                                                </div>
                                                
                                            </div>
                                            <div class="text-muted" style="font-size:0.85rem;">
                                                {{ optional($r->building)->bldg_name ? optional($r->building)->bldg_name : ($roomBuildingAbbr ? '(' . $roomBuildingAbbr . ')' : '') }}
                                            </div>


                                            <form action="{{ route('building.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Delete this room?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                            </form>



                                     </div>
                                    @empty
                                        <p class="mb-0 text-muted" style="font-size: 0.9rem;">No rooms available.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    function filterBuildingsByCollege() {
        const select = document.getElementById('college-filter');
        const collegeId = select ? (select.value ?? '') : '';
        // collegeId is compared directly against each item's data-college-id


        // Filter both buildings and rooms using the same filter
        const buildingContainer = document.getElementById('existing-buildings-container');
        const roomsContainer = document.getElementById('existing-rooms-container');

        const filterContainer = (container) => {
            if (!container) return;
            const items = container.querySelectorAll('[data-college-id]');
            items.forEach(el => {
                const id = el.getAttribute('data-college-id') || '';
                // Only show items whose data-college-id matches the selected present college id
                el.style.display = (!collegeId || collegeId === id) ? '' : 'none';
            });
        };

        filterContainer(buildingContainer);
        filterContainer(roomsContainer);
    }

    // initial call (show all)
    document.addEventListener('DOMContentLoaded', () => {
        filterBuildingsByCollege();
    });
</script>
@include('partials.notifications-modal')
</body>
</html>

