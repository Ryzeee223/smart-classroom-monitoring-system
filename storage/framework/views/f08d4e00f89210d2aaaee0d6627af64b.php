<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule - eMonitor</title>
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
</head>
<body>
<style>
    .app-shell { display: flex; min-height: 100vh; }
    .app-shell__content { flex: 1; min-width: 0; margin-left: 260px; }
    @media (max-width: 767.98px) {
        .app-shell__content { margin-left: 0; }
    }
</style>

<div class="app-shell">
    <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="app-shell__content">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Add Schedule for Faculty</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo e(route('schedules.store')); ?>">
                                <?php echo csrf_field(); ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        
                                        <label class="form-label">Faculty</label>
                                        <select class="form-select" name="user_id" required>
                                            <option value="">Select Faculty</option>
                                            <?php $__currentLoopData = $faculty_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($faculty->id); ?>">
                                                    <?php echo e($faculty->first_name); ?> <?php echo e($faculty->last_name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Course</label>
                                        <select class="form-select" name="course" required>
                                            <option value="">Select Course</option>
                                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($course->course_name); ?>">
                                                    <?php echo e($course->course_name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Day</label>
                                        <select class="form-select" name="Day" required>
                                            <option value="">Day</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Year Level</label>
                                        <select class="form-select" name="year_level" required>
                                            <option value="">Year</option>
                                            <option value="1">1st</option>
                                            <option value="2">2nd</option>
                                            <option value="3">3rd</option>
                                            <option value="4">4th</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Section</label>
                                        <select class="form-select" name="section" required>
                                            <option value="">Section</option>
                                            <?php $__currentLoopData = ['A','B','C','D','E','F','G','H','I','J']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($sec); ?>"><?php echo e($sec); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Semester</label>
                                        <select class="form-select" name="Semester" required>
                                            <option value="">Sem</option>
                                            <option value="1st Sem">1st Sem</option>
                                            <option value="2nd Sem">2nd Sem</option>
                                            <option value="midyear">mid-year</option>
                                            <option value="summer">summer class</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Subject</label>
                                        <select class="form-select" name="Subject" required>
                                            <option value="">Select Subject</option>
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($subject->subject_name); ?>">
                                                    <?php echo e($subject->subject_name); ?> (<?php echo e($subject->subject_code); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Time</label>
                                        <?php
                                            $timeSlots = [
                                                '7:30-8:30','7:30-9:30','7:30-10-:30','7:30-11:30','7:30-12:30',
                                                '8:00-9:30','8:00-10:30','8:00-11:30','8:00-12:30',
                                                '8:30-9:30','8:30-10:30','8:30-11:30','8:30-12:30',
                                                '9:00-10-:30','9:00-11:30','9:00-12:30',
                                                '9:30-10-:30','9:30-11:30','9:30-12:30',
                                                '10:30-11:30','10:30-12:30','10:30-1:00','10:30-1:30',
                                                '1:00-2:00','1:00-2:30','1:00-3:00','1:00-3:30','1:00-4:00','1:00-4:30','1:00-5:00',
                                                '1:30-2:30','1:30-3:00',
                                                '2:00-3:00','2:00-3:30','2:00-4:00','2:00-4:30','2:00-5:00','2:00-5:30','2:00-6:00',
                                                '3:00-4:00','3:00-4:30','3:00-5:00','3:00-5:30','3:00-6:00',
                                                '3:30-4:30','3:30-5:00','3:30-5:30','3:30-6:00',
                                                '4:30-5:30','4:30-6:00',
                                                '5:00-6:00'
                                            ];
                                        ?>
                                        <select class="form-select" name="Time" required>
                                            <option value="">Select Time</option>
                                            <?php $__currentLoopData = $timeSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($slot); ?>"><?php echo e($slot); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Room</label>
                                        <select class="form-select" name="Room" required>
                                            <option value="">Room</option>
                                            <option value="CC101">CC101</option>
                                            <option value="CC104">CC104</option>
                                            <option value="CC205">CC205</option>
                                            <option value="CC206">CC206</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">School Year</label>
                                        <select class="form-select" name="School_year" required>
                                            <option value="2024-2025">2024-2025</option>
                                            <option value="2025-2026">2025-2026</option>
                                            <option value="2026-2027">2026-2027</option>
                                        </select>
                                    </div>
                                    
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Save this schedule?')">
                                        Add Schedule
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Schedules</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">

                            <?php
                                $groupedSchedules = $schedules->groupBy(function ($s) {
                                    return $s->user_id ?? ($s->user->id ?? 'unknown');
                                });
                            ?>

                            <?php $__currentLoopData = $groupedSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userSchedules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $first = $userSchedules->first();
                                    $userName = trim(($first->user->first_name ?? 'N/A') . ' ' . ($first->user->last_name ?? ''));
                                    $collapseId = 'recentSchedules_' . ($first->id ?? $first->user_id ?? 'x');
                                ?>

                                <div class="border-bottom pb-3 mb-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <button class="btn btn-link px-0 text-start" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#<?php echo e($collapseId); ?>"
                                                aria-expanded="false"
                                                aria-controls="<?php echo e($collapseId); ?>">
                                            <strong><?php echo e($userName); ?></strong>
                                        </button>
                                    </div>

                                    <div class="collapse mt-2" id="<?php echo e($collapseId); ?>">
                                        <div class="d-flex flex-column gap-2">
                                            <?php $__currentLoopData = $userSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="p-2 border rounded">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <strong><?php echo e($schedule->Subject); ?></strong><br>
                                                            <small class="text-muted"><?php echo e($schedule->Day); ?> | <?php echo e($schedule->Time); ?> | <?php echo e($schedule->Room); ?></small><br>
                                                            <small class="text-muted">
                                                                <?php echo e($schedule->course ?? 'N/A'); ?> <?php echo e($schedule->year_level ?? ''); ?> <?php echo e($schedule->section ?? ''); ?>

                                                                | <?php echo e($schedule->Semester ?? 'N/A'); ?> <?php echo e($schedule->School_year ?? 'N/A'); ?>

                                                            </small>
                                                        </div>
                                                        <div class="text-end">
                                                            <button class="btn btn-sm btn-outline-primary me-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal<?php echo e($schedule->id); ?>">
                                                                Edit
                                                            </button>

                                                            <form method="POST" action="<?php echo e(route('schedules.destroy', $schedule->id)); ?>" class="d-inline"
                                                                  onsubmit="return confirm('Delete this schedule?')">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="modal fade" id="editModal<?php echo e($schedule->id); ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Schedule</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <form method="POST" action="<?php echo e(route('schedules.update', $schedule->id)); ?>">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('PUT'); ?>

                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Subject</label>
                                                                            <input type="text" class="form-control" name="Subject" value="<?php echo e($schedule->Subject); ?>">
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Time</label>
                                                                            <?php
                                                                                $timeSlots = [
                                                                                    '7:30-8:30','7:30-9:30','7:30-10-:30','7:30-11:30','7:30-12:30',
                                                                                    '8:00-9:30','8:00-10:30','8:00-11:30','8:00-12:30',
                                                                                    '8:30-9:30','8:30-10:30','8:30-11:30','8:30-12:30',
                                                                                    '9:00-10-:30','9:00-11:30','9:00-12:30',
                                                                                    '9:30-10-:30','9:30-11:30','9:30-12:30',
                                                                                    '10:30-11:30','10:30-12:30','10:30-1:00','10:30-1:30',
                                                                                    '1:00-2:00','1:00-2:30','1:00-3:00','1:00-3:30','1:00-4:00','1:00-4:30','1:00-5:00',
                                                                                    '1:30-2:30','1:30-3:00',
                                                                                    '2:00-3:00','2:00-3:30','2:00-4:00','2:00-4:30','2:00-5:00','2:00-5:30','2:00-6:00',
                                                                                    '3:00-4:00','3:00-4:30','3:00-5:00','3:00-5:30','3:00-6:00',
                                                                                    '3:30-4:30','3:30-5:00','3:30-5:30','3:30-6:00',
                                                                                    '4:30-5:30','4:30-6:00',
                                                                                    '5:00-6:00'
                                                                                ];
                                                                            ?>
                                                                            <select class="form-select" name="Time" required>
                                                                                <?php $__currentLoopData = $timeSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <option value="<?php echo e($slot); ?>" <?php echo e($schedule->Time == $slot ? 'selected' : ''); ?>>
                                                                                        <?php echo e($slot); ?>

                                                                                    </option>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-2">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Day</label>
                                                                            <input type="text" class="form-control" name="Day" value="<?php echo e($schedule->Day); ?>">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Room</label>
                                                                            <input type="text" class="form-control" name="Room" value="<?php echo e($schedule->Room); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if($schedules->isEmpty()): ?>
                                <p class="text-muted text-center py-4">No schedules added yet.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>

<?php /**PATH /Users/macbook/Documents/capstone project/backups/emonitor 3rd phase copy/resources/views/schedules.blade.php ENDPATH**/ ?>