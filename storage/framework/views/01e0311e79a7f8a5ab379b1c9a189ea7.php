<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<title>Faculty Schedule Reports</title>
    <style>
        body {
            background: #f5f7fb;
            overflow-x: hidden;
            overflow-y:hidden;
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

    <div class="container-fluid p-5 mt-3">
        <div class="row justify-content-end">
            <div class="col-xl-10 col-lg-12 col-md-12">

                <div class="card schedule-card shadow-sm border-1">
                    <div class="schedule-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">Faculty Schedule Overview</h5>
                            <small class="text-muted d-block"><?php echo e($todayLabel ?? now()->translatedFormat('l, F d, Y')); ?></small>
                            <small class="text-muted d-block">
                                <?php echo e($currentSemester ?? 'Current Semester'); ?> • <?php echo e($currentSchoolYear ?? 'Current School Year'); ?>

                            </small>

                        </div>
                            <div class="d-flex align-items-end gap-2 flex-wrap">
                                <form method="GET" action="<?php echo e(route('reports')); ?>" class="d-flex align-items-end gap-2">
                                    <div>
                                        <label for="date" class="form-label">Attendance Date</label>
                                        <select class="form-select" id="date" name="date" required>
                                            <option value="">Select attendance date</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $displayrep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($date); ?>" <?php if(request('date') == $date): echo 'selected'; endif; ?>>
                                                    <?php echo e(\Illuminate\Support\Carbon::parse($date)->format('F d, Y')); ?>

                                                </option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">View Attendance</button>
                                </form>

                                <form method="POST" action="<?php echo e(route('reports.generate')); ?>" class="d-flex align-items-end">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="date" value="<?php echo e(request('date')); ?>">
                                    <button type="submit" class="btn btn-success" <?php echo e(!request('date') ? 'disabled' : ''); ?>>Generate Excel</button>
                                </form>
                            </div>
                        </div>
                    </div>


                 <div class="card-body p-3 p-md-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Schedule</th>
                                    <th scope="col">Time In</th>
                                    <th scope="col">Time Out</th>
                                    <th scope="col">Faculty Name</th>
                                    <th scope="col">Course Code</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $facultySchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <tr>
                                        <td><?php echo e($schedule['start_display']); ?> - <?php echo e($schedule['end_display']); ?></td>
                                        <td><?php echo e($schedule['time_in'] ?? 'N/A'); ?></td>
                                        <td><?php echo e($schedule['time_out'] ?? 'N/A'); ?></td>
                                        <td><?php echo e($schedule['faculty']); ?></td>
                                        <td><?php echo e($schedule['course_code']); ?></td>
                                        <td>
                                            <span class="badge text-bg-secondary">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $schedule['attendance_status']))); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No attendance records found.</td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                        <br>
                    </div>
                  </div>
                </div>
            </div>
        </div>  
            </div>
    </div>
    <?php echo $__env->make('partials.notifications-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html><?php /**PATH D:\capstone project\backups\emonitor 3rd phase copy\resources\views\reports.blade.php ENDPATH**/ ?>