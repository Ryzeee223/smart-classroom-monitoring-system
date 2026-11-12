<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.favicon')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>schedules - RFINSIDE</title>
    @vite(['resources/sass/app.scss', 'resources/js/bootstrap-vendor.js'])
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
    .schedules-page .collapsing {
        transition: height 1.1s ease;
    }
    @media (prefers-reduced-motion: reduce) {
        .schedules-page .collapsing {
            transition-duration: .01ms;
        }
        .schedules-page .schedule-glass-item {
            animation: none;
        }
    }
    .schedule-form-card {
        border: 0;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    .schedule-form-card > .card-header {
        background: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, .08);
        padding: 1rem 1.25rem;
    }
    .schedule-form-card > .card-body {
        padding: 1.25rem;
    }
    .schedule-term-panel {
        background: #f8f9fa;
        border: 1px solid rgba(0, 0, 0, .08);
        border-radius: .5rem;
        padding: 1rem;
    }
    .schedule-day-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(4.5rem, 1fr));
        gap: .5rem;
    }
    .schedule-day-option {
        display: flex;
        align-items: center;
        gap: .4rem;
        min-height: 2.5rem;
        padding: .5rem .65rem;
        border: 1px solid #dee2e6;
        border-radius: .375rem;
        background: #fff;
        cursor: pointer;
    }
    .schedule-day-option .form-check-input {
        margin: 0;
    }

    .schedules-page .schedule-dropdown {
        padding: .45rem;
        border: 1px solid rgba(46, 111, 181, .18);
        border-radius: .75rem;
        background: rgba(235, 245, 250, .48);
        -webkit-backdrop-filter: blur(14px);
        backdrop-filter: blur(14px);
        box-shadow: 0 10px 24px rgba(46, 111, 181, .12), 0 0 18px rgba(47, 143, 98, .08);
    }

    .schedules-page .schedule-glass-item {
        border: 1px solid rgba(255, 255, 255, .62);
        border-radius: .65rem;
        background: rgba(255, 255, 255, .58);
        -webkit-backdrop-filter: blur(12px);
        backdrop-filter: blur(12px);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .68), 0 6px 16px rgba(31, 109, 74, .1);
        animation: scheduleGlassReveal .75s cubic-bezier(.2, .75, .3, 1) both;
    }

    @keyframes scheduleGlassReveal {
        from {
            opacity: 0;
            filter: blur(6px);
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            filter: blur(0);
            transform: translateY(0);
        }
    }

    body[data-theme="dark"] .schedules-page .card-header {
        background: linear-gradient(135deg, rgba(47,143,98,0.2), rgba(46,111,181,0.2));
        border-bottom-color: rgba(148, 163, 184, 0.2);
        color: #edf2f7;
    }

    body[data-theme="dark"] .schedules-page .schedule-term-panel {
        background: #172233;
        border-color: rgba(148, 163, 184, 0.22);
        color: #edf2f7;
    }

    body[data-theme="dark"] .schedules-page .schedule-dropdown {
        border-color: rgba(125, 211, 252, .22);
        background: rgba(15, 23, 42, .42);
        box-shadow: 0 12px 28px rgba(2, 6, 23, .3), 0 0 20px rgba(46, 111, 181, .12);
    }

    body[data-theme="dark"] .schedules-page .schedule-glass-item {
        border-color: rgba(125, 211, 252, .2);
        background: rgba(31, 41, 55, .62);
        box-shadow: inset 0 1px 0 rgba(226, 232, 240, .1), 0 8px 18px rgba(47, 143, 98, .12), 0 0 16px rgba(46, 111, 181, .1);
        color: #edf2f7;
    }

    body[data-theme="dark"] .schedules-page .schedule-day-option {
        background: #1f2937;
        border-color: rgba(148, 163, 184, 0.28);
        color: #e2e8f0;
    }

    body[data-theme="dark"] .schedules-page input[type="checkbox"].form-check-input {
        width: 1.15rem;
        height: 1.15rem;
        border-color: #7c93a8;
        background-color: #0f172a;
        box-shadow: 0 0 0 1px rgba(125, 211, 252, 0.16), 0 0 10px rgba(46, 111, 181, 0.2);
        cursor: pointer;
    }

    body[data-theme="dark"] .schedules-page input[type="checkbox"].form-check-input:checked {
        border-color: #86efac;
        background-color: #2f8f62;
        box-shadow: 0 0 0 2px rgba(243, 214, 93, 0.18), 0 0 16px rgba(47, 143, 98, 0.38), 0 0 20px rgba(46, 111, 181, 0.2);
    }

    body[data-theme="dark"] .schedules-page input[type="checkbox"].form-check-input:focus-visible {
        border-color: #7dd3fc;
        box-shadow: 0 0 0 3px rgba(46, 111, 181, 0.34), 0 0 16px rgba(47, 143, 98, 0.24);
    }

    body[data-theme="dark"] .schedules-page .bg-light {
        background: #1f2937 !important;
        border-color: rgba(148, 163, 184, 0.24) !important;
        color: #edf2f7 !important;
    }

    body[data-theme="dark"] .schedules-page .text-muted {
        color: #b6c2d1 !important;
    }

    body[data-theme="dark"] .schedules-page .btn-link {
        color: #7dbdf6;
    }

    body[data-theme="dark"] .schedules-page .btn-outline-primary {
        background: rgba(46,111,181,0.2);
        border-color: rgba(125,211,252,0.55);
        color: #e0f2fe;
    }

    body[data-theme="dark"] .schedules-page .btn-outline-danger {
        background: rgba(190,24,93,0.16);
        border-color: rgba(251,113,133,0.55);
        color: #fecdd3;
    }

    body[data-theme="dark"] .schedules-page .btn-outline-primary:hover,
    body[data-theme="dark"] .schedules-page .btn-outline-danger:hover {
        color: #fff;
        box-shadow: 0 0 18px rgba(47,143,98,0.18), 0 0 24px rgba(46,111,181,0.15);
    }

    .schedules-page .schedule-faculty-toggle {
        width: 100%;
        min-height: 2.75rem;
        padding: .6rem .85rem !important;
        border: 1px solid rgba(47,143,98,0.2);
        border-radius: .5rem;
        background: rgba(47,143,98,0.07);
        color: #1f6d4a;
        font-weight: 700;
        text-decoration: none;
        transition: background-color .18s ease, box-shadow .18s ease;
    }

    .schedules-page .schedule-faculty-toggle::after {
        content: "";
        width: .55rem;
        height: .55rem;
        margin-left: .75rem;
        border-right: 2px solid currentColor;
        border-bottom: 2px solid currentColor;
        transform: rotate(45deg) translateY(-2px);
        transition: transform .18s ease;
    }

    .schedules-page .schedule-faculty-toggle[aria-expanded="true"]::after {
        transform: rotate(225deg) translate(-2px, -1px);
    }

    .schedules-page .schedule-faculty-toggle:hover,
    .schedules-page .schedule-faculty-toggle:focus-visible {
        background: rgba(47,143,98,0.13);
        box-shadow: 0 0 0 2px rgba(47,143,98,0.12), 0 6px 16px rgba(46,111,181,0.12);
    }

    .schedules-page .schedule-actions .btn {
        min-height: 2.25rem;
        border-radius: .45rem;
        font-weight: 600;
        transition: background-color .18s ease, box-shadow .18s ease;
    }

    .schedules-page .schedule-actions .btn-outline-primary {
        background: rgba(46,111,181,0.08);
        color: #245c98;
    }

    .schedules-page .schedule-actions .btn-outline-danger {
        background: rgba(190,24,93,0.06);
        color: #a61b42;
    }

    body[data-theme="dark"] .schedules-page .schedule-faculty-toggle {
        background: rgba(47,143,98,0.17);
        border-color: rgba(110,231,168,0.28);
        color: #d1fae5;
    }

    body[data-theme="dark"] .schedules-page .schedule-faculty-toggle:hover,
    body[data-theme="dark"] .schedules-page .schedule-faculty-toggle:focus-visible {
        background: rgba(47,143,98,0.27);
        box-shadow: 0 0 0 2px rgba(243,214,93,0.12), 0 0 18px rgba(47,143,98,0.2), 0 0 22px rgba(46,111,181,0.16);
    }

    body[data-theme="dark"] .schedules-page .schedule-actions .btn-outline-primary {
        color: #e0f2fe;
    }

    body[data-theme="dark"] .schedules-page .schedule-actions .btn-outline-danger {
        color: #fecdd3;
    }

    @media (max-width: 575.98px) {
        .schedule-form-card > .card-body {
            padding: 1rem;
        }
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
    $startTimesDisplay[] = $startTime->format('g:i A');

    $endTimes[] = $startTime->copy()->addHour()->format('H:i:s');
    $endTimesDisplay[] = $startTime->copy()->addHour()->format('g:i A');

    $startTime->addMinutes(30);
    } // Step by 30-minute intervals
