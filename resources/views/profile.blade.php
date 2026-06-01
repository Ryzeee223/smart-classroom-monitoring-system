<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-md-3">@include('sidebar')</div>

                <div class="col-md-9">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    // Build profile image URL safely.
                                    // profile_picture is stored as a path like: profile_pictures/<file>
                                    // but older records may store only a filename.
                                    $profileUrl = null;
                                    if (isset($user) && $user && !empty($user->profile_picture)) {
                                        $pic = $user->profile_picture;

                                        // If it already contains the directory, use it as-is
                                        if (is_string($pic) && str_contains($pic, 'profile_pictures/')) {
                                            $profileUrl = asset('storage/' . ltrim($pic, '/'));
                                        } else {
                                            // Assume it's a filename only
                                            $profileUrl = asset('storage/profile_pictures/' . ltrim((string) $pic, '/'));
                                        }
                                    }


                                    $roleMap = [
                                        1 => 'Admin',
                                        2 => 'Dean',
                                        3 => 'Assistant Dean',
                                        4 => 'Faculty',
                                        5 => 'Program Head',
                                    ];

                                    $roleName = $user && isset($roleMap[$user->role])
                                        ? $roleMap[$user->role]
                                        : ($user->role ?? null);
                                @endphp

                                <img
                                    src="{{ $profileUrl ?? asset('images/default-avatar.png') }}"
                                    alt="Profile picture"
                                    style="width:140px; height:140px; object-fit:cover; border-radius:50%;"
                                >

                                <div>
                                    <h4 class="mb-1">{{ $user ? ($user->first_name . ' ' . $user->last_name) : 'Guest' }}</h4>

                                    <div class="text-muted">
                                        <div><strong>Employee ID:</strong> {{ $user->employee_ID ?? '-' }}</div>
                                        <div><strong>Role:</strong> {{ $roleName ?? 'Unknown' }}</div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-3 text-center">
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profilePicForm">
                                    @csrf
                                    <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*" style="display:none;" required>
                                    {{-- display account status --}}

                                    <button type="button" class="btn btn-outline-primary" id="changePhotoBtn">Add / Replace Image</button>
                                    <button type="submit" class="btn btn-primary" id="savePhotoBtn" style="display:none;">Save</button>
                                </form>
                            </div>

                            <script>
                                const changeBtn = document.getElementById('changePhotoBtn');
                                const saveBtn = document.getElementById('savePhotoBtn');
                                const input = document.getElementById('profilePictureInput');

                                // If file input changes, reveal save button.
                                input.addEventListener('change', () => {
                                    if (input.files && input.files.length > 0) {
                                        saveBtn.style.display = 'inline-block';
                                    }
                                });

                                // Clicking the button should open the file picker.
                                changeBtn.addEventListener('click', () => input.click());
                            </script>

                            {{-- request form + history --}}
                            @if(isset($user) && in_array((int) $user->role, [2, 3, 4, 5], true))
                                <div class="mt-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="mb-1">Letter</h4>
                                            <p class="text-muted mb-3">This is where you will send an excuse letter</p>


                                            <form action="{{ route('profile.request.store') }}" method="POST" class="row g-3">
                                                @csrf

                                                <div class="col-12">
                                                    <label for="letter" class="form-label">Your request</label>
                                                    <input
                                                        type="text"
                                                        name="letter"
                                                        id="letter"
                                                        class="form-control"
                                                        placeholder="State your request here..."
                                                        required
                                                    >
                                                </div>

                                                <div class="col-12">
                                                    <label for="reason" class="form-label">Select option</label>
                                                    <select name="reason" id="reason" class="form-select" required>
                                                        <option value="" selected disabled>Select option</option>
                                                        <option value="Sick leave">Sick leave</option>
                                                        <option value="official business leave">Official Business leave</option>
                                                        <option value="Summer class">Summer class</option>
                                                    </select>
                                                </div>

                                                <div class="col-12 text-end">
                                                    <button type="submit" class="btn btn-primary">Send Request</button>
                                                </div>
                                            </form>

                                            {{-- Request history --}}
                                            @if(isset($requests) && $requests->count() > 0)
                                                <hr class="my-4">
                                                <h5 class="mb-3">Request History</h5>

                                                <div class="table-responsive">
                                                    <table class="table table-sm align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Letter</th>
                                                                <th>Reason</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($requests as $r)
                                                                <tr>
                                                                    <td>{{ $r->letter }}</td>
                                                                    <td>{{ $r->reason }}</td>
                                                                    @php
                                                                        // DB::table() returns timestamps as strings by default
                                                                        $createdAt = $r->created_at;
                                                                        $createdAtFormatted = null;
                                                                        if (!empty($createdAt)) {
                                                                            try {
                                                                                $createdAtFormatted = \Carbon\Carbon::parse($createdAt)->format('Y-m-d');
                                                                            } catch (\Throwable $e) {
                                                                                $createdAtFormatted = $createdAt;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <td>{{ $createdAtFormatted ?? '-' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <hr class="my-4">
                                                <p class="text-muted mb-0">No requests yet.</p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

