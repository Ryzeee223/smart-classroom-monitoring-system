<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <title>emonitor</title>
</head>
<body>
    <style>
        /* Ensure content doesn’t overlap the fixed sidebar (matches dashboard-admin/course patterns) */
        .app-shell { display: flex; min-height: 100vh; }
        .app-shell__content { flex: 1; min-width: 0; margin-left: 260px; }
        @media (max-width: 767.98px) {
            .app-shell__content { margin-left: 0; }
        }
    </style>

    <div class="app-shell">
        @include('sidebar')

        <main class="app-shell__content container mt-4 mb-5 p-4">
            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Add Subject</h5>
                            <form action="{{ route('subjects.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="course_id" class="form-label">Course</label>
                                    <select name="course_id" id="course_id" class="form-select" required>
                                        <option value="">Select Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->course_name }} ({{ $course->course_code }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <input type="text" name="subject_code" placeholder="Subject Code" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <input type="text" name="subject_name" placeholder="Subject Name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" name="description" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">Add Subject</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Subject List</h5>

                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Desc</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subjects as $subject)
                                            <tr>
                                                <td>{{ $subject->course->course_name ?? 'N/A' }}</td>
                                                <td>{{ $subject->subject_code }}</td>
                                                <td>{{ $subject->subject_name }}</td>
                                                <td>{{ Str::limit($subject->description, 30) }}</td>
                                                <td class="text-nowrap">
                                                    <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    <form method="POST" action="{{ route('subjects.destroy', $subject->id) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No subjects yet</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

