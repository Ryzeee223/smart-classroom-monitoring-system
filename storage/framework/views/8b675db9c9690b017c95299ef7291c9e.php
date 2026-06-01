<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedules - eMonitor</title>
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
</head>
<body>

    <style>
        .app-shell { display: flex; min-height: 100vh; }
        .app-shell__content {
            flex: 1;
            min-width: 0;
            margin-left: 260px; /* keep aligned with fixed sidebar */
        }

        @media (max-width: 767.98px) {
            .app-shell__content { margin-left: 0; }
        }

        /* Fix width / prevent layout stretching on Rooms page */
        .rooms-page {
            width: 100%;
            max-width: 1100px;
        }
    </style>

    <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="app-shell">
        <div class="app-shell__content">
            <main class="container rooms-page">
            <div class="card col-mb4">
<div class="row-md-2">
    <div action="POST">
        
        <div>
            <h4>Create building</h4>
            <p>This is where you create a building for colleges</p>
            <hr>
            <input type="text" placeholder="Building name" class="form-control form control-sm">
            <button class="btn btn-primary btn-sm w-100 h-100">Create Building</button>
        </div>
    </div>
     
              <div class="row-mb3 card shadow">
                <div>
                
                    <h4>Create Rooms</h4> <br>
                    <p>This is where you create a Room</p>
                    <div>
                         <input type="text" placeholder="Enter Classroom code (eg. cc101)" class="form-control form control-sm">
                        <button class="btn btn-primary btn-sm w-100 h-100">Create Room</button>
                    </div>
                    <div>
                        
                     <div class="">
                       
                        
                        <div class="">
                            <div>
                                
                            </div>
                        </div>
                    </div>
                
                    
                   
                </div>
              </div>
              </div>
                </div>

  

            </div>
             
            
        </main>
    </div>
</body>
</html>

<?php /**PATH /Users/macbook/Documents/capstone project/backups/emonitor 3rd phase copy/resources/views/rooms.blade.php ENDPATH**/ ?>