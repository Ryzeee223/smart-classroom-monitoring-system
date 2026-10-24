<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
    
    @vite('resources/js/bootstrap-vendor.js')
    
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-md-3">@include('sidebar')</div>

                <div class="col-md-9 mt-5">
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
                    <div class="card shadow-sm">
                        <div class="card-body">
                            
                                            
                            <div class="d-flex align-items-center gap-3">
                                @php
                                  
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

                                 {{-- <img
                                    src="{{ $profileUrl ?? asset('images/default-avatar.png') }}"
                                    alt="Profile picture"
                                    style="width:140px; height:140px; object-fit:cover; border-radius:50%;"
                                > --}}

                                <div>
                                    <h4 class="mb-1">{{ $user ? ($user->first_name .' '.$user->middle_name .' ' . $user->last_name) : 'Guest' }}</h4>

                                    <div class="text-muted">
                                        @php
                                                    // Get the college name if the user has a college_id
                                   $currentCollegeId = (int) ($user?->college_id ?? 0);
                    $currentCollege = $currentCollegeId ? \App\Models\college::find($currentCollegeId) : null;
                    $currentCollegeName = $currentCollege?->abbreviation ?? '';
                                        @endphp
                                        <div><Strong>College:</Strong>{{$currentCollegeName ?? 'No College Assigned' }}</div>
                                        <div><strong>Employee ID:</strong> {{ $user->employee_ID ?? '-' }}</div>
                                        <div><strong>Role:</strong> {{ $roleName ?? 'Unknown' }}</div>
                                        <div><strong>Status:</strong> {{ $AccStatus ?? 'Unknown' }}</div>
                                    </div>
                                    

                                </div>
                                
                            </div>
                            <hr>
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary" id="changeprofilename" type="button" data-bs-toggle="modal" data-bs-target="#updateProfileNameModal">Update Profile Name</button>
                            </div>
                            <div class="modal fade" id="updateProfileNameModal" tabindex="-1" aria-labelledby="updateProfileNameModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form class="modal-content" action="{{ route('profile.name.update') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateProfileNameModalLabel">Update Profile Name</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="profileFirstName" class="form-label">First name</label>
                                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="profileFirstName" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" maxlength="100" required>
                                                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="profileMiddleName" class="form-label">Middle name</label>
                                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="profileMiddleName" name="middle_name" value="{{ old('middle_name', $user->middle_name ?? '') }}" maxlength="100" required>
                                                @error('middle_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="profileLastName" class="form-label">Last name</label>
                                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="profileLastName" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" maxlength="100" required>
                                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save Name</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @if($errors->hasAny(['first_name', 'middle_name', 'last_name']))
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        bootstrap.Modal.getOrCreateInstance(document.getElementById('updateProfileNameModal')).show();
                                    });
                                </script>
                            @endif
                            <hr>

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
                                            <p class="text-muted mb-3">Submit a Request Letter to the Dean</p>


                                            <form id="requestForm" action="{{ route('profile.request.store') }}" method="POST" class="row g-3">
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
                                                        
                                                        
                                                    </select>
                                                </div>

                                                <div class="col-12 text-end">
                                                    <button type="submit" class="btn btn-primary">Send Request</button>
                                                </div>

                                                <div class="modal fade" id="requestConfirmationModal" tabindex="-1" aria-labelledby="requestConfirmationModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content text-start">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="requestConfirmationModalLabel">Confirm Request</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="mb-3">Please double-check your request before sending.</p>
                                                                <dl class="row mb-0">
                                                                    <dt class="col-sm-4">Your Request</dt>
                                                                    <dd class="col-sm-8" id="confirmRequestLetter">-</dd>
                                                                    <dt class="col-sm-4">Reason</dt>
                                                                    <dd class="col-sm-8" id="confirmRequestReason">-</dd>
                                                                </dl>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
                                                                <button type="button" class="btn btn-primary" id="confirmRequestButton">Confirm and Send</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    const requestForm = document.getElementById('requestForm');
                                                    const requestModalElement = document.getElementById('requestConfirmationModal');
                                                    if (!requestForm || !requestModalElement) return;

                                                    const requestModal = new bootstrap.Modal(requestModalElement);
                                                    const confirmRequestButton = document.getElementById('confirmRequestButton');
                                                    let requestConfirmed = false;

                                                    requestForm.addEventListener('submit', function (event) {
                                                        if (requestConfirmed) {
                                                            requestConfirmed = false;
                                                            return;
                                                        }

                                                        event.preventDefault();
                                                        document.getElementById('confirmRequestLetter').textContent = requestForm.querySelector('[name="letter"]').value || 'Not provided';
                                                        document.getElementById('confirmRequestReason').textContent = requestForm.querySelector('[name="reason"]').selectedOptions[0]?.text || 'Not selected';
                                                        requestModal.show();
                                                    });

                                                    confirmRequestButton.addEventListener('click', function () {
                                                        requestConfirmed = true;
                                                        requestModal.hide();
                                                        requestForm.requestSubmit();
                                                    });
                                                });
                                            </script>

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
                                                                <th>Status</th>
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
                                                                        if (!empty($createdAt)) 
                                                                        {
                                                                            try {
                                                                                $createdAtFormatted = \Carbon\Carbon::parse($createdAt)->format('Y-m-d');
                                                                            } catch (\Throwable $e) {
                                                                                $createdAtFormatted = $createdAt;
                                                                            }
                                                                        }


                                                                    @endphp
                                                                    <td>{{ $createdAtFormatted ?? '-' }}</td>
                                                                    <td> {{$r->status}}</td>
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
    @include('partials.notifications-modal')
</body>
</html>
