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
                            <h5 class="card-title">Add Course</h5>
                            <form action="{{ route('course.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    @php
                                        $sessionRole = (int) (session('user_role') ?? 0);
                                        $currentUser = \App\Models\User::find(session('user_id'));
                                        $currentCollegeId = (int) ($currentUser?->college_id ?? 0);
                                    @endphp

                                    <input type="hidden" name="college_id" value="{{ $currentCollegeId }}">

                                    <label class="form-label">College</label>
                                    <div class="form-control-plaintext">
                                        @if($currentCollegeId)
                                            Assigned to your college (ID: {{ $currentCollegeId }})
                                        @else
                                            No assigned college. Please contact Admin.
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <input type="text" name="course_code" placeholder="course Code" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <input type="text" name="course_name" placeholder="Course Name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" name="description" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">Add Course</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Course List</h5>

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
                                        @forelse($course as $course)
                                            <tr>
                                                <td>{{ $course->course_name ?? 'N/A' }}</td>
                                                <td>{{ $course->course_code }}</td>
                                                <td>{{ $course->course_name }}</td>
                                                <td>{{ Str::limit($course->description, 30) }}</td>
                                                <td class="text-nowrap">
                                                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    <form method="POST" action="{{ route('courses.destroy', $course->id) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No course yet</td>
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

