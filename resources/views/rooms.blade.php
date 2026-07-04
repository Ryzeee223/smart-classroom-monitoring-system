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
    <div action="POST">

        {{-- Create building --}}
        <div class="shadow p-3 mb-3 rounded border-1">
            <h4>Create building</h4>
            <p>This is where you create a building for colleges</p>
            <div class="d-grid gap-2 align-items-center">
                 <input type="text" placeholder="Building name" class="form-control">
            
            </div>
            <br>
            <div class="d-flex justify-content-center"> 
                <button class="btn btn-primary btn-sm w-100 buttons">Create Building</button>
             </div>
          
        </div>
    </div>

     {{-- Create Rooms --}}
            
                    <div class="shadow p-3 mb-3 bg-white rounded border-1">
                        <h4>Create Rooms</h4> <br>
                    <p>This is where you create a Room</p>
                    <div class="d-grid gap-2 align-items-center">
                         <input type="text" placeholder="Enter Classroom code (eg. cc101)" class="form-control form control-sm">
                        <input type="text" class="form-control" placeholder="Enter Classroom name (eg. Computer Lab)">
             
                    </div> 
                    <br>
                    <div class="d-flex justify-content-center"> 
                        
                         <button class="btn btn-primary btn-sm w-100 buttons">Create Room</button>
                        </div>
                </div>
                    
                    

                   
                        
                    
                     <div class="shadow p-3 mb-3 bg-white rounded border-1">
                        {{-- Assign rooms to buildings --}}
                        <h4>Assign Rooms to Building</h4>
                        <p>This is where you assign rooms on buildings</p>
                     </div>

                        <div class="">
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

