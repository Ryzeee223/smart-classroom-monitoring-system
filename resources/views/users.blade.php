<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - eMonitor</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap-icons-1.10.5/bootstrap-icons.css">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
            overflow-y: hidden;
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
    
</style>
<div class="mt-5" style=" margin-left:300px; ">
    <div class="d-none d-md-block app-sidebar">
        @include('sidebar')
    </div>

    <!-- content -->
    <div class="">

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
        <div class="card p-3 shadow-sm bg-white border-1" >
            <h2 class="h5 mb-2">Add User</h2>

            <form action="{{ route('users.store') }}" method="POST">
                 @csrf
                <div class="row g-3">
                    {{-- college --}}
                @php
                    $sessionRole = (int) (session('user_role') ?? 0);
                    $currentUser = \App\Models\User::find(session('user_id'));
                    $currentCollegeId = (int) ($currentUser?->college_id ?? 0);
                    $currentCollege = $currentCollegeId ? \App\Models\college::find($currentCollegeId) : null;
                    $currentCollegeName = $currentCollege?->college_name ?? '';
                @endphp

                <div class="mt-2">
                    <label class="form-label form-label-sm">College</label>

                    <select name="college_id" id="collegeSelect" class="form-select form-select-sm" {{ in_array($sessionRole, [2, 3], true) ? 'disabled' : '' }}>
                        @if(in_array($sessionRole, [2, 3], true))
                            <option value="{{ $currentCollegeId }}" selected>
                                {{ $currentCollegeName ?: 'Assigned college' }}
                            </option>
                        @else
                            <option value="" selected>Select College</option>
                            @php $college = $college ?? collect(); @endphp
                            @foreach($colleges as $college)
                                @php
                                    $collegeId = (int) ($college->id ?? 0);
                                    // Hide the first-created college (id=1) from all user assignment choices.
                                    if ($collegeId === 1) {
                                        continue;
                                    }
                                    $collegeName = $college->college_name ?? $collegeId;
                                    $collegeCode = $college->abbreviation ?? '';
                                    $label = $collegeCode ? $collegeCode . ' - ' . $collegeName : $collegeName;
                                @endphp
                                <option value="{{ $collegeId }}">{{ $label }}</option>
                            @endforeach
                        @endif
                    </select>

                    {{-- Dean/Assistant Dean: lock college_id to their assigned college. --}}
                    {{-- Admin: choose the college for the new user. --}}
                    @if(in_array($sessionRole, [2, 3], true))
                        <input type="hidden" name="college_id" value="{{ $currentCollegeId }}">
                    @endif
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
                                <option selected value="2">Dean</option>
                                
                                
                                
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
                
                </div>
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
                        {{-- employee id --}}
                        <label class="form-label form-label-sm">Employee ID</label>
                        <input type="text" class="form-control form-control-sm" name="employee_ID" id="employee_ID-lead" placeholder="Enter employee ID">
                    </div>

                    {{-- email --}}
                    <div class="col-md">
                        <label class="form-label form-label-sm">Email</label>
                        <input type="email" class="form-control form-control-sm" name="email" id="email-lead" placeholder="Enter email">
                    </div>
                </div>
{{-- password --}}
                <div class="mt-2">
                    <label class="form-label form-label-sm">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-sm" name="password" id="password-lead" placeholder="Enter password">
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="togglePassword-lead">
                            
                        </button>
                    </div>
                </div>
                <div class="row g-2 mt-2">
                   
                    
                </div>
                <div class="col-md d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100 h-100">Add User</button>
                    </div>
               
                    <div class="row g-2 mt-2" id="adminCourseRow" style="display:none;">
                    
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

@php
                            $sessionRole = (int) (session('user_role') ?? 0);
                        @endphp

                        @forelse($all_users as $user)
                            @php
                                $uRole = (int) ($user->role ?? 0);
                                $canView = (
                                    $sessionRole === 1
                                        ? $uRole === 2
                                        : ($sessionRole === 2 ? in_array($uRole, [3,4,5], true) : in_array($uRole, [3,4,5], true))
                                );
                            @endphp

                            @continue(!$canView)

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
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?')" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">Delete</button>
                                        </form>
                                    </div>
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



