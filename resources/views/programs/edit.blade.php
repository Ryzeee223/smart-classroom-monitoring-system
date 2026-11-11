<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.favicon')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/sass/app.scss', 'resources/js/bootstrap-vendor.js'])
    <title>Edit Program</title>
</head>
<body>
    @include('sidebar')

    <style>
        .app-shell { display: flex; }
        .app-shell__content { flex: 1; min-width: 0; margin-left: 260px; }
        @media (max-width: 767.98px) {
            .app-shell__content { margin-left: 0; }
        }
    </style>

    <div class="app-shell">
        <main class="app-shell__content container mt-4 mb-5 p-4">
            <div class="card shadow">
                <div class="card-header">
                    <h1 class="h4 mb-0">Edit Program</h1>
                </div>
                <div class="card-body">
                    @include('partials.feedback-modal')

                    <form method="POST" action="{{ route('programs.update', $program->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="program_abbr" class="form-label">Program Abbreviation</label>
                            <input type="text" class="form-control" id="program_abbr" name="program_abbr" value="{{ old('program_abbr', $program->program_abbr) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="program_name" class="form-label">Program Name</label>
                            <input type="text" class="form-control" id="program_name" name="program_name" value="{{ old('program_name', $program->program_name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $program->description) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Program</button>
                        <a href="{{ route('programs') }}" class="btn btn-outline-secondary ms-2">Back</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
