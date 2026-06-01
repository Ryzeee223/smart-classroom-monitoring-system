<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <title>Login - eMonitor</title>
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100 bg-light py-3">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="card shadow w-100" style="max-width: 400px;">
            <div class="card-body p-4 p-md-5">
                <h1 class="card-title text-center mb-4 fs-2">Login</h1>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" placeholder="Enter email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
<div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" placeholder="Enter password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary w-100 btn-lg py-2">Login</button>
            </div>
        </div>
    </form>
</body>
</html>

