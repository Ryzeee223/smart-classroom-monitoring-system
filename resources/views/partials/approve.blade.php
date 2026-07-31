<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Approval-emonitor</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
    @include('sidebar')
  @if ($req->reason === 'Summer class')
    <!-- 1. Summer Class View -->
    <div class="card container-fluid bg-light">
        <div class="card-body text-center">
            <h1>Summer Class Approval</h1>
            <p>Please review the academic schedule details below.</p>
        </div>
    </div>

@elseif ($req->reason === 'official business leave')
    <!-- 2. Official Business View -->
    <div class="card container-fluid border-primary">
        <div class="card-body text-center">
            <h1>Official Business Approval</h1>
            <p>Requires travel documents and authorization signatures.</p>
        </div>
    </div>

@elseif ($req->reason === 'Sick leave')
    <!-- 3. Sick Leave View -->
    <div class="card container-fluid border-danger">
        <div class="card-body text-center">
            <h1>Sick Leave Approval</h1>
            <p>Please ensure a medical certificate is attached.</p>
        </div>
    </div>

@elseif ($req->reason === 'others')
    <!-- 4. Others View -->
    <div class="card container-fluid">
        <div class="card-body text-center">
            <h1>General Approval</h1>
            <p>Reason: {{ $req->custom_reason ?? 'No description provided' }}</p>
        </div>
    </div>

@else
    <!-- 5. Fallback Default View (Optional) -->
    <div class="alert alert-warning text-center">
        Unknown request type.
    </div>
@endif



</body>
</html>