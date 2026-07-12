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
    body {
        overflow-x: hidden;
    }
</style>

{{-- Predefining the arrays once at the very top to avoid repetitive messy code blocks --}}
@php
    $startTimes = [
        "7:00:00", "7:30:00", "8:00:00", "8:30:00", "9:00:00", "9:30:00", "10:00:00", "10:30:00",
        "11:00:00", "11:30:00", "12:00:00", "12:30:00", "1:00:00", "1:30:00", "2:00:00", "2:30:00",
        "3:00:00", "3:30:00", "4:00:00", "4:30:00", "5:00:00"
    ];

    $endTimes = [
        "8:00:00","8:30:00", "9:00:00", "9:30:00", "10:00:00", "10:30:00", "11:00:00", "11:30:00", "12:00:00", "12:30:00", "1:00:00", "1:30:00", "2:00:00", "2:30:00", "3:00:00", "3:30:00",
        "4:00:00", "4:30:00", "5:00:00", "5:30:00", "6:00:00"
    ];
@endphp

<div class="app-shell">
    @include('sidebar')

    <main class="app-shell__content">
        <div class="container mt-5">
            <div class="row">
                {{-- ADD SCHEDULE FORM --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Add Schedule for Faculty</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('schedules.store') }}">
                                @csrf

                                <div class="row text-start">
                                    {{-- Semester / School Year (top) --}}
                                    <div class="col-md-6 mb-3">
                                        @php
                                            $latestSemyr = \App\Models\semyr::query()->latest('id')->first();
                                        @endphp
                                        <label class="form-label">Semester</label>
                                        <select class="form-select" name="Semester" required disabled>
                                            <option value="{{ $latestSemyr->semester ?? '' }}">{{ $latestSemyr->semester ?? 'Current Semester' }}</option>
                                        </select>
                                        <input type="hidden" name="Semester" value="{{ $latestSemyr->semester ?? '' }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">School Year</label>
                                        <select class="form-select" name="School_year" required disabled>
                                            <option value="{{ $latestSemyr->school_year ?? '' }}">{{ $latestSemyr->school_year ?? 'Current School Year' }}</option>
                                        </select>
                                        <input type="hidden" name="School_year" value="{{ $latestSemyr->school_year ?? '' }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
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

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Year Level</label>
                                        <select class="form-select" name="year_level" required>
                                            <option value="">Year</option>
                                            <option value="1">1st</option>
                                            <option value="2">2nd</option>
                                            <option value="3">3rd</option>
                                            <option value="4">4th</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Section</label>
                                        <select class="form-select" name="section" required>
                                            <option value="">Section</option>
                                            @foreach(['A','B','C','D','E','F','G','H','I','J'] as $sec)
                                                <option value="{{ $sec }}">{{ $sec }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Course</label>
                                        <select class="form-select" name="Course" required>
                                            <option value="">Select Course</option>
                                            @foreach($course as $courses)
                                                <option value="{{ $courses->course_name }}">
                                                    {{ $courses->course_name }} ({{ $courses->course_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Start Time</label>
                                        <select class="form-select" name="Start_time" required>
                                            <option value="">Select Start Time</option>
                                            @foreach($startTimes as $start)
                                                <option value="{{ $start }}">{{ $start }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">End Time</label>
                                        <select class="form-select" name="End_time" required>
                                            <option value="">Select End Time</option>
                                            @foreach($endTimes as $end)
                                                <option value="{{ $end }}">{{ $end }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Room</label>
                                        <select class="form-select" name="Room" required>
                                            <option value="">Room</option>
                                            <option value="CC101">CC101</option>
                                            <option value="CC104">CC104</option>
                                            <option value="CC205">CC205</option>
                                            <option value="CC206">CC206</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Day</label>
                                        <div class="d-flex flex-wrap gap-3 mt-1">
                                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday'] as $day)
                                                <label class="d-flex align-items-center gap-1">
                                                    <input type="checkbox" name="Day[]" value="{{ $day }}" class="form-check-input">
                                                    <span>{{ $day }}</span>
                                                </label>
                                            @endforeach
                                        </div>
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

                {{-- RECENT SCHEDULES LIST --}}
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Schedules</h5>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: auto;">

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

                                <div class="border-bottom pb-3 mb-2 text-start">
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
                                                <div class="p-2 border rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <strong>{{ $schedule->Subject }}</strong><br>
                                                            <small class="text-muted">
                                                                {{ $schedule->Day }} |
                                                                {{ $schedule->start_time }} - {{ $schedule->end_time }} |
                                                                {{ $schedule->Room }}
                                                            </small><br>
                                                            <small class="text-muted">
                                                                {{ $schedule->year_level ?? '' }}{{ $schedule->section ?? '' }}
                                                                | {{ $schedule->Semester ?? 'N/A' }} {{ $schedule->School_year ?? 'N/A' }}
                                                            </small>
                                                        </div>
                                                        <div class="text-end pt-1">
                                                            <button class="btn btn-sm btn-outline-primary mb-1 d-block w-100"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal{{ $schedule->id }}">
                                                                Edit
                                                            </button>

                                                            <form method="POST" action="{{ route('schedules.destroy', $schedule->id) }}"
                                                                  onsubmit="return confirm('Delete this schedule?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- EDIT MODAL --}}
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

                                                                <div class="modal-body text-start">
                                                                    <div class="row">
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="form-label">Course</label>
                                                                            <input type="text" class="form-control" name="Course" value="{{ $schedule->Course }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">Start Time</label>
                                                                            <select class="form-select" name="Start_time" required>
                                                                                @foreach($startTimes as $start)
                                                                                    <option value="{{ $start }}" {{ $schedule->Start_time == $start ? 'selected' : '' }}>
                                                                                        {{ $start }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">End Time</label>
                                                                            <select class="form-select" name="End_time" required>
                                                                                @foreach($endTimes as $end)
                                                                                    <option value="{{ $end }}" {{ $schedule->End_time == $end ? 'selected' : '' }}>
                                                                                        {{ $end }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">Day</label>
                                                                            <input type="text" class="form-control" name="Day" value="{{ is_array($schedule->Day) ? implode(', ', $schedule->Day) : $schedule->Day }}">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
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