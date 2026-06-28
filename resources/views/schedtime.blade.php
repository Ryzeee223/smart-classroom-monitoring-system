<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Set date and time</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Set date and time</h1>

        <form method="POST" action="{{ route('users.set-datetime') }}">
            @csrf

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row g-3 align-items-end mb-3">
                        <div class="col-12">
                            <label class="form-label mb-2">Schedule (Monday - Saturday)</label>
                        </div>
                        <div class="col-12 text-muted" style="font-size: 0.95rem;">
                            Enter start and end time for each day.
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-nowrap" style="width: 140px;">Day</th>
                                    <th class="text-nowrap" style="width: 180px;">Start Time</th>
                                    <th class="text-nowrap" style="width: 180px;">End Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                                @endphp

                                @foreach($days as $day)
                                    @php
                                        $key = strtolower($day);
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $day }}</td>
                                        <td>
                                            <input
                                                type="time"
                                                class="form-control form-control-sm"
                                                name="schedule[{{ $key }}][start]"
                                                required
                                            >
                                        </td>
                                        <td>
                                            <input
                                                type="time"
                                                class="form-control form-control-sm"
                                                name="schedule[{{ $key }}][end]"
                                                required
                                            >
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</body>
</html>