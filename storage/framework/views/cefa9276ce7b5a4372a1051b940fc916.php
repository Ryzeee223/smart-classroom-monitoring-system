<div class="modal fade " id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true" style="">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationsModalLabel">Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
          

                <div class="d-flex align-items-center justify-content-between mb-2">
    <h6 class="mb-0 fw-bold">Requests</h6>
    <span class="badge bg-primary"><?php echo e(($req ?? collect())->count()); ?></span>
</div>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array((int)(session('user_role') ?? 0), [2, 3], true)): ?>
    <div id="leave-requests-modal-content">
        <p class="text-muted small mb-3">Click a name to view request details.</p>
        <div class="list-group">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($req ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requesterUserId => $requests): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $first = $requests->first();
                    $userObj = $first->user ?? null;
                    $requesterName = trim(($userObj->first_name ?? $first->first_name ?? '').' '.($userObj->last_name ?? $first->last_name ?? ''));
                    $roleMap = [2 => 'Dean', 3 => 'Assistant Dean', 4 => 'Faculty', 5 => 'Program Head'];
                    $requesterRoleCode = $userObj->role ?? $first->role ?? 0;
                    $requesterRole = $roleMap[(int)$requesterRoleCode] ?? 'Unknown';
                    $collapseId = 'modal-faculty-requests-' . $requesterUserId;
                ?>
                <button
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-3 py-2"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#<?php echo e($collapseId); ?>"
                    aria-expanded="false"
                    aria-controls="<?php echo e($collapseId); ?>"
                >
                    <span class="fw-bold small"><?php echo e(!empty($requesterName) ? $requesterName : 'Unknown Faculty'); ?></span>
                    <span class="small text-muted"><?php echo e($requesterRole); ?></span>
                    <span class="badge bg-primary rounded-pill"><?php echo e($requests->count()); ?></span>
                </button>

                <div id="<?php echo e($collapseId); ?>" class="collapse border border-dark-1">
                    <div class="p-3" style="border-top:1px solid rgba(0,0,0,.08)">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold small"><?php echo e($r->reason); ?></div>
                                    <div class="text-muted" style="font-size:12px;">
                                        <?php echo e(!empty($r->created_at) ? \Carbon\Carbon::parse($r->created_at)->format('Y-m-d') : '-'); ?>

                                    </div>
                                </div>
                                <div class="text-muted small">
                                    Reason: <?php echo e($r->letter); ?>

                                    <br>
                                    <div class="d-flex gap-2 mt-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($r->reason ?? '') === 'official business leave'): ?>
                                            <form action="<?php echo e(route('showReason', $r->id ?? 0)); ?>" method="GET">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-outline-success btn-sm">Accept</button>
                                            </form>
                                            <form action="<?php echo e(route('requests.decline', $r->id ?? 0)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Decline</button>
                                            </form>
                                        <?php elseif(($r->reason ?? '') === 'Sick leave'): ?>
                                            <form action="<?php echo e(route('requests.approve', $r->id ?? 0)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-outline-success btn-sm">Accept</button>
                                            </form>
                                            <form action="<?php echo e(route('requests.decline', $r->id ?? 0)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Decline</button>
                                            </form>
                                        <?php elseif(($r->reason ?? '') === 'Summer class'): ?>
                                            <a href="<?php echo e(route('schedules')); ?>?user_id=<?php echo e($r->user_id ?? ''); ?>" class="btn btn-outline-success btn-sm">Set Schedule</a>
                                            <form action="<?php echo e(route('requests.decline', $r->id ?? 0)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Decline</button>
                                            </form>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="text-center text-muted py-4">No pending requests for your college.</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="text-center text-muted py-4">Your requests will be reviewed by the Dean.</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="mt-4">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="mb-0 fw-bold">Recent Faculty Added</h6>
        <span class="badge bg-info"><?php echo e(count($recent_faculty ?? [])); ?></span>
    </div>

    <div class="list-group">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recent_faculty ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="list-group-item">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-1 small fw-bold"><?php echo e($faculty->first_name); ?> <?php echo e($faculty->last_name); ?></h6>
                        <p class="text-xs mb-0 text-muted"><?php echo e($faculty->email); ?></p>
                    </div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <div class="list-group-item text-center text-muted">No recent faculty added in your college.</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="setschedmodal" tabindex="-1" aria-labelledby="setschedmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setschedmodalLabel">Set Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <p>Set schedule for this faculty.</p>
                
                <form action="<?php echo e(route('schedules.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Faculty</label>
                        <input type="text" name="faculty_name" class="form-control" placeholder="Faculty name" value="">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Program</label>
                            <input type="text" name="program_id" class="form-control" placeholder="Program">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Course</label>
                            <input type="text" name="Course" class="form-control" placeholder="Course">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room</label>
                            <input type="text" name="Room" class="form-control" placeholder="Room">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Day</label>
                            <input type="text" name="Day" class="form-control" placeholder="e.g. Monday, Tuesday">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="Start_time" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Time</label>
                            <input type="time" name="End_time" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Volumes/shared/capstone project/backups/emonitor 3rd phase copy/resources/views/partials/notifications-modal.blade.php ENDPATH**/ ?>