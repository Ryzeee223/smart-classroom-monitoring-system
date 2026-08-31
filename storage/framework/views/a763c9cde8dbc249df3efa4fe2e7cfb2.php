<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <title>RFInsiDe - My Schedule</title>
</head>
<body>
    <style>
        .app-shell{display:flex; min-height:100vh;}
        .app-shell__content{flex:1; min-width:0; margin-left:260px;}
        @media (max-width: 767.98px){
            .app-shell__content{margin-left:0;}
        }

        body{
            overflow-x:auto;
            overflow-y:auto;
        }
    </style>

    <div class="app-shell">
        <div class="d-none d-md-block">
            <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
      

        
            <div class="app-shell__content">
            <div class="container mt-5">


                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Welcome, <?php echo e($current_user->first_name); ?> <?php echo e($current_user->last_name); ?>!</h5>
                                <p class="mb-0">Here's your schedule for the semester.</p>
                            </div>

                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                    <h4 class="mb-0">My Schedule</h4>
                                    <span class="text-muted"><?php echo e($schedules->count()); ?> schedule(s)</span>
                                </div>

                                <?php
                                    // Group by semester+school year to make the page easier to read.
                                    $grouped = $schedules->groupBy(function($s){
                                        return trim(($s->Semester ?? 'N/A').' '.($s->School_year ?? 'N/A'));
                                    });

                                   
                                ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupKey => $groupItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6 class="text-uppercase fw-bold"><?php echo e($groupKey); ?></h6>
                                            <span class="text-muted"><?php echo e($groupItems->count()); ?> item(s)</span>
                                        </div>

                                        <div class="table-responsive" style="min-height: 180px;">
                                            <table class="table table-striped align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>Program</th>
                                                        <th>Course</th>
                                                        <th>Day</th>
                                                        <th>Time</th>
                                                        <th>Room</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groupItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                        <tr>
                                                            <td><?php echo e($schedule->program?->Program_abbr ?? $schedule->Programs?->Program_abbr ?? $schedule->Programs?->Program_name ?? 'N/A'); ?> <?php echo e($schedule->year_level); ?> <?php echo e($schedule->section); ?></td>
                                                            <td>
                                                                <?php echo e($schedule->course?->course_code); ?>

                                                            </td>
                                                            <td><?php echo e($schedule->day ?? ($schedule->Day ?? 'N/A')); ?></td>
                                                            <td>
                                                                <?php
                                                                    $startTime = $schedule->start_time ?? ($schedule->Start_time ?? null);
                                                                    $endTime = $schedule->end_time ?? ($schedule->End_time ?? null);
                                                                ?>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($startTime): ?>
                                                                    <?php echo e(\Illuminate\Support\Carbon::parse($startTime)->format('g:i A')); ?>

                                                                <?php else: ?>
                                                                    N/A
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                -
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($endTime): ?>
                                                                    <?php echo e(\Illuminate\Support\Carbon::parse($endTime)->format('g:i A')); ?>

                                                                <?php else: ?>
                                                                    N/A
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </td>
                                                            <td><?php echo e($schedule->room?->room_name); ?></td>
                                                         
                                                        </tr>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <div class="text-center py-5">
                                        <h5 class="mb-2">No schedules found.</h5>
                                        <p class="text-muted mb-0">Once your faculty schedules are assigned, they will appear here.</p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('partials.notifications-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>

<?php /**PATH D:\capstone project\backups\emonitor 3rd phase copy\resources\views/myschedule.blade.php ENDPATH**/ ?>