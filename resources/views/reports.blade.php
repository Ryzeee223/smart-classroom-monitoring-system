<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.favicon')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/sass/app.scss', 'resources/js/bootstrap-vendor.js'])
<title>Faculty Schedule Reports</title>
    <style>
        body {
            background: #f5f7fb;
            overflow-x: auto;
            overflow-y: auto;
        }

        .schedule-card {
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.75rem rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .schedule-header {
            background: rgba(13, 110, 253, 0.08);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            padding: 1rem 1.25rem;
        }

        .schedule-item {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            background: #fff;
            padding: 1rem;
            margin-bottom: 0.85rem;
        }

        .schedule-item.live {
            border-color: #86b7fe;
            background: rgba(13, 110, 253, 0.04);
        }

        .schedule-time {
            font-weight: 700;
            color: #0d6efd;
        }

        .subject-code {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #495057;
            text-transform: uppercase;
        }

        .status-badge {
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.35rem 0.6rem;
        }

        .upcoming-box {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            padding: 1rem;
        }

        .room-utilization-progress {
            height: 0.75rem;
        }

        .room-utilization-label {
            font-size: 0.875rem;
        }

        @media (max-width: 767.98px) {
            .schedule-item {
                padding: 0.85rem;
            }
        }
    </style>
</head>
<body>
    @include('sidebar')

    <div class="container-fluid p-5 mt-3">
        <div class="row justify-content-end">
            <div class="col-md-9  shadow">

                <div class="card schedule-card shadow-sm border-1">
                    <div class="schedule-header d-flex justify-content-between align-items-center flex-wrap gap-3 ">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">Faculty Schedule Overview</h5>
                            <small class="text-muted d-block">{{ $todayLabel ?? now()->translatedFormat('l, F d, Y') }}</small>
                            <small class="text-muted d-block">
                                {{ $currentSemester ?? 'Current Semester' }} • {{ $currentSchoolYear ?? 'Current School Year' }}
                            </small>

                        </div>
                            <div class="d-flex align-items-end gap-2 flex-wrap">
                                <form method="GET" action="{{ route('reports') }}" class="d-flex align-items-end gap-2">
                                    <div>
                                        <label for="date" class="form-label">Attendance Date</label>
                                        <select class="form-select" id="date" name="date" required>
                                            <option value="">Select attendance date</option>
                                            @foreach ($displayrep as $date)
                                                <option value="{{ $date }}" @selected(request('date') == $date)>
                                                    {{ \Illuminate\Support\Carbon::parse($date)->format('F d, Y') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">View Attendance</button>
                                </form>

                                <form method="POST" action="{{ route('reports.generate') }}" class="d-flex align-items-end">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ request('date') }}">
                                    <button type="submit" class="btn btn-success" {{ !request('date') ? 'disabled' : '' }}>Generate Excel</button>
                                </form>
                            </div>
                        </div>
                    </div>


                 <div class="card-body p-3 p-md-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Schedule</th>
                                    <th scope="col">Time In</th>
                                    <th scope="col">Time Out</th>
                                    <th scope="col">Faculty Name</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($facultySchedules as $schedule)
                                    <tr>
                                        <td >{{ $schedule['start_display'] }} - {{ $schedule['end_display'] }}</td>
                                        <td>{{ $schedule['time_in'] ?? 'N/A' }}</td>
                                        <td>{{ $schedule['time_out'] ?? 'N/A' }}</td>
                                        <td>{{ $schedule['faculty'] }}</td>
                                        <td>{{ $schedule['course_code'] }}</td>
                                        <td>
                                            <span class="d-flex align-items-center justify-content-center">
                                                {{ ucwords(str_replace('_', ' ', $schedule['attendance_status'])) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No attendance records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <br>
                    </div>
                  </div>
                </div>
            
                @if($isDean)
                {{-- Room utilization --}}
            @php
                $availableRoomHoursPerWeek = 80.5;
            @endphp
            <div class="col-md-9 d-box justify-content-center mt-5">
                <div class="card rooms-card shadow">
                    <div class="card-header">
                        <h2 class="h5 mb-1">Room Utilization</h2>
                        <p class="text-muted mb-0">Scheduled time from 07:00 to 6:30, across all seven days</p>
                    </div>

                    <div class="card-body">
                        @forelse($rooms as $room)
                            <div class="room-utilization-item mb-3">
                            <div class="room-utilization-label d-flex justify-content-between align-items-baseline gap-2 mb-1">
                                    <span class="fw-semibold">
                                        {{ $room->room_name }}
                                        @if($room->building?->bldg_abbr)
                                            <span class="text-muted fw-normal">({{ $room->building->bldg_abbr }})</span>
                                        @endif
                                    </span>
                                    <span class="text-muted text-end">
                                        {{ number_format($room->utilization_percentage, 1) }}%
                                        ({{ number_format($room->scheduled_hours_per_week, 1) }} of {{ number_format($availableRoomHoursPerWeek, 1) }} hours/week)
                                    </span>
                                </div>
                                <div
                                    class="progress room-utilization-progress"
                                    role="progressbar"
                                    aria-label="Room utilization for {{ $room->room_name }}"
                                    aria-valuenow="{{ $room->utilization_percentage }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                >
                                    <div
                                        class="progress-bar"
                                        style="width: {{ $room->utilization_percentage }}%;"
                                    ></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No rooms available to display.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Instructional Faculty Load --}}

                <div class="col-md-9 mt-4 d-box">
                    <div class="card shadow-lg ">
                        <div class="card-header">
                            <h5>Individual Faculty Load</h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($facultyMembers as $faculty)
                                    @php
                                        $facultyLoadModalId = 'facultyLoadModal' . $faculty->id;
                                        $facultyName = trim($faculty->first_name . ' ' . $faculty->last_name) ?: 'Faculty';
                                    @endphp
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary rounded-pill"
                                        data-bs-toggle="modal"
                                        data-bs-target="#{{ $facultyLoadModalId }}"
                                    >
                                        {{ $facultyName }}
                                    </button>

                                    <div class="modal fade" id="{{ $facultyLoadModalId }}" tabindex="-1" aria-labelledby="{{ $facultyLoadModalId }}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="{{ $facultyLoadModalId }}Label">{{ $facultyName }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body"></div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">No faculty available.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                @endif

            </div>
        </div>  
    </div>
 </div>
    @include('partials.notifications-modal')
</body>

</html>