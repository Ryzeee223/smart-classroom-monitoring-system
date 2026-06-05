<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <title>Edit Course</title>
</head>
<body>
    @include('sidebar')

    <style>
        .app-shell{display:flex;}
        .app-shell__content{flex:1; min-width:0; margin-left:260px;}
        @media (max-width: 767.98px){
            .app-shell__content{margin-left:0;}
        }
    </style>

    <div class="app-shell">
        <main class="app-shell__content container mt-4 mb-5 p-4">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title mb-0">Edit Course</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('course.update', $course->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="course_code" class="form-label">Course Code</label>
                            <input type="text" class="form-control" id="course_code" name="course_code" value="{{ old('course_code', $course->course_code) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="course_name" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="course_name" name="course_name" value="{{ old('course_name', $course->course_name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $course->description) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Course</button>
                        <a href="{{ route('course') }}" class="btn btn-outline-secondary ms-2">Back</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

