<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - eMonitor</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</head>
<body>

<style>
   body{
    overflow-x: hidden;
    
   }
</style>

@include('sidebar')

<div class="">
    <main class=" container mt-4 mb-5" style="margin-left: 35%; width: 50%;">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3>Settings</h3>
            </div>

            <div class="card-body">
                @if ((int) (session('user_role') ?? 0) === 1)
                    @include('partials.school-year-settings')

                    <!-- RFID Assign -->
                    <br>
                    <h4 class="mb-3">Assign RFID</h4>
                    <form action="{{ route('settings.assign_rfid') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="user_id" class="form-label">User</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>


<div class="mb-3">
    <label class="form-label">RFID Code <small class="text-muted">(Tap card)</small></label>
    <input type="hidden" name="rfid_code" id="rfid_input">
    <span id="rfid_label" class="form-control bg-light" style="font-family:monospace">N/A</span>
</div>

{{-- logic of the assigning ID --}}
<script>
    const rfidInput = document.getElementById('rfid_input');
    const rfidLabel = document.getElementById('rfid_label');

    // Poll your Laravel API endpoint every 2 seconds
    const pollInterval = setInterval(async () => {
        try {
            const response = await fetch('/api/check-latest-scan'); // The route we created earlier that reads Cache::get('latest_nfc_scan')
            const data = await response.json();

            if (data.uid && data.uid !== 'N/A') {
                // 1. Update the hidden input value
                rfidInput.value = data.uid;

                // 2. Update the visual span text
                rfidLabel.textContent = data.uid;

               
            }
        } catch (error) {
            console.error("Error fetching RFID scan:", error);
        }
    }, 2000);
</script>

                        <button type="submit" class="btn btn-primary">Assign RFID</button>
                    </form>

                @endif

                <br>

                
                <div class="card">
                    <div class="card-body">
                        <h5 class="mt-3">Reset my password</h5>
                        <form action="{{ route('settings.reset_password') }}" method="POST" class="mt-3">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Current password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New password</label>
                                <input type="password" name="password" class="form-control" required minlength="8">
                            </div>

                            <button type="submit" class="btn btn-primary">Reset password</button>
                        </form>
                    </div>
                </div>

                {{-- Admin-only: reset another user's password --}}
                @if ((int) (session('user_role') ?? 0) === 1)
                    <div class="card mt-3">
                        <div class="card-body">
                            <h4>Reset password (Admin)</h4>

                            {{-- Reset another user's password --}}
                            <form action="{{ route('settings.reset_user_password') }}" method="POST" class="mt-3">
                                @csrf

                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User</label>
                                    <select name="user_id" id="user_id" class="form-select" required>
                                        <option value="">Select a user</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">New password</label>
                                    <input type="password" name="password" class="form-control" required minlength="8">
                                </div>

                                <button type="submit" class="btn btn-primary">Reset user password</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>
</body>
</html>

