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
<form method="POST" action="{{ route('college.store') }}"> 
                            <div class="mb-3">
                                <label for="course_code" class="form-label">College Abbreviation</label>
                                <input type="text" class="form-control" id="abbreviation" name="abbreviation" placeholder="e.g., BSCS, BSED">
                            </div>
                            <div class="mb-3">
                                <label for="course_name" class="form-label">College Name</label>
                                <input type="text" class="form-control" id="college_name" name="college_name" placeholder="e.g., Bachelor of Science in Computer Science">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the course"></textarea>
                            </div>
                            @csrf
                            <button type="submit" class="btn btn-primary">Save College</button>
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
                        <h3 class="card-title mb-0">Existing program</h3>
                    </div>
                    <div class="card-body">
                        <p>List of program currently in the system.</p>
                        <ul class="list-group list-group-flush">
@forelse($college ?? [] as $college)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $college->college_name }}</strong> - <strong>{{ $college->cabbreviation }}</strong>
                                    @if($college->description)<br><small>{{ $college->description }}</small>@endif
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('college.edit', $college->id) }}">Edit</a>
                                    <form method="POST" action="{{ route('college.destroy', $college->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this college?')">Delete</button>
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
