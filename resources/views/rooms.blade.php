<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedules - eMonitor</title>
    <link href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
</head>
<body>

    <style>
        body {
            font-family: system-ui , "Segoe UI", Roboto, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        .buttons {
        
        height: 50px;
        }
        
    </style>

    @include('sidebar')

    <div class="mt-4" style="min-width:50%; margin-left:260px;">
        <div class="">
            <main class="container rooms-page">
            <div class="col-mb-4">
<div class="row-md-2">
    <div >
<form method="POST" action="{{ route("storeBldg.store") }}">
    @csrf
        {{-- Create building --}}
        <div class="shadow p-3 mb-3 rounded border-1">
<div class="d-flex align-items-center justify-content-between">
            <div>
                <h4>Create building</h4>
                <p>This is where you create a building for colleges</p>
            </div>
            <div class="ms-5">
                <h4 class="mb-0">Existing buildings</h4>
                <p class="mb-0 text-muted">Display the existing buildings here</p>
            </div>
        </div>
            <div class="d-grid gap-2 align-items-center">
                {{-- dropdown list for college that one of the option should be shared --}}
                <div>
                    <label>Building type</label>
                    <select value="">
                        <option value="">Select Building type</option>
                    </select>
                </div>
                 <input type="text" placeholder="Building name" class="form-control" name="bldg_name" id="bldg_name">
                 <input type="text" placeholder="Building abbreviation" class="form-control" name="bldg_abbr" id="bldg_abbr">
            
            </div>
            <br>
            <div class="d-flex justify-content-center"> 
                <button class="btn btn-primary btn-sm w-100 buttons">Create Building</button>
             </div>
          
        </div>
    </form>
    </div>

     {{-- Create Rooms --}}
            <form method="POST" action="{{ route('storeRoom.store') }}">
                    <div class="shadow p-3 mb-3 bg-white rounded border-1">
                        <h4>Create Rooms</h4> <br>
                    <p>This is where you create a Room</p>
                    <div class="d-grid gap-2 align-items-center">
                        <select name="college_id" class="form-select form-select-sm">
                            <option value="">Select College</option>
                            @forelse($colleges ?? [] as $c)
                                <option value="{{ $c->id }}">{{ $c->college_name ?? $c->abbreviation }}</option>
                            @empty
                                <option value="" disabled>No colleges available</option>
                            @endforelse
                        </select>

                         <input type="text" placeholder="Enter Classroom code (eg. cc101)" class="form-control form control-sm" required>
                        <select class="form-select form-select-sm" value="" required>
                            <option value="">Select Type</option>
                        <option value="Lec">Lecture</option>
                        <option value="Lab">Laboratory</option>
             </select>
                    </div> 
                    <br>
                    <div class="d-flex justify-content-center"> 
                        
                         <button class="btn btn-primary btn-sm w-100 buttons">Create Room</button>
                        </div>
                </div>
                    </form>
                   
                        
                    
                     <div class="shadow p-3 mb-3 bg-white rounded border-1">
                        {{-- Assign rooms to buildings --}}
                        <h4>Assign Rooms to Building</h4>
                        <p>This is where you assign rooms on buildings</p>
                     </div>
                        <div class="card">
                            <div>
                                {{-- dropdown option for the available classrooms --}}

                            </div>
                        </div>
                    </div>
                
                    {{-- Assign Scanner to Rooms --}}
                   
                
              </div>
              </div>
                </div>

  

         
             
            
        </main>
    </div>
</div>
</body>
</html>

