<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    <title>Document</title>
</head>
<body>
    @include('sidebar')

    <div class="container">
        <div class="row-md-3">
            {{-- generate report --}}
            <div class="card-body">
                <h4>Generate Semetral Reports</h4>
                <divcol-md-3>
                    <button>Generate</button>
                </div>
            </div>
        </div>
        <div class="col-md-9"></div>
    </div>
</body>
</html>