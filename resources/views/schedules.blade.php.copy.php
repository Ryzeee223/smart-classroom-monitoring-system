<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule - eMonitor</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</head>
<body>
<style>
    .app-shell { display: flex; min-height: 100vh; }
    .app-shell__content { flex: 1; min-width: 0; margin-left: 260px; }
    @media (max-width: 767.98px) {
        .app-shell__content { margin-left: 0; }
    }
</style>

<div class="app-shell">
    @include('sidebar-admin')

    <main class="app-shell__content">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Add Schedule for Faculty</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('schedules.store') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- Faculty selection --}}
                                        <label class="form-label">Faculty</label>
                                        <select class="form-select" name="user_id" required>
                                            <option value="">Select Faculty</option>
                                            @foreach($faculty_list as $faculty)
                                                <option value="{{ $faculty->id }}">
                                                    {{ $faculty->first_name }} {{ $faculty->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- course selection --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Course</label>
                                        <select class="form-select" name="course" required>
                                            <option value="">Select Course</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->course_name }}">
                                                    {{ $course->course_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- day selection --}}
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Day</label>
                                        <select class="form-select" name="Day" required>
                                            <option value="">Day</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Year Level</label>
                                        <select class="form-select" name="year_level" required>
                                            <option value="">Year</option>
                                            <option value="1">1st</option>
                                            <option value="2">2nd</option>
                                            <option value="3">3rd</option>
                                            <option value="4">4th</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Section</label>
                                        <select class="form-select" name="section" required>
                                            <option value="">Section</option>
                                            @foreach(['A','B','C','D','E','F','G','H','I','J'] as $sec)
                                                <option value="{{ $sec }}">{{ $sec }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Semester</label>
                                        <select class="form-select" name="Semester" required>
                                            <option value="">Sem</option>
                                            <option value="1st Sem">1st Sem</option>
                                            <option value="2nd Sem">2nd Sem</option>
                                            <option value="midyear">mid-year</option>
                                            <option value="summer">summer class</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    {{-- subject --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Subject</label>
                                        <select class="form-select" name="Subject" required>
                                            <option value="">Select Subject</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->subject_name }}">
                                                    {{ $subject->subject_name }} ({{ $subject->subject_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- time --}}
                                    <div class="col-md-3">
                                        <label class="form-label">Time</label>
                                        @php
                                            $timeSlots = [
                                                '7:30-8:30','7:30-9:30','7:30-10-:30','7:30-11:30','7:30-12:30',
                                                '8:00-9:30','8:00-10:30','8:00-11:30','8:00-12:30',
                                                '8:30-9:30','8:30-10:30','8:30-11:30','8:30-12:30',
                                                '9:00-10-:30','9:00-11:30','9:00-12:30',
                                                '9:30-10-:30','9:30-11:30','9:30-12:30',
                                                '10:30-11:30','10:30-12:30','10:30-1:00','10:30-1:30',
                                                '1:00-2:00','1:00-2:30','1:00-3:00','1:00-3:30','1:00-4:00','1:00-4:30','1:00-5:00',
                                                '1:30-2:30','1:30-3:00',
                                                '2:00-3:00','2:00-3:30','2:00-4:00','2:00-4:30','2:00-5:00','2:00-5:30','2:00-6:00',
                                                '3:00-4:00','3:00-4:30','3:00-5:00','3:00-5:30','3:00-6:00',
                                                '3:30-4:30','3:30-5:00','3:30-5:30','3:30-6:00',
                                                '4:30-5:30','4:30-6:00',
                                                '5:00-6:00'
                                            ];
                                        @endphp
                                        <select class="form-select" name="Time" required>
                                            <option value="">Select Time</option>
                                            @foreach($timeSlots as $slot)
                                                <option value="{{ $slot }}">{{ $slot }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- rooms --}}
                                    <div class="col-md-3">
                                        <label class="form-label">Room</label>
                                        <select class="form-select" name="Room" required>
                                            <option value="">Room</option>
                                            <option value="CC101">CC101</option>
                                            <option value="CC104">CC104</option>
                                            <option value="CC205">CC205</option>
                                            <option value="CC206">CC206</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">School Year</label>
                                        <select class="form-select" name="School_year" required>
                                            <option value="2024-2025">2024-2025</option>
                                            <option value="2025-2026">2025-2026</option>
                                            <option value="2026-2027">2026-2027</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Semester</label>
                                        <select class="form-select" name="Semester" required>
                                            <option value="1st Sem">1st Sem</option>
                                            <option value="2nd Sem">2nd Sem</option>
                                            <option value="midyear">mid-year</option>
                                            <option value="summer">summer class</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Save this schedule?')">
                                        Add Schedule
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Schedules</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">

                            @php
                                $groupedSchedules = $schedules->groupBy(function ($s) {
                                    return $s->user_id ?? ($s->user->id ?? 'unknown');
                                });
                            @endphp

                            @foreach($groupedSchedules as $userSchedules)
                                @php
                                    $first = $userSchedules->first();
                                    $userName = trim(($first->user->first_name ?? 'N/A') . ' ' . ($first->user->last_name ?? ''));
                                    $collapseId = 'recentSchedules_' . ($first->id ?? $first->user_id ?? 'x');
                                @endphp

                                <div class="border-bottom pb-3 mb-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <button class="btn btn-link px-0 text-start" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#{{ $collapseId }}"
                                                aria-expanded="false"
                                                aria-controls="{{ $collapseId }}">
                                            <strong>{{ $userName }}</strong>
                                        </button>
                                    </div>

                                    <div class="collapse mt-2" id="{{ $collapseId }}">
                                        <div class="d-flex flex-column gap-2">
                                            @foreach($userSchedules as $schedule)
                                                <div class="p-2 border rounded">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <strong>{{ $schedule->Subject }}</strong><br>
                                                            <small class="text-muted">{{ $schedule->Day }} | {{ $schedule->Time }} | {{ $schedule->Room }}</small><br>
                                                            <small class="text-muted">
                                                                {{ $schedule->course ?? 'N/A' }} {{ $schedule->year_level ?? '' }} {{ $schedule->section ?? '' }}
                                                                | {{ $schedule->Semester ?? 'N/A' }} {{ $schedule->School_year ?? 'N/A' }}
                                                            </small>
                                                        </div>
                                                        <div class="text-end">
                                                            <button class="btn btn-sm btn-outline-primary me-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal{{ $schedule->id }}">
                                                                Edit
                                                            </button>

                                                            <form method="POST" action="{{ route('schedules.destroy', $schedule->id) }}" class="d-inline"
                                                                  onsubmit="return confirm('Delete this schedule?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Edit Modal (inside schedule loop) --}}
                                                <div class="modal fade" id="editModal{{ $schedule->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Schedule</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <form method="POST" action="{{ route('schedules.update', $schedule->id) }}">
                                                                @csrf
                                                                @method('PUT')

                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Subject</label>
                                                                            <input type="text" class="form-control" name="Subject" value="{{ $schedule->Subject }}">
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Time</label>
                                                                            @php
                                                                                $timeSlots = [
                                                                                    '7:30-8:30','7:30-9:30','7:30-10-:30','7:30-11:30','7:30-12:30',
                                                                                    '8:00-9:30','8:00-10:30','8:00-11:30','8:00-12:30',
                                                                                    '8:30-9:30','8:30-10:30','8:30-11:30','8:30-12:30',
                                                                                    '9:00-10-:30','9:00-11:30','9:00-12:30',
                                                                                    '9:30-10-:30','9:30-11:30','9:30-12:30',
                                                                                    '10:30-11:30','10:30-12:30','10:30-1:00','10:30-1:30',
                                                                                    '1:00-2:00','1:00-2:30','1:00-3:00','1:00-3:30','1:00-4:00','1:00-4:30','1:00-5:00',
                                                                                    '1:30-2:30','1:30-3:00',
                                                                                    '2:00-3:00','2:00-3:30','2:00-4:00','2:00-4:30','2:00-5:00','2:00-5:30','2:00-6:00',
                                                                                    '3:00-4:00','3:00-4:30','3:00-5:00','3:00-5:30','3:00-6:00',
                                                                                    '3:30-4:30','3:30-5:00','3:30-5:30','3:30-6:00',
                                                                                    '4:30-5:30','4:30-6:00',
                                                                                    '5:00-6:00'
                                                                                ];
                                                                            @endphp
                                                                            <select class="form-select" name="Time" required>
                                                                                @foreach($timeSlots as $slot)
                                                                                    <option value="{{ $slot }}" {{ $schedule->Time == $slot ? 'selected' : '' }}>
                                                                                        {{ $slot }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-2">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Day</label>
                                                                            <input type="text" class="form-control" name="Day" value="{{ $schedule->Day }}">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Room</label>
                                                                            <input type="text" class="form-control" name="Room" value="{{ $schedule->Room }}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($schedules->isEmpty())
                                <p class="text-muted text-center py-4">No schedules added yet.</p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>

