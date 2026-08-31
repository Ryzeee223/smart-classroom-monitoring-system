<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>schedules - eMonitor</title>
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


@php

use Illuminate\Support\Carbon;
    $startTimes = [];
$endTimes = [];
$startTimesDisplay = [];
$endTimesDisplay = [];

// Start at 07:00 AM, end loop at 05:00 PM
$startTime = Carbon::createFromTimeString('07:00:00');
$endTimeLimit = Carbon::createFromTimeString('17:00:00');

while ($startTime <= $endTimeLimit) {
    $startTimes[] = $startTime->format('H:i:s'); // "07:00:00"
    $startTimesDisplay[] = $startTime->format('g:i A'); // "7:00 AM"

    $endTimes[] = $startTime->copy()->addHour()->format('H:i:s');
    $endTimesDisplay[] = $startTime->copy()->addHour()->format('g:i A');

    $startTime->addMinutes(30);
    } // Step by 30-minute intervals
@endphp

<div class="app-shell">
    @include('sidebar')

    <main class="app-shell__content d-flex justify-content-center">
        <div class="container mt-5">
            <div class="row align-items-start">
                {{-- Conflict alert --}}
                                <div id="scheduleConflictAlert" class="alert alert-danger mt-3 d-none" role="alert">
                                    Schedule conflict detected. Please choose another time or room.
                                </div>
                {{-- ADD SCHEDULE FORM --}}
                <div class="col-md-9">
                    <div class="card bg-light shadow ">
                        <div class="card-header">
                            <h5>Add Schedule for Faculty</h5>
                            @if(!empty($collegeName))
                            <div class="d-flex justify-content-center"><span class="mt-1 align-text-center justify-content-center">{{ $collegeName }}</span></div>
                                
                            @endif
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('schedules.store') }}">
                                @csrf

                                <div class="row">


                                <div class="row text-start">
                                    {{-- Semester / School Year--}}
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


                                    {{-- school year --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">School Year</label>
                                        <select class="form-select" name="School_year" required disabled>
                                            <option value="{{ $latestSemyr->school_year ?? '' }}">{{ $latestSemyr->school_year ?? 'Current School Year' }}</option>
                                        </select>
                                        <input type="hidden" name="School_year" value="{{ $latestSemyr->school_year ?? '' }}">
                                    </div>
                                </div>

                                {{-- selec faculty --}}
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Faculty</label>
                                        <select class="form-select" name="user_id" required>
                                            <option value="">Select Faculty</option>
                                            @foreach($faculty_list as $faculty)
                                                <option value="{{ $faculty->id }}" {{ (isset($selectedFacultyId) && (int)$selectedFacultyId === (int)$faculty->id) ? 'selected' : '' }}>
                                                    {{ $faculty->first_name }} {{ $faculty->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                <div class="row"> 
                                    {{-- Programs --}}
                                    <div class="row">
                                    <div class="col-md-12 mb-3 shadow-sm">
                                        <label class="form-label">Program</label>
                                        <select class="form-select" name="program_id" required>
                                            <option value="">Select Program</option>
                                                @foreach ($programs as $program)
                                                <option value="{{ $program->id  }}">{{ $program->Program_abbr }}</option>
                                               @endforeach

                                        </select>
                                    </div>
                                </div>
                                    
                                    {{-- year level --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Year Level</label>
                                        <select class="form-select" name="year_level" required>
                                            <option value="">Year</option>
                                            <option value="1">1st</option>
                                            <option value="2">2nd</option>
                                            <option value="3">3rd</option>
                                            <option value="4">4th</option>
                                        </select>
                                    </div>

                                        {{-- sectioning --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Section</label>
                                        <select class="form-select" name="section" required>
                                            <option value="">Section</option>
                                            @foreach(['A','B','C','D','E','F','G','H','I','J'] as $sec)
                                                <option value="{{ $sec }}">{{ $sec }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- course --}}
                                <div class="row mt-2">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Course</label>
                                        <select class="form-select" name="Course" required>
                                            <option value="">Select Course</option>
                                            @foreach($courses as $courses)
                                                <option value="{{ $courses->id }}">
                                                    {{ $courses->course_name }} ({{ $courses->course_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

{{-- start time --}}
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Start Time</label>
                                        <select class="form-select" name="Start_time" required>
                                            <option value="">Select Start Time</option>
                                            @foreach($startTimes as $index => $start)
                                                <option value="{{ $start }}">{{ $startTimesDisplay[$index] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- end time --}}
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">End Time</label>
                                        <select class="form-select" name="End_time" required>
                                            <option value="">Select End Time</option>
                                            @foreach($endTimes as $index => $end)
                                                <option value="{{ $end }}">{{ $endTimesDisplay[$index] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- rooms --}}
                                <div class="row mt-2">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Room</label>
                                        <select class="form-select" name="Room" required>
                                            <option value="">Room</option>
                                            @foreach($rooms as $room)
                                                <option value="{{ $room->id }}">{{ $room->room_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Day --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Day</label>
                                        <div class="d-flex flex-wrap gap-3 mt-1">
                                            @foreach(['Mon','Tue','Wed','Thu','Fri', 'Sat'] as $day)
                                                <label class="d-flex align-items-center gap-1">
                                                    <input type="checkbox" name="Day[]" value="{{ $day }}" class="form-check-input">
                                                    <span>{{ $day }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>



                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Save this schedule?')">
                                        Add Schedule
                                    </button>
                                </div>

                                

                                {{-- Conflict checker script --}}
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const form = document.querySelector('form[action="{{ route('schedules.store') }}"]');
                                        if (!form) return;

                                        const alertEl = document.getElementById('scheduleConflictAlert');
                                        const apiUrl = '{{ route('schedules.bookingsystem') }}';

                                        function getSelectedDays() {
                                            return Array.from(form.querySelectorAll('input[name="Day[]"]:checked')).map(el => el.value);
                                        }

                                        async function checkConflictForDay(day, roomId, startTime, endTime) {
                                            const payload = {
                                                day: day,
                                                room_id: roomId,
                                                start_time: startTime,
                                                end_time: endTime
                                            };

                                            const res = await fetch(apiUrl, {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                },
                                                body: JSON.stringify(payload)
                                            });

                                            if (!res.ok) {
                                                // Fail open: allow submission if the checker fails
                                                return false;
                                            }

                                            const data = await res.json();
                                            return !!data.conflict;
                                        }

                                        form.addEventListener('submit', async function (e) {
                                            alertEl.classList.add('d-none');

                                            const selectedDays = getSelectedDays();
                                            const roomId = form.querySelector('select[name="Room"]').value;
                                            const startTime = form.querySelector('select[name="Start_time"]').value;
                                            const endTime = form.querySelector('select[name="End_time"]').value;

                                            // If form is incomplete, let backend validation handle it
                                            if (!selectedDays.length || !roomId || !startTime || !endTime) return;

                                            e.preventDefault();

                                            for (const day of selectedDays) {
                                                const hasConflict = await checkConflictForDay(day, roomId, startTime, endTime);
                                                if (hasConflict) {
                                                    alertEl.classList.remove('d-none');
                                                    return;
                                                }
                                            }

                                            // No conflicts: submit
                                            form.submit();
                                        });
                                    });
                                </script>

                            </form>
                        </div>
                    </div>
                </div>

                {{-- RECENT SCHEDULES LIST --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Assigned Faculty Schedules</h5>
                            <span>Faculty schedules shown here and can be edited or delete</span>
                            @if(!empty($collegeName))
                                <div class="text-muted small mt-1">Showing users from: <strong>{{ $collegeName }}</strong></div>
                            @endif
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
                                                            {{-- Schedule fields (match schedule table columns) --}}
                                                            <strong>{{ $schedule->program?->Program_abbr ?? $schedule->Programs?->Program_abbr ?? 'N/A' }} {{ $schedule->year_level ?? '' }} {{$schedule->section }}</strong><br>
                                                            <strong>{{ $schedule->course->course_code ?? 'N/A' }}</strong><br>
                                                            <small class="text-muted">
                                                                {{ $schedule->day ?? ($schedule->Day ?? 'N/A') }} |
                                                                {{ $schedule->start_time }} - {{ $schedule->end_time }} |
                                                                {{ $schedule->room_id ?? ($schedule->Room ?? 'N/A') }}
                                                            </small><br>

                                                            <small class="text-muted">

                                                                
                                                                | {{ $schedule->Semester ?? 'N/A' }} {{ $schedule->School_year ?? 'N/A' }}
                                                            </small>
                                                        </div>
                                                        <div class="text-end pt-1">
                                                            <button class="btn btn-sm btn-outline-primary mb-1 d-block w-100"
                                                                    type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal{{ $schedule->id }}">
                                                                Edit
                                                            </button>

                                                            <form method="POST" action="{{ route('schedules.destroy', $schedule->id) }}" class="d-block w-100"
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
                                                                            <input type="text" class="form-control" name="Course" value="{{ $schedule->course_id }}">

                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="form-label">Program</label>
                                                                            <select class="form-select" name="program_id" required>
                                                                                <option value="">Select Program</option>
                                                                                @foreach($programs as $program)
                                                                                    <option value="{{ $program->id }}" {{ (isset($schedule->program_id) && (int)$schedule->program_id === (int)$program->id) ? 'selected' : '' }}>
                                                                                        {{ $program->Program_abbr }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
<div class="col-md-6 mb-3">
                                                                            <label class="form-label">Start Time</label>
                                                                            <select class="form-select" name="Start_time" required>
                                                                                @foreach($startTimes as $index => $start)
                                                                                    <option value="{{ $start }}" {{ $schedule->Start_time == $start ? 'selected' : '' }}>
                                                                                        {{ $startTimesDisplay[$index] }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">End Time</label>
                                                                            <select class="form-select" name="End_time" required>
                                                                                @foreach($endTimes as $index => $end)
                                                                                    <option value="{{ $end }}" {{ $schedule->End_time == $end ? 'selected' : '' }}>
                                                                                        {{ $endTimesDisplay[$index] }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label class="form-label">Day</label>
                                        <div class="d-flex flex-wrap gap-3 mt-1">
                                            @foreach(['Mon','Tue','Wed','Thu','Fri'] as $day)
                                                <label class="d-flex align-items-center gap-1">
                                                    <input type="checkbox" name="Day[]" value="{{ $day }}" class="form-check-input">
                                                    <span>{{ $day }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">Room</label>
                                                                            <input type="text" class="form-control" name="Room" value="{{ $schedule->room_id }}">

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
    @include('partials.notifications-modal')
</div>
</body>
