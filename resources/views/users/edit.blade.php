<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - RFINSIDE</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<style>
    .app-sidebar{position:fixed; top:0; left:0; width:260px; height:100vh;}
    .app-shell{display:flex; min-height:100vh;}
    .app-shell__content{flex:1; min-width:0; margin-left:260px;}
    @media (max-width: 767.98px){
        .app-shell__content{margin-left:0;}
    }

    .edit-page { width: 100%; max-width: 900px; }
</style>

@include('sidebar')

<div class="app-shell">
    <div class="app-shell__content">
        <main class="container edit-page py-4">
            <h1 class="mb-4">Edit User</h1>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>Could not save changes:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label form-label-sm">First Name</label>
                                <input type="text" class="form-control form-control-sm" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label form-label-sm">Middle Name</label>
                                <input type="text" class="form-control form-control-sm" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label form-label-sm">Last Name</label>
                                <input type="text" class="form-control form-control-sm" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label form-label-sm">Role</label>
                                <select name="role" class="form-select form-select-sm" required>
                                    @php
                                        $roleLabels = [
                                            2 => 'Dean',
                                            3 => 'Assistant Dean',
                                            4 => 'Faculty',
                                            5 => 'Program Head',
                                        ];
                                    @endphp
                                    @foreach($roleLabels as $roleId => $roleName)
                                        <option value="{{ $roleId }}" {{ (string)old('role', $user->role) === (string)$roleId ? 'selected' : '' }}>
                                            {{ $roleName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                           <div class="col-md-12">
                            <label class="form-label form-label-sm">Status</label>
                            <select name="acc_status" class="form-select form-select-sm">
                                <option value="Present" {{ old('acc_status', $user->acc_status) === 'Present' ? 'selected' : '' }}>Present</option>
                                <option value="Absent" {{ old('acc_status', $user->acc_status) === 'Absent' ? 'selected' : '' }}>Absent</option>
                            </select>
                           </div>

                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
                            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>
</body>
</html>

