<!-- Notifications Modal -->
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
                        <h6 class="mb-0 fw-bold">Leave Requests</h6>
                        <span class="badge bg-primary"><?php echo e(($leave_requests_by_faculty ?? collect())->count()); ?></span>
                    </div>

                    <?php if(in_array((int)(session('user_role') ?? 0), [2,3], true)): ?>
                        <div id="leave-requests-modal-content">
                            <p class="text-muted small mb-3">Click a faculty name to view leave request details.</p>

                            <div class="list-group">
                                <?php $__empty_1 = true; $__currentLoopData = ($leave_requests_by_faculty ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facultyUserId => $reqs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $first = $reqs->first();
                                        $facultyName = trim(($first->first_name ?? '').' '.($first->last_name ?? ''));
                                        $collapseId = 'modal-faculty-requests-' . $facultyUserId;
                                    ?>

                                    <button
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-3 py-2"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#<?php echo e($collapseId); ?>"
                                        aria-expanded="false"
                                        aria-controls="<?php echo e($collapseId); ?>"
                                    >
                                        <span class="fw-bold small"><?php echo e($facultyName ?: 'Unknown Faculty'); ?></span>
                                        <span class="badge bg-primary rounded-pill"><?php echo e($reqs->count()); ?></span>
                                    </button>

                                    <div id="<?php echo e($collapseId); ?>" class="collapse">
                                        <div class="p-3" style="border-top:1px solid rgba(0,0,0,.08)">
                                            <?php $__currentLoopData = $reqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <div class="fw-bold small"><?php echo e($r->letter); ?></div>
                                                        <div class="text-muted" style="font-size:12px;">
                                                            <?php echo e(!empty($r->created_at) ? \Carbon\Carbon::parse($r->created_at)->format('Y-m-d') : '-'); ?>

                                                        </div>
                                                    </div>
                                                    <div class="text-muted small">Reason: <?php echo e($r->reason); ?></div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center text-muted py-4">No leave requests yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        no request has not been approved yet.
                    </div>
                            
                        </div>
                    <?php endif; ?>
                </div>

                
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0 fw-bold">Recent Faculty Added</h6>
                        <span class="badge bg-info"><?php echo e(count($recent_faculty ?? [])); ?></span>
                    </div>

                    <div class="list-group">
                        <?php $__empty_1 = true; $__currentLoopData = $recent_faculty ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-1 small fw-bold"><?php echo e($faculty->first_name); ?> <?php echo e($faculty->last_name); ?></h6>
                                        <p class="text-xs mb-0 text-muted"><?php echo e($faculty->email); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="list-group-item text-center text-muted">No recent faculty added.</div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php /**PATH /Users/macbook/Documents/capstone project/backups/emonitor 3rd phase copy/resources/views/partials/notifications-modal.blade.php ENDPATH**/ ?>