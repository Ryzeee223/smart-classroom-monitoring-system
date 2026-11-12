<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.favicon')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
     @vite(['resources/sass/app.scss', 'resources/js/bootstrap-vendor.js'])

    <title>RFInsiDe - My Schedule</title>
</head>
<body>
    <style>
        .app-shell{display:flex; min-height:100vh;}
        .app-shell__content{flex:1; min-width:0; margin-left:260px;}
        @media (max-width: 767.98px){
            .app-shell__content{margin-left:0;}
        }

        body{
            overflow-x:auto;
            overflow-y:auto;
        }

        .my-schedule-card {
            overflow: hidden;
            border: 1px solid rgba(46,111,181,0.12);
        }

        .my-schedule-card > .card-header {
            padding: 1.15rem 1.35rem;
            background: linear-gradient(135deg, rgba(47,143,98,0.13), rgba(46,111,181,0.12));
            border-bottom: 1px solid rgba(46,111,181,0.12);
        }

        .schedule-period {
            padding-top: 1rem;
            border-top: 1px solid rgba(46,111,181,0.12);
        }

        .schedule-period__header {
            padding: .7rem .85rem;
            margin-bottom: .75rem;
            background: linear-gradient(90deg, rgba(47,143,98,0.08), rgba(46,111,181,0.06));
            border-left: 4px solid #2f8f62;
            border-radius: .5rem;
        }

        .schedule-period__title {
            margin: 0;
            color: #1f6d4a;
            font-size: .8rem;
            letter-spacing: .06em;
        }

        .schedule-count {
            padding: .25rem .65rem;
            border: 1px solid rgba(46,111,181,0.16);
            border-radius: 999px;
            background: rgba(46,111,181,0.08);
            color: #245c98;
            font-size: .8rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .schedule-table thead th {
            padding-top: .8rem;
            padding-bottom: .8rem;
            background: linear-gradient(135deg, #2f8f62, #2e6fb5);
            border-color: rgba(255,255,255,0.16);
            color: #fff;
            white-space: nowrap;
        }

        .schedule-table tbody tr {
            transition: background-color .18s ease;
        }

        body[data-theme="dark"] .my-schedule-card {
            border-color: rgba(148,163,184,0.22);
        }

        body[data-theme="dark"] .my-schedule-card > .card-header {
            background: linear-gradient(135deg, rgba(47,143,98,0.24), rgba(46,111,181,0.24));
            border-bottom-color: rgba(148,163,184,0.2);
            color: #f1f5f9;
        }

        body[data-theme="dark"] .schedule-period {
            border-top-color: rgba(148,163,184,0.2);
        }

        body[data-theme="dark"] .schedule-period__header {
            background: linear-gradient(90deg, rgba(47,143,98,0.16), rgba(46,111,181,0.16));
            border-left-color: #f3d65d;
        }

        body[data-theme="dark"] .schedule-period__title {
            color: #b7f0ce;
        }

        body[data-theme="dark"] .schedule-count {
            background: rgba(46,111,181,0.2);
            border-color: rgba(125,211,252,0.24);
            color: #d7efff;
        }

    </style>

    <div class="app-shell">
        <div class="d-none d-md-block">
            @include('sidebar')
        </div>
      

        
            <div class="app-shell__content">
            <div class="container mt-5">


                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card my-schedule-card shadow-lg">
                            <div class="card-header">
                                <h5 class="mb-0">Welcome, {{ $current_user->first_name }} {{ $current_user->last_name }}!</h5>
                                <p class="mb-0">Here's your schedule for the semester.</p>
                            </div>

                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                    <h4 class="mb-0">My Schedule</h4>
                                    <span class="text-muted">{{ $schedules->count() }} schedule(s)</span>
                                </div>

                                @php
                                    // Group by semester+school year to make the page easier to read.
                                    $grouped = $schedules->groupBy(function($s){
                                        return trim(($s->Semester ?? 'N/A').' '.($s->School_year ?? 'N/A'));
                                    });

                                   
                                @endphp

                                @forelse($grouped as $groupKey => $groupItems)
                                    <div class="schedule-period mb-4">
                                        <div class="schedule-period__header d-flex align-items-center justify-content-between gap-2">
                                            <h6 class="schedule-period__title text-uppercase fw-bold">{{ $groupKey }}</h6>
                                            <span class="schedule-count">{{ $groupItems->count() }} item(s)</span>
                                        </div>

                                        <div class="table-responsive" style="min-height: 180px;">
                                            <table class="table table-striped table-hover align-middle schedule-table">
                                                <thead>
                                                    <tr>
                                                        <th>Program</th>
                                                        <th>Course</th>
                                                        <th>Day</th>
                                                        <th>Time</th>
                                                        <th>Room</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($groupItems as $schedule)
                                                        <tr>
                                                            <td>{{ $schedule->Program?->program_abbr ?? $schedule->programs?->program_abbr ?? $schedule->programs?->program_name ?? 'N/A' }} {{$schedule->year_level}} {{$schedule->section}}</td>
                                                            <td>
                                                                {{ $schedule->course?->course_code }}
                                                            </td>
                                                            <td>{{ $schedule->day ?? ($schedule->Day ?? 'N/A') }}</td>
                                                            <td>
                                                                @php
                                                                    $startTime = $schedule->start_time ?? ($schedule->Start_time ?? null);
                                                                    $endTime = $schedule->end_time ?? ($schedule->End_time ?? null);
                                                                @endphp
                                                                @if($startTime)
                                                                    {{ \Illuminate\Support\Carbon::parse($startTime)->format('g:i A') }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                                -
                                                                @if($endTime)
                                                                    {{ \Illuminate\Support\Carbon::parse($endTime)->format('g:i A') }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </td>
                                                            <td>{{ $schedule->room?->room_name }}</td>
                                                         
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <h5 class="mb-2">No schedules found.</h5>
                                        <p class="text-muted mb-0">Once your faculty schedules are assigned, they will appear here.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.notifications-modal')
</body>
</html>
