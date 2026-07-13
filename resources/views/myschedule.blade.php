<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <title>eMonitor - My Schedule</title>
</head>
<body>
    <style>
        .app-shell{display:flex; min-height:100vh;}
        .app-shell__content{flex:1; min-width:0; margin-left:260px;}
        @media (max-width: 767.98px){
            .app-shell__content{margin-left:0;}
        }
    </style>

    <div class="app-shell">
        <div class="d-none d-md-block">
            @include('sidebar')
        </div>
        <div class="d-md-none">
            @include('sidebar')
        </div>

        
                <div class="app-shell__content">
            <div class="container mt-5">


                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card">
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
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6 class="text-uppercase fw-bold">{{ $groupKey }}</h6>
                                            <span class="text-muted">{{ $groupItems->count() }} item(s)</span>
                                        </div>

                                        <div class="table-responsive" style="min-height: 180px;">
                                            <table class="table table-striped align-middle">
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
                                                            <td>{{ $schedule->program?->Program_abbr ?? $schedule->Programs?->Program_abbr ?? $schedule->Programs?->Program_name ?? 'N/A' }} {{$schedule->year_level}} {{$schedule->section}}</td>
                                                            <td>
                                                                {{ $schedule->course?->course_code }}
                                                            </td>
                                                            <td>{{ $schedule->day ?? ($schedule->Day ?? 'N/A') }}</td>
                                                            <td>
                                                                {{ $schedule->start_time ?? ($schedule->Start_time ?? 'N/A') }}
                                                                -
                                                                {{ $schedule->end_time ?? ($schedule->End_time ?? 'N/A') }}
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
</body>
</html>

