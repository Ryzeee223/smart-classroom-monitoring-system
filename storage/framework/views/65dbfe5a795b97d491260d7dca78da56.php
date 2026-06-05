<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <title>eMonitor - My Schedule</title>
</head>
<body>

    <style>
        /* Keep layout consistent with other pages (fixed sidebar + content offset) */
        .app-shell{display:flex; min-height:100vh;}
        .app-sidebar{position:fixed; top:0; left:0; width:260px; height:100vh;}
        .app-shell__content{flex:1; min-width:0; margin-left:260px;}
        @media (max-width: 767.98px){
            .app-shell__content{margin-left:0;}
        }
    </style>

    <div class="app-shell">
        <div class="d-none d-md-block">
            <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="d-md-none">
            <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div class="app-shell__content">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Welcome, <?php echo e($current_user->first_name); ?> <?php echo e($current_user->last_name); ?>!</h5>
                        <p class="mb-0">Here's your schedule for the semester.</p>
                    </div>
            <div class="card-body">
                        <h4>My Schedule</h4>
                        <div class="table-responsive" style="min-height: 360px;">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Subject</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Room</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($schedule->Subject ?? 'N/A'); ?></td>
                                        <td><?php echo e($schedule->Day ?? 'N/A'); ?></td>
                                        <td><?php echo e($schedule->Time ?? 'N/A'); ?></td>
                                        <td><?php echo e($schedule->Room ?? 'N/A'); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No schedules found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
<?php /**PATH /Users/macbook/Documents/capstone project/backups/emonitor 3rd phase copy/resources/views/myschedule.blade.php ENDPATH**/ ?>