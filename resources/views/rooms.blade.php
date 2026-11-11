<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.favicon')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - RFINSIDE</title>

    @vite(['resources/sass/app.scss', 'resources/js/bootstrap-vendor.js'])
</head>
<body>

<style>
    body {
        font-family: system-ui, "Segoe UI", Roboto, sans-serif;
        background-color: #f8f9fa;
        overflow-x: hidden;
    }

    .rooms-page {
        max-width: 1600px;
    }

    .rooms-card {
        border: 0;
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .rooms-card > .card-header {
        padding: 1rem 1.25rem;
        background: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .rooms-card > .card-body {
        padding: 1.25rem;
    }

    .room-list-section {
        padding: 1rem;
        background: #f8f9fa;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 0.625rem;
    }

    .room-list-item {
        min-width: 0;
        gap: 1rem;
        padding: 0.75rem;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
    }

    .room-list-item__details {
        min-width: 0;
    }

    .room-list-item__meta {
        color: #6c757d;
        font-size: 0.875rem;
    }

    @media (max-width: 575.98px) {
        .rooms-card > .card-body {
            padding: 1rem;
        }

        .room-list-item {
            align-items: flex-start !important;
            flex-wrap: wrap;
        }

        .room-list-item form {
            margin-left: auto;
        }

    }
</style>

@include('sidebar')

<div class="page-content">
    <main class="container-fluid rooms-page px-3 px-lg-4 py-4">
        <div class="mb-4">
            <h1 class="h2 mb-1">Rooms and Buildings</h1>
            <p class="text-muted mb-0">Manage campus buildings and rooms.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                {{-- Create building --}}
                <div class="card rooms-card shadow-sm mb-4">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Create Building</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('storeBldg.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="college_id" class="form-label">College</label>
                                <select name="college_id" class="form-select" id="college_id">
                                    <option value="">Select College</option>
                                    @forelse($colleges ?? [] as $c)
                                        <option value="{{ $c->id }}">{{ $c->college_name ?? $c->abbreviation }}</option>
                                    @empty
                                        <option value="" disabled>No colleges available</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="bldg_name" class="form-label">Building name</label>
                                <input type="text" placeholder="Building name" class="form-control" name="bldg_name" id="bldg_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="bldg_abbr" class="form-label">Building abbreviation</label>
                                <input type="text" placeholder="Building abbreviation" class="form-control" name="bldg_abbr" id="bldg_abbr" required>
                            </div>

                            <button class="btn btn-primary w-100" type="submit">Create Building</button>
                        </form>
                    </div>
                </div>

                {{-- Create room --}}
                <div class="card rooms-card shadow-sm">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Create Room</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('storeRoom.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="bldg_id" class="form-label">Building</label>
                                <select name="bldg_id" class="form-select" id="bldg_id">
                                    <option value="">Select Building</option>
                                    @forelse($bldgModel ?? [] as $bldg)
                                        <option value="{{ $bldg->id }}">{{ $bldg->bldg_name }} ({{ $bldg->bldg_abbr }})</option>
                                    @empty
                                        <option value="" disabled>No buildings available</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="room_type" class="form-label">Room type</label>
                                <select class="form-select" name="room_type" id="room_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Lec">Lecture</option>
                                    <option value="Lab">Laboratory</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="room_name" class="form-label">Room code</label>
                                <input type="text" placeholder="e.g. CC101" class="form-control" name="room_name" id="room_name" required>
                            </div>

                            <button class="btn btn-primary w-100" type="submit">Create Room</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Existing buildings and rooms --}}
            <div class="col-lg-8">
                <div class="card rooms-card shadow-sm h-100">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Existing Buildings and Rooms</h2>
                    </div>
                    <div class="card-body">
                        <section class="room-list-section mb-4" aria-labelledby="existingBuildingsHeading">
                            <h3 class="h6 text-muted mb-3" id="existingBuildingsHeading">Buildings</h3>
                            <div id="existing-buildings-container">
                                @forelse($bldgModel ?? [] as $b)
                                    <div class="room-list-item d-flex justify-content-between align-items-center mb-2"
                                         data-college-id="{{ $b->college_id ?? '' }}">
                                        <div class="room-list-item__details">
                                            <div class="fw-semibold">{{ $b->bldg_name }}</div>
                                            <div class="room-list-item__meta">{{ $b->bldg_abbr }}</div>
                                        </div>

                                        <form action="{{ route('building.archive', $b->id) }}" method="POST" onsubmit="return confirm('Archive this building?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Archive</button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">No buildings available.</p>
                                @endforelse
                            </div>
                        </section>

                        <section class="room-list-section" aria-labelledby="existingRoomsHeading">
                            <h3 class="h6 text-muted mb-3" id="existingRoomsHeading">Rooms</h3>
                            <div id="existing-rooms-container">
                                @forelse($rooms ?? [] as $r)
                                    @php
                                        $roomCollegeId = $r->college_id ?? (optional($r->building)->college_id ?? '');
                                        $roomBuildingAbbr = optional($r->building)->bldg_abbr;
                                    @endphp

                                    <div class="room-list-item d-flex justify-content-between align-items-center mb-2"
                                         data-college-id="{{ $roomCollegeId }}">
                                        <div class="room-list-item__details">
                                            <div class="fw-semibold">{{ $r->room_name }}</div>
                                            <div class="room-list-item__meta">
                                                {{ $r->room_type ?? '' }}
                                                @if($roomBuildingAbbr)
                                                    <span aria-hidden="true">·</span> {{ $roomBuildingAbbr }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="room-list-item__meta text-end">
                                            {{ optional($r->building)->bldg_name ?? ($roomBuildingAbbr ? '(' . $roomBuildingAbbr . ')' : '') }}
                                        </div>

                                        <form action="{{ route('room.archive', $r->id) }}" method="POST" onsubmit="return confirm('Archive this room?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Archive</button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">No rooms available.</p>
                                @endforelse
                            </div>
                        </section>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

@include('partials.feedback-modal')

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
