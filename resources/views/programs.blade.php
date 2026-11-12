<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.favicon')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/sass/app.scss', 'resources/js/bootstrap-vendor.js'])

    <title>RFInsiDe - Programs</title>
</head>
<body>
    @include('sidebar')

    <style>
        /* match dashboard-admin layout so sidebar occupies its space */
        .app-shell{display:flex;}
        .app-shell__content{flex:1; min-width:0; margin-left:260px;}
        .programs-page .btn-primary { color: #fff; }
        .programs-page .btn-outline-secondary { color: #344256; }
        .programs-page .btn-outline-danger { color: #b02a37; }
        body[data-theme="dark"] .programs-page .list-group-item {
            --bs-list-group-bg: #1f2937;
            --bs-list-group-color: #edf2f7;
            --bs-list-group-border-color: rgba(148, 163, 184, 0.18);
            background: #1f2937;
            color: #edf2f7;
        }
        body[data-theme="dark"] .programs-page .btn-outline-secondary {
            background: rgba(46,111,181,0.2);
            border-color: rgba(125,211,252,0.55);
            color: #e0f2fe;
        }
        body[data-theme="dark"] .programs-page .btn-outline-secondary:hover {
            background: rgba(46,111,181,0.38);
            color: #fff;
        }
        body[data-theme="dark"] .programs-page .btn-outline-danger {
            background: rgba(190,24,93,0.16);
            border-color: rgba(251,113,133,0.55);
            color: #fecdd3;
        }
        body[data-theme="dark"] .programs-page .btn-outline-danger:hover {
            background: rgba(190,24,93,0.32);
            color: #fff;
        }
        @media (max-width: 767.98px){
            .app-shell__content{margin-left:0;}
        }
    </style>

    <div class="app-shell">
    <main class="app-shell__content programs-page container mt-4 mb-5 p-4 gap-4">  
        @include('partials.feedback-modal')
        <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow">
                  
                    <div class="card-header">
                        
                        <!-- create -->
                        <h3 class="card-title mb-0">Add New Program</h3>
                    </div>
                    <div class="card-body">
                         
<form method="POST" action="{{ route('programs.store') }}">

    @php
        $currentUser = \App\Models\User::find(session('user_id'));
        $currentCollegeId = $currentUser?->college_id;
    @endphp

    <input type="hidden" name="college_id" value="{{ $currentCollegeId }}">

    @php
        $currentCollege = null;
        if ($currentCollegeId) {
            $currentCollege = \App\Models\college::find($currentCollegeId);
        }
        $currentCollegeName = $currentCollege?->college_name ?? '';
    @endphp

    <div class="mb-3">
        <label for="college_id" class="form-label">College</label>
        <select class="form-select" id="college_id_display" disabled>
            <option value="" selected>
                {{ $currentCollegeName ?: ('Assigned college ID: ' . $currentCollegeId) }}
            </option>
        </select>
        {{-- actual value sent to backend --}}
        <input type="hidden" name="college_id" value="{{ $currentCollegeId }}">
    </div>

    <div class="mb-3">
        <label for="program_abbr" class="form-label">Program Abbreviation</label>
        <input type="text" class="form-control" id="program_abbr" name="program_abbr" placeholder="e.g., BSCS, BSED" required>
    </div>

    <div class="mb-3">
        <label for="program_name" class="form-label">Program Name</label>
        <input type="text" class="form-control" id="program_name" name="program_name" placeholder="e.g., Bachelor of Science in Computer Science" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the course"></textarea>
    </div>

    @csrf
    <button type="submit" class="btn btn-primary">Save Program</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-4">
                <!-- list -->
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Existing Programs</h3>
                    </div>
                    <div class="card-body">
                        <p>List of Programs currently in the system.</p>
                        <ul class="list-group list-group-flush">
@forelse($programs as $program)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $program->program_abbr }}</strong> - <strong>{{ $program->program_name }}</strong>
                                    @if($program->description)<br><small>{{ $program->description }}</small>@endif
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <a class="btn btn-sm btn-outline-secondary btn-edit" href="{{ route('programs.edit', $program->id) }}" title="Edit" aria-label="Edit">✏️</a>
                                    <form method="POST" action="{{ route('programs.archive', $program->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold" title="Archive" aria-label="Archive" onclick="return confirm('Archive this program?')">🗑</button>
                                    </form>
                                </div>
                            </li>
@empty
                            <li class="list-group-item text-muted">No Program yet.</li>
@endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('partials.notifications-modal')
</body>
</html>
