<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <title>eMonitor - Colleges</title>
</head>
<body>
    @include('sidebar')

    <style>
       body {
            font-family: system-ui, "Segoe UI", Roboto, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
            
        }
    </style>

    <div class="" style="margin-left:260px;">
    <main class=" container mt-4 mb-5 p-4 gap-4">
        <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success mt-3">{{ session('success') }}</div>
                            @endif
                <div class="card shadow">
                    <div class="card-header">
                        
                        <!-- create -->
                        <h3 class="card-title mb-0">Add New College</h3>
                    </div>
                    <div class="card-body">
<form method="POST" action="{{ route('college.store') }}"> 
                            <div class="mb-3">
<label for="abbreviation" class="form-label">College Abbreviation</label>
                                <input type="text" class="form-control" id="abbreviation" name="abbreviation" placeholder="e.g., CCIT, CCJE">
                            </div>
                            <div class="mb-3">
                                <label for="college_name" class="form-label">College Name</label>
                                <input type="text" class="form-control" id="college_name" name="college_name" placeholder="e.g., College of Communication and Information Technology">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the course"></textarea>
                            </div>
                            @csrf

                            <button type="submit" class="btn btn-primary">Save College</button>

                            
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-4">
                <!-- list -->
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Existing College</h3>
                    </div>
                    <div class="card-body">
                        <p>List of program currently in the system.</p>
                        <ul class="list-group list-group-flush">
                                    @forelse($college ?? [] as $college)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $college->college_name }}</strong> - <strong>{{ $college->abbreviation }}</strong>
                                    @if($college->description)<br><small>{{ $college->description }}</small>@endif
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('college.edit', $college->id) }}">Edit</a>
                                    <form method="POST" action="{{ route('college.destroy', $college->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold" onclick="return confirm('Delete this college?')">Delete</button>
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
