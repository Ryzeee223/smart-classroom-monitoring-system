<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.favicon')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - RFInsiDe</title>
    
    @vite(['resources/sass/app.scss', 'resources/js/bootstrap-vendor.js'])
    <link rel="stylesheet" href="bootstrap-icons-1.10.5/bootstrap-icons.css">
    
<style>
        
body {
    background:#f5f7fb;
    overflow-x:hidden;
    overflow-y:auto;
}
.dashboard-container {
    padding-top:16px;
    padding-bottom:32px;
}
.dashboard-container .card {
    border:0;
    border-radius:10px;
}
.dashboard-container .card-header {
    border-bottom:1px solid rgba(0,0,0,.06);
}
.dashboard-container .stats-card {
    min-height:132px;
}
        .account-row .col {
            padding: 0.25rem 0.5rem;
        }
        .users-table-scroll {
            max-height: 65vh;
            overflow: auto;
        }
        .users-table-scroll thead {
            position: sticky;
            top: 0;
            z-index: 1;
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


    <main role="main" class="page-content">
<div class="container-fluid dashboard-container px-4" >
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
        <main class="">

            <h1 class="mb-4">Users Management</h1>


         @include('partials.feedback-modal')

    <!-- Add Users -->

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
        <div class="card p-3 shadow-lg bg-white border-1 h-100 card" >

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
                {{-- assign rfid --}}
                     <div class="mt-3">
                    <label class="form-label form-label-sm" for="newUserRfidLabel">Assign RFID</label>
                    <input type="hidden" name="RFID_code" id="newUserRfidInput" value="{{ old('RFID_code') }}">
                    <div class="input-group">
                        <span class="form-control form-control-sm bg-light font-monospace" id="newUserRfidLabel" aria-live="polite">
                            {{ old('RFID_code') ?: 'Tap a card on the RFID reader to assign it' }}
                        </span>
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="clearNewUserRfid">Clear</button>
                    </div>
                </div>
                {{-- basic info --}}
                    <div class="col-md">

                        {{-- Fill-up form basic info  --}}
                        <label class="form-label form-label-sm">First Name</label>
                        <input type="text" class="form-control form-control-sm" name="first_name" id="first_name-lead" placeholder="Enter first name">
                    </div>
                    <div class="col-md">
                        <label class="form-label form-label-sm">Middle Initial</label>
                        <input type="text" class="form-control form-control-sm" name="middle_name" id="middle_name-lead" placeholder="Enter middle initial">
                    </div>
                    <div class="form-label form-label-sm">Last Name
                    <input type="text" class="form-control form control-sm" name="last_name" id="last_name-lead" placeholder="Enter last name">
                </div>
            
        </div>

            {{-- Educ attain & acad rank --}}
<div class="row g-2 mt-2 d-box">

<div class="col-md-6 justify-content-center">
<label for="eduAttain" class="form-label">Educational Attainment</label>
<input type="text" class="form-control form-control-sm" placeholder="Enter Educational Attainment">
</div>
<div class="col-md-6 mt-2">
    <label for="acadRank" class="form-label">Academic Rank</label>
<input type="text" class="form-control form-control-sm" placeholder="Enter Academic Rank (e.g. Instructor I)">
</div>

</div>

                {{-- roles --}}
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
                        <button type="submit" class="btn btn-primary btn-sm w-100 h-100">Create User</button>
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

                    document.addEventListener('DOMContentLoaded', function () {
                        const rfidInput = document.getElementById('newUserRfidInput');
                        const rfidLabel = document.getElementById('newUserRfidLabel');
                        const clearButton = document.getElementById('clearNewUserRfid');
                        if (!rfidInput || !rfidLabel || !clearButton) return;

                        clearButton.addEventListener('click', function () {
                            rfidInput.value = '';
                            rfidLabel.textContent = 'Tap a card on the RFID reader to assign it';
                        });

                        async function readScannedRfid() {
                            try {
                                const response = await fetch('/api/check-latest-assignment', { cache: 'no-store' });
                                if (!response.ok) throw new Error(`RFID scan check failed: ${response.status}`);
                                const data = await response.json();

                                if (data.uid && data.uid !== 'N/A') {
                                    rfidInput.value = data.uid;
                                    rfidLabel.textContent = data.uid;
                                }
                            } catch (error) {
                                console.error('Unable to read the RFID scanner:', error);
                                if (!rfidInput.value) rfidLabel.textContent = 'Unable to read scanner';
                            }
                        }

                        readScannedRfid();
                        window.setInterval(readScannedRfid, 2000);
                    });
                </script>
         </form>
        </div>
        </div>
        <div class="col-lg-6">
        <div class="card p-3 shadow-lg bg-white border-1 h-100 card">
            <h5 class="mb-3 d-flex justify-content-between align-items-center">
                User Accounts
               
            </h5>
            <div class="table-responsive users-table-scroll mb-0">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>RFID UID</th>
                            <th>Actions</th>
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
                                <td>
                                    @php
                                        $accountStatus = strtolower((string) ($user->acc_status ?? ''));
                                    @endphp
                                    @if ($accountStatus === 'present')
                                        <span class="badge bg-success">{{ $user->acc_status }}</span>
                                    @elseif ($accountStatus === 'absent')
                                        <span class="badge bg-danger">{{ $user->acc_status }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $user->acc_status ?? 'N/A' }}</span>
                                    @endif

                                </td>
                                <td>
                                    @if($user->RFID_code)
                                        <span class="badge bg-success">Assigned</span>
                                    @else
                                        <span class="badge bg-danger">Not Assigned</span>   
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit" title="Edit" aria-label="Edit"
                                                data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                            ✏️
                                        </button>

                                        <form method="POST" action="{{ route('users.archive', $user->id) }}" onsubmit="return confirm('Archive this user?')" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Archive" aria-label="Archive">🗑</button>
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
            @foreach($all_users as $user)
                @php
                    $uRole = (int) ($user->role ?? 0);
                    $canView = $sessionRole === 1
                        ? $uRole === 2
                        : in_array($uRole, [3, 4, 5], true);
                @endphp
                @continue(!$canView)

                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <form method="POST" action="{{ route('users.update', $user->id) }}" class="modal-content">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="first_name_{{ $user->id }}" class="form-label">First Name</label>
                                        <input id="first_name_{{ $user->id }}" type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="middle_name_{{ $user->id }}" class="form-label">Middle Name</label>
                                        <input id="middle_name_{{ $user->id }}" type="text" class="form-control" name="middle_name" value="{{ $user->middle_name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name_{{ $user->id }}" class="form-label">Last Name</label>
                                        <input id="last_name_{{ $user->id }}" type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="employee_ID_{{ $user->id }}" class="form-label">Employee ID</label>
                                        <input id="employee_ID_{{ $user->id }}" type="text" class="form-control" name="employee_ID" value="{{ $user->employee_ID }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="role_{{ $user->id }}" class="form-label">Role</label>
                                        <select id="role_{{ $user->id }}" name="role" class="form-select" required>
                                            @foreach([2 => 'Dean', 3 => 'Assistant Dean', 4 => 'Faculty', 5 => 'Program Head'] as $roleId => $roleName)
                                                <option value="{{ $roleId }}" {{ (int) $user->role === $roleId ? 'selected' : '' }}>{{ $roleName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="acc_status_{{ $user->id }}" class="form-label">Status</label>
                                        <select id="acc_status_{{ $user->id }}" name="acc_status" class="form-select">
                                            <option value="Present" {{ $user->acc_status === 'Present' ? 'selected' : '' }}>Present</option>
                                            <option value="Absent" {{ $user->acc_status === 'Absent' ? 'selected' : '' }}>Absent</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="Academic_rank_{{ $user->id }}" class="form-label">Academic Rank</label>
                                        <input id="Academic_rank_{{ $user->id }}" type="text" class="form-control" name="Academic_rank" value="{{ $user->Academic_rank }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label for="Advisory_class_{{ $user->id }}" class="form-label">Advisory Class</label>
                                        <input id="Advisory_class_{{ $user->id }}" type="text" class="form-control" name="Advisory_class" value="{{ $user->Advisory_class }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="class_count_{{ $user->id }}" class="form-label">Advisory Count</label>
                                        <input id="class_count_{{ $user->id }}" type="number" class="form-control" name="class_count" value="{{ $user->class_count }}">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        </div>
    </div>
        </main>
    </div>
</div>
@include('partials.notifications-modal')
</main>

</html>