@endphp

<div class="app-shell">
    @include('sidebar')

    <main class="app-shell__content schedules-page d-flex justify-content-center">
        <div class="container-fluid px-3 px-lg-4 py-4">
            <div class="row align-items-start">

                {{-- ADD SCHEDULE FORM --}}
                <div class="col-6 mb-4">
                    <div class="card schedule-form-card shadow-lg">
                        <div class="card-header">
                            <h1 class="h5 mb-0">Add Faculty Schedule</h1>
                            @if(!empty($collegeName))
                                <div class="text-muted small mt-1">{{ $collegeName }}</div>
                            @endif
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('schedules.store') }}">
                                @csrf

                                @php
                                    $latestSemyr = \App\Models\semyr::query()->latest('id')->first();
                                @endphp
                                <input type="hidden" name="Semester" value="{{ $latestSemyr->semester ?? '' }}">
                                <input type="hidden" name="School_year" value="{{ $latestSemyr->school_year ?? '' }}">
                                <input type="hidden" name="sched_Status" value="Regular">

                                <div class="schedule-term-panel mb-4">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="text-muted small">Semester</div>
                                            <div class="fw-semibold">{{ $latestSemyr->semester ?? 'Current Semester' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-muted small">School Year</div>
                                            <div class="fw-semibold">{{ $latestSemyr->school_year ?? 'Current School Year' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="scheduleFaculty">Faculty</label>
                                        <select class="form-select" name="user_id" id="scheduleFaculty" required>
                                            <option value="">Select Faculty</option>
                                            @foreach($faculty_list as $faculty)
                                                <option value="{{ $faculty->id }}" {{ (isset($selectedFacultyId) && (int)$selectedFacultyId === (int)$faculty->id) ? 'selected' : '' }}>
                                                    {{ $faculty->first_name }} {{ $faculty->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="scheduleProgram">Program</label>
                                        <select class="form-select" name="program_id" id="scheduleProgram" required>
                                            <option value="">Select Program</option>
                                            @foreach($programs as $program)
                                                <option value="{{ $program->id }}">{{ $program->program_abbr }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="form-label" for="scheduleCourse">Subject</label>
                                        <select class="form-select" name="Course" id="scheduleCourse" required>
                                            <option value="">Select Subject</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}">({{ $course->course_code }}) {{ $course->course_name }} <span class="mb-10">Unit: {{$course->unit}}</span></option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-6 col-lg-3">
                                        <label class="form-label" for="scheduleYear">Year Level</label>
                                        <select class="form-select" name="year_level" id="scheduleYear" required>
                                            <option value="">Year</option>
                                            <option value="1">1st</option>
                                            <option value="2">2nd</option>
                                            <option value="3">3rd</option>
                                            <option value="4">4th</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-6 col-lg-3">
                                        <label class="form-label" for="scheduleSection">Section</label>
                                        <select class="form-select" name="section" id="scheduleSection" required>
                                            <option value="">Section</option>
                                            @foreach(['A','B','C','D','E','F','G','H','I','J'] as $section)
                                                <option value="{{ $section }}">{{ $section }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="scheduleStartTime">Start Time</label>
                                        <select class="form-select" name="Start_time" id="scheduleStartTime" required>
                                            <option value="">Select Start Time</option>
                                            @foreach($startTimes as $index => $start)
                                                <option value="{{ $start }}">{{ $startTimesDisplay[$index] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="scheduleEndTime">End Time</label>
                                        <select class="form-select" name="End_time" id="scheduleEndTime" required>
                                            <option value="">Select End Time</option>
                                            @foreach($endTimes as $index => $end)
                                                <option value="{{ $end }}">{{ $endTimesDisplay[$index] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="scheduleRoom">Room</label>
                                        <select class="form-select" name="Room" id="scheduleRoom" required>
                                            <option value="">Select Room</option>
                                            @foreach($rooms as $room)
                                                <option value="{{ $room->id }}">{{ $room->room_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <fieldset class="mb-0">
                                            <legend class="form-label mb-2">Days</legend>
                                            <div class="schedule-day-options">
                                                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                                                    <label class="schedule-day-option">
                                                        <input type="checkbox" name="Day[]" value="{{ $day }}" class="form-check-input">
                                                        <span>{{ $day }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary w-100">Add Schedule</button>
                                </div>

                                {{-- Conflict modal --}}
                                <div class="modal fade" id="scheduleConflictModal" tabindex="-1" aria-labelledby="scheduleConflictModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="scheduleConflictModalLabel">Schedule Conflict</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                A schedule conflict has been detected. Please choose another time or room.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Schedule confirmation modal --}}
                                <div class="modal fade" id="scheduleConfirmationModal" tabindex="-1" aria-labelledby="scheduleConfirmationModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="scheduleConfirmationModalLabel">Confirm Schedule</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-3">Please double-check the schedule details before saving.</p>
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-4">Semester</dt>
                                                    <dd class="col-sm-8" id="confirmSemester">-</dd>
                                                    <dt class="col-sm-4">School Year</dt>
                                                    <dd class="col-sm-8" id="confirmSchoolYear">-</dd>
                                                    <dt class="col-sm-4">Faculty</dt>
                                                    <dd class="col-sm-8" id="confirmFaculty">-</dd>
                                                    <dt class="col-sm-4">Program</dt>
                                                    <dd class="col-sm-8" id="confirmProgram">-</dd>
                                                    <dt class="col-sm-4">Course</dt>
                                                    <dd class="col-sm-8" id="confirmCourse">-</dd>
                                                    <dt class="col-sm-4">Year / Section</dt>
                                                    <dd class="col-sm-8" id="confirmYearSection">-</dd>
                                                    <dt class="col-sm-4">Room</dt>
                                                    <dd class="col-sm-8" id="confirmRoom">-</dd>
                                                    <dt class="col-sm-4">Day</dt>
                                                    <dd class="col-sm-8" id="confirmDays">-</dd>
                                                    <dt class="col-sm-4">Time</dt>
                                                    <dd class="col-sm-8" id="confirmTime">-</dd>
                                                </dl>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
                                                <button type="button" class="btn btn-primary" id="confirmScheduleButton">Confirm and Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($errors->any())
                                    <div class="modal fade" id="scheduleValidationModal" tabindex="-1" aria-labelledby="scheduleValidationModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="scheduleValidationModalLabel">Schedule Not Saved</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Please correct the following before saving:</p>
                                                    <ul class="mb-0">
                                                        @foreach($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(session('success') || session('error'))
                                    @php
                                        $scheduleResultIsError = session()->has('error');
                                        $scheduleResultMessage = session('error') ?? session('success');
                                    @endphp
                                    <div class="modal fade" id="scheduleResultModal" tabindex="-1" aria-labelledby="scheduleResultModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="scheduleResultModalLabel">
                                                        {{ $scheduleResultIsError ? 'Schedule Error' : 'Schedule Updated' }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">{{ $scheduleResultMessage }}</div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Conflict checker script --}}
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const form = document.querySelector('form[action="{{ route('schedules.store') }}"]');
                                        if (!form) return;

                                        const apiUrl = '{{ route('schedules.bookingsystem') }}';
                                        const conflictModal = new bootstrap.Modal(document.getElementById('scheduleConflictModal'));
                                        const confirmationModal = new bootstrap.Modal(document.getElementById('scheduleConfirmationModal'));
                                        const confirmButton = document.getElementById('confirmScheduleButton');
                                        let confirmationAccepted = false;

                                        function selectedText(selector, fallback = 'Not selected') {
                                            const select = form.querySelector(selector);
                                            return select?.selectedOptions[0]?.text.trim() || fallback;
                                        }

                                        function populateConfirmation() {
                                            document.getElementById('confirmSemester').textContent = form.querySelector('input[name="Semester"]')?.value || 'Current Semester';
                                            document.getElementById('confirmSchoolYear').textContent = form.querySelector('input[name="School_year"]')?.value || 'Current School Year';
                                            document.getElementById('confirmFaculty').textContent = selectedText('select[name="user_id"]');
                                            document.getElementById('confirmProgram').textContent = selectedText('select[name="program_id"]');
                                            document.getElementById('confirmCourse').textContent = selectedText('select[name="Course"]');
                                            document.getElementById('confirmYearSection').textContent = `${selectedText('select[name="year_level"]')} / ${selectedText('select[name="section"]')}`;
                                            document.getElementById('confirmRoom').textContent = selectedText('select[name="Room"]');
                                            document.getElementById('confirmDays').textContent = getSelectedDays().join(', ') || 'Not selected';
                                            document.getElementById('confirmTime').textContent = `${selectedText('select[name="Start_time"]')} - ${selectedText('select[name="End_time"]')}`;
                                        }

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
                                            if (!confirmationAccepted) {
                                                e.preventDefault();
                                                populateConfirmation();
                                                confirmationModal.show();
                                                return;
                                            }

                                            confirmationAccepted = false;

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
                                                    conflictModal.show();
                                                    return;
                                                }
                                            }

                                            // No conflicts: submit
                                            window.showSubmitLoading(form);
                                            form.submit();
                                        });

                                        confirmButton.addEventListener('click', function () {
                                            confirmationAccepted = true;
                                            confirmationModal.hide();
                                            form.requestSubmit();
                                        });
                                    });
                                </script>
                                @if($errors->any())
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            bootstrap.Modal.getOrCreateInstance(document.getElementById('scheduleValidationModal')).show();
                                        });
                                    </script>
                                @endif
                                @if(session('success') || session('error'))
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            bootstrap.Modal.getOrCreateInstance(document.getElementById('scheduleResultModal')).show();
                                        });
                                    </script>
                                @endif

                            </form>
                        </div>
                    </div>
                </div>

                {{-- RECENT SCHEDULES LIST --}}
                <div class="col-md-6 mb-4">
                    <div class="card shadow">
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
                                        <button class="btn btn-link schedule-faculty-toggle d-flex justify-content-between align-items-center text-start" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#{{ $collapseId }}"
                                                aria-expanded="false"
                                                aria-controls="{{ $collapseId }}">
                                            <strong>{{ $userName }}</strong>
                                        </button>
                                    </div>

                                    <div class="collapse schedule-dropdown mt-2" id="{{ $collapseId }}">
                                        <div class="d-flex flex-column gap-2">
                                @foreach($userSchedules as $schedule)
                                                <div class="schedule-glass-item p-2">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>

                                                            {{-- Schedule fields --}}
                                                            <strong>{{ $schedule->Program?->program_abbr ?? $schedule->programs?->program_abbr ?? 'N/A' }} {{ $schedule->year_level ?? '' }} {{$schedule->section }}</strong><br>
                                                            <strong>{{ $schedule->course->course_code ?? 'N/A' }}</strong><br>
                                                            <small class="text-muted">
                                                                {{ $schedule->day ?? ($schedule->Day ?? 'N/A') }} |
                                                                {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('g:i A') }} |
                                                                {{ $schedule->room?->room_name ?? ($schedule->room_id ?? ($schedule->Room ?? 'N/A')) }}
                                                            </small><br>

                                                            <small class="text-muted">

                                                                
                                                                | {{ $schedule->Semester ?? 'N/A' }} {{ $schedule->School_year ?? 'N/A' }}
                                                            </small>
                                                        </div>
                                                        <div class="schedule-actions text-end pt-1">
                                                            <button class="btn btn-sm btn-outline-primary btn-edit mb-1 d-block w-100" title="Edit" aria-label="Edit"
                                                                    type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal{{ $schedule->id }}">
                                                                ✏️
                                                            </button>

                                                            <form method="POST" action="{{ route('schedules.archive', $schedule->id) }}" class="d-block w-100"
                                                                  onsubmit="return confirm('Archive this schedule?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Archive" aria-label="Archive">🗑</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- EDIT MODAL --}}
                                                <div class="modal fade schedule-edit-modal" id="editModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $schedule->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel{{ $schedule->id }}">Edit Schedule</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <form method="POST" action="{{ route('schedules.update', $schedule->id) }}">
                                                                @csrf
                                                                @method('PUT')

                                                                @php
                                                                    $editStartTime = $schedule->start_time ?? ($schedule->Start_time ?? '');
                                                                    $editEndTime = $schedule->end_time ?? ($schedule->End_time ?? '');
                                                                    $editStartTime = $editStartTime ? \Illuminate\Support\Carbon::parse($editStartTime)->format('H:i:s') : '';
                                                                    $editEndTime = $editEndTime ? \Illuminate\Support\Carbon::parse($editEndTime)->format('H:i:s') : '';
                                                                    $editDay = $schedule->day ?? ($schedule->Day ?? '');
                                                                @endphp
                                                                <input type="hidden" name="Semester" value="{{ $schedule->Semester ?? '' }}">
                                                                <input type="hidden" name="School_year" value="{{ $schedule->School_year ?? '' }}">

                                                                <div class="modal-body text-start">
                                                                    <div class="row">
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="form-label" for="editCourse{{ $schedule->id }}">Course</label>
                                                                            <select class="form-select" id="editCourse{{ $schedule->id }}" name="Course" required>
                                                                                <option value="">Select Course</option>
                                                                                @foreach($courses as $courseOption)
                                                                                    <option value="{{ $courseOption->id }}" @selected((int) $schedule->course_id === (int) $courseOption->id)>
                                                                                        {{ $courseOption->course_code }} - {{ $courseOption->course_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="form-label" for="editProgram{{ $schedule->id }}">Program</label>
                                                                            <select class="form-select" id="editProgram{{ $schedule->id }}" name="program_id" required>
                                                                                <option value="">Select Program</option>
                                                                                @foreach($programs as $program)
                                                                                    <option value="{{ $program->id }}" @selected((int) $schedule->program_id === (int) $program->id)>
                                                                                        {{ $program->program_abbr }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label" for="editStartTime{{ $schedule->id }}">Start Time</label>
                                                                            <select class="form-select" id="editStartTime{{ $schedule->id }}" name="Start_time" required>
                                                                                @foreach($startTimes as $index => $start)
                                                                                    <option value="{{ $start }}" @selected($editStartTime === $start)>
                                                                                        {{ $startTimesDisplay[$index] }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label" for="editEndTime{{ $schedule->id }}">End Time</label>
                                                                            <select class="form-select" id="editEndTime{{ $schedule->id }}" name="End_time" required>
                                                                                @foreach($endTimes as $index => $end)
                                                                                    <option value="{{ $end }}" @selected($editEndTime === $end)>
                                                                                        {{ $endTimesDisplay[$index] }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-12 mb-3">
                                                                            <span class="form-label d-block">Days</span>
                                                                            <div class="d-flex flex-wrap gap-3 mt-1">
                                                                                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                                                                                    <label class="d-flex align-items-center gap-1">
                                                                                        <input type="checkbox" name="Day[]" value="{{ $day }}" class="form-check-input" @checked($editDay === $day)>
                                                                                        <span>{{ $day }}</span>
                                                                                    </label>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label" for="editYearLevel{{ $schedule->id }}">Year Level</label>
                                                                            <input type="text" class="form-control" id="editYearLevel{{ $schedule->id }}" name="year_level" value="{{ $schedule->year_level }}" required maxlength="50">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label" for="editSection{{ $schedule->id }}">Section</label>
                                                                            <input type="text" class="form-control" id="editSection{{ $schedule->id }}" name="section" value="{{ $schedule->section }}" required maxlength="1">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label" for="editRoom{{ $schedule->id }}">Room</label>
                                                                            <select class="form-select" id="editRoom{{ $schedule->id }}" name="Room" required>
                                                                                <option value="">Select Room</option>
                                                                                @foreach($rooms as $roomOption)
                                                                                    <option value="{{ $roomOption->id }}" @selected((int) $schedule->room_id === (int) $roomOption->id)>
                                                                                        {{ $roomOption->room_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
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
    <script>
        document.addEventListener('show.bs.modal', (event) => {
            const modal = event.target;
            if (modal instanceof HTMLElement && modal.matches('.schedule-edit-modal') && modal.parentElement !== document.body) {
                document.body.append(modal);
            }
        });
    </script>
</div>
</body>
