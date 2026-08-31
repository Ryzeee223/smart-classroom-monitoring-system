<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <title>Faculty Schedule Reports</title>
    <style>
        body {
            background: #f5f7fb;
        }

        .schedule-card {
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 0.75rem;
            box-shadow: 0 0.125rem 0.75rem rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .schedule-header {
            background: rgba(13, 110, 253, 0.08);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            padding: 1rem 1.25rem;
        }

        .schedule-item {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            background: #fff;
            padding: 1rem;
            margin-bottom: 0.85rem;
        }

        .schedule-item.live {
            border-color: #86b7fe;
            background: rgba(13, 110, 253, 0.04);
        }

        .schedule-time {
            font-weight: 700;
            color: #0d6efd;
        }

        .subject-code {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #495057;
            text-transform: uppercase;
        }

        .status-badge {
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.35rem 0.6rem;
        }

        .upcoming-box {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            padding: 1rem;
        }

        @media (max-width: 767.98px) {
            .schedule-item {
                padding: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container-fluid p-4 mt-3">
        <div class="row justify-content-end">
            <div class="col-xl-10 col-lg-11 col-md-11">
                <div class="card schedule-card shadow-sm border-1">
                    <div class="schedule-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                       <form action="">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">Faculty Schedule Overview</h5>
                            <small class="text-muted d-block"><?php echo e($todayLabel ?? now()->translatedFormat('l, F d, Y')); ?></small>
                            <small class="text-muted d-block">
                                <?php echo e($currentSemester ?? 'Current Semester'); ?> • <?php echo e($currentSchoolYear ?? 'Current School Year'); ?>

                            </small>
                        </div>
                        <button class="btn btn-primary px-4">Generate Report</button>
                    </div>
                    </form>

                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 fw-bold text-dark">Today's Faculty Classes</h6>
                                    <span class="badge bg-light text-dark border"><?php echo e(count($facultySchedules)); ?> scheduled</span>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $facultySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <div class="schedule-item <?php echo e($class['is_live'] ? 'live' : ''); ?>">
                                        <div class="row align-items-center g-3">
                                            <div class="col-md-3">
                                                <div class="subject-code"><?php echo e($class['course_code']); ?></div>
                                                <div class="fw-bold text-dark mt-1"><?php echo e($class['subject']); ?></div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-muted small">Faculty</div>
                                                <div class="fw-semibold"><?php echo e($class['faculty']); ?></div>
                                                <div class="text-muted small"><?php echo e($class['role']); ?></div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-muted small">Classroom</div>
                                                <div class="fw-semibold"><?php echo e($class['room']); ?></div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="text-muted small">Date</div>
                                                <div class="fw-semibold"><?php echo e($class['date_display']); ?></div>
                                            </div>
                                            <div class="col-md-2 text-md-end">
                                                <div class="text-muted small">Start</div>
                                                <div class="schedule-time"><?php echo e($class['start_display']); ?></div>
                                                <div class="text-muted small mt-2">End</div>
                                                <div class="fw-semibold"><?php echo e($class['end_display']); ?></div>
                                                <div class="text-muted small mt-2">Attendance</div>
                                                <div class="fw-semibold text-capitalize"><?php echo e(str_replace('_', ' ', $class['attendance_status'])); ?></div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($class['time_in']): ?>
                                                    <div class="text-muted small">In: <?php echo e($class['time_in']); ?></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($class['time_out']): ?>
                                                    <div class="text-muted small">Out: <?php echo e($class['time_out']); ?></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <span class="status-badge mt-2 d-inline-block <?php echo e($class['is_live'] ? 'bg-success text-white' : 'bg-light text-dark border'); ?>">
                                                    <?php echo e($class['label']); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <div class="alert alert-light border mb-0">No faculty schedules available.</div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($nextClass): ?>
                                <div class="col-lg-4">
                                    <div class="upcoming-box h-100">
                                        <div class="text-muted small text-uppercase fw-bold mb-2">Next Class</div>
                                        <h6 class="fw-bold mb-2"><?php echo e($nextClass['faculty']); ?></h6>
                                        <div class="fw-bold text-dark"><?php echo e($nextClass['course_code']); ?> • <?php echo e($nextClass['subject']); ?></div>
                                        <div class="mt-2 text-muted"><?php echo e($nextClass['day']); ?> • <?php echo e($nextClass['date_display']); ?></div>

                                        <div class="mt-3 border rounded p-3 bg-light">
                                            <div class="small text-muted">Time Slot</div>
                                            <div class="fw-bold text-primary">
                                                <?php echo e($nextClass['start_display']); ?> - <?php echo e($nextClass['end_display']); ?>

                                            </div>
                                        </div>

                                        <div class="mt-3 border rounded p-3 bg-light">
                                            <div class="small text-muted">Classroom</div>
                                            <div class="fw-bold"><?php echo e($nextClass['room']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('partials.notifications-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html><?php /**PATH /Volumes/shared/capstone project/backups/emonitor 3rd phase copy/resources/views/reports.blade.php ENDPATH**/ ?>