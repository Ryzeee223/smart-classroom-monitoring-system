<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - eMonitor</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap-icons-1.10.5/bootstrap-icons.css">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background-color: #f8f9fa;
        }
        .account-row .col {
            padding: 0.25rem 0.5rem;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggles
            ['lead', 'fac'].forEach(suffix => {
                const toggle = document.getElementById('togglePassword-' + suffix);
                const password = document.getElementById('password-' + suffix);
                if (toggle && password) {
                    toggle.addEventListener('click', function () {
                        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                        password.setAttribute('type', type);
                        this.querySelector('i').classList.toggle('bi-eye');
                        this.querySelector('i').classList.toggle('bi-eye-slash');
                    });
                }
            });
        });
    </script>
</head>
<body>
<style>
    .app-sidebar{position:fixed; top:0; left:0; width:260px; height:100vh;}
    .app-shell{display:flex; min-height:100vh;}
    .app-shell__content{flex:1; min-width:0; margin-left:260px;}
    @media (max-width: 767.98px){
        .app-shell__content{margin-left:0;}
    }
</style>
<div class="app-shell">
    <div class="d-none d-md-block app-sidebar">
        @include('sidebar')
    </div>

    <!-- content -->
    <div class="app-shell__content">

    <div class="flex-grow-1" style="min-width: 0;">

        <!-- nav bar (mobile only) -->
        <div class="d-md-none mb-3">
            @include('sidebar')
        </div>

        <!-- main content -->
        <main class="container py-2">

            <h1 class="mb-4">Users Management</h1>


         @if (session('success'))
         <div class="alert alert-success alert-dismissible fade show" role="alert">
             {{ session('success') }}
             <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
         </div>
         @endif

         @if ($errors->any())
         <div class="alert alert-danger" role="alert">
             <strong>Could not save user:</strong>
             <ul class="mb-0">
                 @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                 @endforeach
             </ul>
         </div>
         @endif

    <!-- Add Users -->

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
        <div class="card p-3 shadow-sm bg-white border-0">
            <h2 class="h5 mb-2">Add User</h2>

            <form action="{{ route('users.store') }}" method="POST">
                 @csrf
                <div class="row g-3">
                    <div class="col-md">

                        {{-- Fill-up form basic info  --}}
                        <label class="form-label form-label-sm">First Name</label>
                        <input type="text" class="form-control form-control-sm" name="first_name" id="first_name-lead" placeholder="Enter first name">
                    </div>
                    <div class="col-md">
                        <label class="form-label form-label-sm">Middle Name</label>
                        <input type="text" class="form-control form-control-sm" name="middle_name" id="last_name-lead" placeholder="Enter last name">
                    </div>
                    <div class="form-label form-label-sm">Last Name
                    <input type="text" class="form-control form control-sm" name="last_name" id="middle_name-lead" placeholder="Enter middle name">
                </div>
            </div>

                <div class="row g-2 mt-2">
                    <div class="col-md">
                        <label class="form-label form-label-sm">Employee ID</label>
                        <input type="text" class="form-control form-control-sm" name="employee_ID" id="employee_ID-lead" placeholder="Enter employee ID">
                    </div>
                    <div class="col-md">
                        <label class="form-label form-label-sm">Email</label>
                        <input type="email" class="form-control form-control-sm" name="email" id="email-lead" placeholder="Enter email">
                    </div>
                </div>

                    <div class="mt-2">
                    <label class="form-label form-label-sm">College</label>
                    <select name="course" id="collegeSelect" class="form-select form-select-sm">
                            <option value="" selected>Select College</option>
                        @php $colleges = $colleges ?? collect(); @endphp
                        @foreach($colleges as $college)
                            @php
                                $collegeId = $college->id ?? '';
                                $collegeName = $college->college_name ?? $collegeId;
                                $collegeCode = $college->abbreviation ?? '';
                                $label = $collegeCode ? $collegeCode . ' - ' . $collegeName : $collegeName;
                            @endphp
                            <option value="{{ $collegeName }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-2">
                    <label class="form-label form-label-sm">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-sm" name="password" id="password-lead" placeholder="Enter password">
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="togglePassword-lead">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="row g-2 mt-2">
                    <div class="col-md">
                        <label class="form-label form-label-sm">Role</label>
                        <select class="form-select form-select-sm" name="role" id="role-lead">
                            <option selected>Select role</option>
                            @php
                                
                                $myRole = (int) (session('user_role') ?? 0);
                            @endphp

                                {{-- admin=1||dean=2||asst=3||faculty=4||head=5 --}}
                            @if($myRole === 1)
                                {{-- Admin can add: Dean, Assistant Dean, Faculty, Program Head --}}
                                <option value="2">Dean</option>
                                
                                
                            @elseif($myRole === 2)
                                {{-- Dean can add: Assistant Dean, Faculty, Program Head --}}
                                <option value="3">Assistant Dean</option>
                                <option value="4">Faculty</option>
                                <option value="5">Program Head</option>
                            @elseif($myRole === 3)
                                {{-- Assistant Dean can add: Faculty, Program Head --}}
                                <option value="4">Faculty</option>
                                <option value="5">Program Head</option>
                            @else
                                {{-- Faculty & Program Head cannot add roles --}}
                            @endif
                        </select>
                    </div>
                    <div class="col-md d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100 h-100">Add User</button>
                    </div>
                </div>
                
                {{-- Course dropdown: show only when creating a Dean --}}
                    <div class="row g-2 mt-2" id="adminCourseRow" style="display:none;">
                    <div class="col-md">
                        <label class="form-label form-label-sm">Course (for Admin)</label>
                        <select class="form-select form-select-sm" name="course" id="adminCourseSelect">
                            @php $courses = $courses ?? collect(); @endphp
                            <option value="">Select Course</option>
                            @foreach($colleges as $college)
                                @php
                                    $collegeId = $college->id ?? '';
                                    $collegeName = $college->college_name ?? $collegeId;
                                    $collegeCode = $college->abbreviation ?? '';
                                    $label = $collegeCode ? ($collegeName . ' (' . $collegeCode . ')') : $collegeName;
                                @endphp
                                <option value="{{ $collegeId }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <script>
                    (function() {
                        const roleSelect = document.getElementById('role-lead');
                        const adminCourseRow = document.getElementById('adminCourseRow');
                        const toggleAdminCourse = () => {
                            if (!roleSelect || !adminCourseRow) return;
                            adminCourseRow.style.display = (roleSelect.value === '1') ? 'block' : 'none';
                        };
                        if (roleSelect) roleSelect.addEventListener('change', toggleAdminCourse);
                        toggleAdminCourse();

                        
                        adminCourseRow.style.display = (roleSelect.value === '1') ? 'block' : 'none';
                    })();
                </script>
            </form>
        </div>
        </div>
        <div class="col-lg-4">
        <div class="card p-3 shadow-sm bg-white border-1 h-100">
            <h5 class="mb-3 d-flex justify-content-between align-items-center">
                User Accounts
               
            </h5>
            <div class="table-responsive mb-0">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>RFID UID</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $all_users = collect();
                            $all_users = $all_users->merge($account_users ?? []);
                        @endphp

                        @forelse($all_users as $user)
                        <tr>
                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td>
                                @switch($user->role)
                                    @case(1) Admin @break
                                    @case(2) Dean @break
                                    @case(3) Assistant Dean @break
                                    @case(4) Faculty @break
                                    @case(5) Program Head @break
                                    @default Unknown
                                @endswitch
                            </td>
                            <td><span class="badge {{ $user->acc_status ? 'bg-success' : 'bg-danger' }}">{{ $user->acc_status ? 'Active' : 'Inactive' }}</span></td>
                            <td>
                                @if($user->RFID_code)
                                    <span class="badge bg-success">Assigned</span>
                                @else
                                    <span class="badge bg-secondary">Not Assigned</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No User accounts</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
        </main>
    </div>
</div>
</body>
</html>



