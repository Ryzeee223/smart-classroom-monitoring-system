<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule - eMonitor</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
    body {
        overflow-x: hidden;
    }
</style>


<?php
    $startTimes = [
        "7:00:00", "7:30:00", "8:00:00", "8:30:00", "9:00:00", "9:30:00", "10:00:00", "10:30:00",
        "11:00:00", "11:30:00", "12:00:00", "12:30:00", "1:00:00", "1:30:00", "2:00:00", "2:30:00",
        "3:00:00", "3:30:00", "4:00:00", "4:30:00", "5:00:00"
    ];

    $endTimes = [
        "8:00:00","8:30:00", "9:00:00", "9:30:00", "10:00:00", "10:30:00", "11:00:00", "11:30:00", "12:00:00", "12:30:00", "1:00:00", "1:30:00", "2:00:00", "2:30:00", "3:00:00", "3:30:00",
        "4:00:00", "4:30:00", "5:00:00", "5:30:00", "6:00:00"
    ];
?>

<div class="app-shell">
    <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="app-shell__content d-flex justify-content-center">
        <div class="container mt-5">
            <div class="row align-items-start">
                
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h5>Add Schedule for Faculty</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo e(route('schedules.store')); ?>">
                                <?php echo csrf_field(); ?>


                                
                                    <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Program</label>
                                        <select class="form-select" name="program_id" required>
                                            <option value="">Select Program</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($program->id); ?>"><?php echo e($program->Program_abbr); ?></option>
                                               <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                                        </select>
                                    </div>
                                </div>
                                

                                <div class="row text-start">
                                    
                                    <div class="col-md-6 mb-3">
                                        <?php
                                            $latestSemyr = \App\Models\semyr::query()->latest('id')->first();
                                        ?>
                                        <label class="form-label">Semester</label>
                                        <select class="form-select" name="Semester" required disabled>
                                            <option value="<?php echo e($latestSemyr->semester ?? ''); ?>"><?php echo e($latestSemyr->semester ?? 'Current Semester'); ?></option>
                                        </select>
                                        <input type="hidden" name="Semester" value="<?php echo e($latestSemyr->semester ?? ''); ?>">
                                    </div>

                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">School Year</label>
                                        <select class="form-select" name="School_year" required disabled>
                                            <option value="<?php echo e($latestSemyr->school_year ?? ''); ?>"><?php echo e($latestSemyr->school_year ?? 'Current School Year'); ?></option>
                                        </select>
                                        <input type="hidden" name="School_year" value="<?php echo e($latestSemyr->school_year ?? ''); ?>">
                                    </div>
                                </div>

                                
                                <div class="row">
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Faculty</label>
                                        <select class="form-select" name="user_id" required>
                                            <option value="">Select Faculty</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $faculty_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($faculty->id); ?>">
                                                    <?php echo e($faculty->first_name); ?> <?php echo e($faculty->last_name); ?>

                                                </option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    </div>

                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Year Level</label>
                                        <select class="form-select" name="year_level" required>
                                            <option value="">Year</option>
                                            <option value="1">1st</option>
                                            <option value="2">2nd</option>
                                            <option value="3">3rd</option>
                                            <option value="4">4th</option>
                                        </select>
                                    </div>

                                        
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Section</label>
                                        <select class="form-select" name="section" required>
                                            <option value="">Section</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['A','B','C','D','E','F','G','H','I','J']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($sec); ?>"><?php echo e($sec); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="row mt-2">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Course</label>
                                        <select class="form-select" name="Course" required>
                                            <option value="">Select Course</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($courses->id); ?>">
                                                    <?php echo e($courses->course_name); ?> (<?php echo e($courses->course_code); ?>)
                                                </option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    </div>

                                        
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Start Time</label>
                                        <select class="form-select" name="Start_time" required>
                                            <option value="">Select Start Time</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $startTimes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $start): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($start); ?>"><?php echo e($start); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    </div>

                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">End Time</label>
                                        <select class="form-select" name="End_time" required>
                                            <option value="">Select End Time</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $endTimes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $end): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($end); ?>"><?php echo e($end); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="row mt-2">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Room</label>
                                        <select class="form-select" name="Room" required>
                                            <option value="">Room</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($room->id); ?>"><?php echo e($room->room_name); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    </div>

                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Day</label>
                                        <div class="d-flex flex-wrap gap-3 mt-1">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Mon','Tue','Wed','Thu','Fri']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <label class="d-flex align-items-center gap-1">
                                                    <input type="checkbox" name="Day[]" value="<?php echo e($day); ?>" class="form-check-input">
                                                    <span><?php echo e($day); ?></span>
                                                </label>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </div>
                                    </div>



                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Save this schedule?')">
                                        Add Schedule
                                    </button>
                                </div>

                                
                                <div id="scheduleConflictAlert" class="alert alert-danger mt-3 d-none" role="alert">
                                    Schedule conflict detected. Please choose another time or room.
                                </div>

                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const form = document.querySelector('form[action="<?php echo e(route('schedules.store')); ?>"]');
                                        if (!form) return;

                                        const alertEl = document.getElementById('scheduleConflictAlert');
                                        const apiUrl = '<?php echo e(route('schedules.bookingsystem')); ?>';

                                        function getSelectedDays() {
                                            return Array.from(form.querySelectorAll('input[name="Day[]"]:checked')).map(el => el.value);
                                        }

                                        async function checkConflictForDay(day, roomId, startTime, endTime) {
                                            const payload = {
                                                day: day,
                                                room_id: roomId,
                                                start_time: startTime,
                                                end_time: endTime
                                            };

                                            const res = await fetch(apiUrl, {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                                },
                                                body: JSON.stringify(payload)
                                            });

                                            if (!res.ok) {
                                                // Fail open: allow submission if the checker fails
                                                return false;
                                            }

                                            const data = await res.json();
                                            return !!data.conflict;
                                        }

                                        form.addEventListener('submit', async function (e) {
                                            alertEl.classList.add('d-none');

                                            const selectedDays = getSelectedDays();
                                            const roomId = form.querySelector('select[name="Room"]').value;
                                            const startTime = form.querySelector('select[name="Start_time"]').value;
                                            const endTime = form.querySelector('select[name="End_time"]').value;

                                            // If form is incomplete, let backend validation handle it
                                            if (!selectedDays.length || !roomId || !startTime || !endTime) return;

                                            e.preventDefault();

                                            for (const day of selectedDays) {
                                                const hasConflict = await checkConflictForDay(day, roomId, startTime, endTime);
                                                if (hasConflict) {
                                                    alertEl.classList.remove('d-none');
                                                    return;
                                                }
                                            }

                                            // No conflicts: submit
                                            form.submit();
                                        });
                                    });
                                </script>

                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Assigned Faculty Schedules</h5>
                            <span>Faculty schedules shown here and can be edited or delete</span>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: auto;">

                            <?php
                                $groupedSchedules = $schedules->groupBy(function ($s) {
                                    return $s->user_id ?? ($s->user->id ?? 'unknown');
                                });
                            ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groupedSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userSchedules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php
                                    $first = $userSchedules->first();
                                    $userName = trim(($first->user->first_name ?? 'N/A') . ' ' . ($first->user->last_name ?? ''));
                                    $collapseId = 'recentSchedules_' . ($first->id ?? $first->user_id ?? 'x');
                                ?>

                                <div class="border-bottom pb-3 mb-2 text-start">
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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $userSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <div class="p-2 border rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            
                                                            <strong><?php echo e($schedule->program?->Program_abbr ?? $schedule->Programs?->Program_abbr ?? 'N/A'); ?> <?php echo e($schedule->year_level ?? ''); ?> <?php echo e($schedule->section); ?></strong><br>
                                                            <strong><?php echo e($schedule->course->course_code ?? 'N/A'); ?></strong><br>
                                                            <small class="text-muted">
                                                                <?php echo e($schedule->day ?? ($schedule->Day ?? 'N/A')); ?> |
                                                                <?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?> |
                                                                <?php echo e($schedule->room_id ?? ($schedule->Room ?? 'N/A')); ?>

                                                            </small><br>

                                                            <small class="text-muted">

                                                                
                                                                | <?php echo e($schedule->Semester ?? 'N/A'); ?> <?php echo e($schedule->School_year ?? 'N/A'); ?>

                                                            </small>
                                                        </div>
                                                        <div class="text-end pt-1">
                                                            <button class="btn btn-sm btn-outline-primary mb-1 d-block w-100"
                                                                    type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal<?php echo e($schedule->id); ?>">
                                                                Edit
                                                            </button>

                                                            <form method="POST" action="<?php echo e(route('schedules.destroy', $schedule->id)); ?>" class="d-block w-100"
                                                                  onsubmit="return confirm('Delete this schedule?')">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">Delete</button>
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
                                                                
                                                                <div class="modal-body text-start">
                                                                    <div class="row">
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="form-label">Course</label>
                                                                            <input type="text" class="form-control" name="Course" value="<?php echo e($schedule->course_id); ?>">

                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="form-label">Program</label>
                                                                            <select class="form-select" name="program_id" required>
                                                                                <option value="">Select Program</option>
                                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                                                    <option value="<?php echo e($program->id); ?>" <?php echo e((isset($schedule->program_id) && (int)$schedule->program_id === (int)$program->id) ? 'selected' : ''); ?>>
                                                                                        <?php echo e($program->Program_abbr); ?>

                                                                                    </option>
                                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">Start Time</label>
                                                                            <select class="form-select" name="Start_time" required>
                                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $startTimes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $start): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                                                    <option value="<?php echo e($start); ?>" <?php echo e($schedule->Start_time == $start ? 'selected' : ''); ?>>
                                                                                        <?php echo e($start); ?>

                                                                                    </option>
                                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">End Time</label>
                                                                            <select class="form-select" name="End_time" required>
                                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $endTimes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $end): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                                                    <option value="<?php echo e($end); ?>" <?php echo e($schedule->End_time == $end ? 'selected' : ''); ?>>
                                                                                        <?php echo e($end); ?>

                                                                                    </option>
                                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <label class="form-label">Day</label>
                                        <div class="d-flex flex-wrap gap-3 mt-1">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['Monday','Tuesday','Wednesday','Thursday','Friday']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <label class="d-flex align-items-center gap-1">
                                                    <input type="checkbox" name="Day[]" value="<?php echo e($day); ?>" class="form-check-input">
                                                    <span><?php echo e($day); ?></span>
                                                </label>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label">Room</label>
                                                                            <input type="text" class="form-control" name="Room" value="<?php echo e($schedule->room_id); ?>">

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
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schedules->isEmpty()): ?>
                                <p class="text-muted text-center py-4">No schedules added yet.</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html><?php /**PATH /Volumes/shared/capstone project/backups/emonitor 3rd phase copy/resources/views/schedules.blade.php ENDPATH**/ ?>