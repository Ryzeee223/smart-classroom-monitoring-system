<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<title>Faculty Schedule Reports</title>
    <style>
        body {
            background: #f5f7fb;
            overflow-x: hidden;
            overflow-y:hidden;
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
            <div class="col-xl-10 col-lg-11 col-md-12">

                <div class="card schedule-card shadow-sm border-1">
                    <div class="schedule-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">Faculty Schedule Overview</h5>
                            <small class="text-muted d-block">{{ $todayLabel ?? now()->translatedFormat('l, F d, Y') }}</small>
                            <small class="text-muted d-block">
                                {{ $currentSemester ?? 'Current Semester' }} • {{ $currentSchoolYear ?? 'Current School Year' }}
                            </small>

                        </div>
                            <form method="POST" action="{{ route('reports.generate') }}" class="d-flex align-items-end gap-2">
                                @csrf
                                <div>
                                    <label for="date" class="form-label">Attendance Date</label>
                                    <select class="form-select" id="date" name="date" required>
                                        <option value="">Select attendance date</option>
                                        @foreach ($displayrep as $date)
                                            <option value="{{ $date }}">{{ \Illuminate\Support\Carbon::parse($date)->format('F d, Y') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Generate Excel</button>
                            </form>
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
                                        <td>{{ $schedule['start_display'] }} - {{ $schedule['end_display'] }}</td>
                                        <td>{{ $schedule['time_in'] ?? 'N/A' }}</td>
                                        <td>{{ $schedule['time_out'] ?? 'N/A' }}</td>
                                        <td>{{ $schedule['faculty'] }}</td>
                                        <td>{{ $schedule['course_code'] }}</td>
                                        <td>
                                            <span class="badge text-bg-secondary">
                                                {{ ucfirst(str_replace('_', ' ', $schedule['attendance_status'])) }}
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
            </div>
        </div>  
            </div>
    </div>
    @include('partials.notifications-modal')
</body>

</html>