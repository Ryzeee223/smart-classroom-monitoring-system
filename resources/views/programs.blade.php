<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <title>eMonitor - Programs</title>
</head>
<body>
    @include('sidebar')

    <style>
        /* match dashboard-admin layout so sidebar occupies its space */
        .app-shell{display:flex;}
        .app-shell__content{flex:1; min-width:0; margin-left:260px;}
        @media (max-width: 767.98px){
            .app-shell__content{margin-left:0;}
        }
    </style>

    <div class="app-shell">
    <main class="app-shell__content container mt-4 mb-5 p-4 gap-4">
        <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        
                        <!-- create -->
                        <h3 class="card-title mb-0">Add New Program</h3>
                    </div>
                    <div class="card-body">
<form method="POST" action="{{ route('programs.store') }}"> 
    
    <div class="mb-3">
        <label for="college" class="form-label">College</label>
        <select class="form-select" id="college" name="college_id" required>
            {{-- for college, it should be depending on the college of the dean --}}
@foreach($college as $college)
                <option value="{{ $college->abbr }}">{{ $college->college_name }}</option>
            @endforeach
    </div>
                            <div class="mb-3">
                                <label for="program_code" class="form-label">Program Abbreviation</label>
                                <input type="text" class="form-control" id="Program_abbr" name="Program_abbr" placeholder="e.g., BSCS, BSED">
                            </div>
                            <div class="mb-3">
                                <label for="program_name" class="form-label">Program Name</label>
                                <input type="text" class="form-control" id="Program_name" name="Program_name" placeholder="e.g., Bachelor of Science in Computer Science">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the course"></textarea>
                            </div>
                            @csrf
                            <button type="submit" class="btn btn-primary">Save Program</button>
                            @if (session('success'))
                                <div class="alert alert-success mt-3">{{ session('success') }}</div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-4">
                <!-- list -->
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Existing programs</h3>
                    </div>
                    <div class="card-body">
                        <p>List of programs currently in the system.</p>
                        <ul class="list-group list-group-flush">
@forelse($Programs as $program)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $program->program_code }}</strong> - <strong>{{ $program->program_name }}</strong>
                                    @if($program->description)<br><small>{{ $program->description }}</small>@endif
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('program.edit', $program->id) }}">Edit</a>
                                    <form method="POST" action="{{ route('program.destroy', $program->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this program?')">Delete</button>
                                    </form>
                                </div>
                            </li>
@empty
                            <li class="list-group-item text-muted">No program yet.</li>
@endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
