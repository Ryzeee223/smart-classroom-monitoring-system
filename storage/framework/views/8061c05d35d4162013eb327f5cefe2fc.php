<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Approval-emonitor</title>
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
</head>
<body>

    <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- 1. Request Approval View -->
    <div class="container-fluid py-4" style="margin-left: 260px;">
        <div class="card bg-light mb-4">
            <div class="card-body text-center">
                <h1>Summer Class Approval</h1>
                <p>Please review the academic schedule details below.</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Academic Schedule Details</h5>
                <p class="card-text"><strong>Reason:</strong> <?php echo e($RequestRecord->reason ?? 'N/A'); ?></p>
                <p class="card-text"><strong>Details:</strong> <?php echo e($RequestRecord->letter ?? 'N/A'); ?></p>
                <p class="card-text"><strong>Status:</strong> <?php echo e($RequestRecord->status ?? 'N/A'); ?></p>
                <p class="card-text"><strong>Date Submitted:</strong> <?php echo e(!empty($RequestRecord->created_at) ? \Carbon\Carbon::parse($RequestRecord->created_at)->format('Y-m-d') : 'N/A'); ?></p>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($RequestRecord)): ?>
            <div class="text-center mb-4">
                <form action="<?php echo e(route('requests.approve', $RequestRecord->id)); ?>" method="POST" style="display: inline-block;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
                <form action="<?php echo e(route('requests.decline', $RequestRecord->id)); ?>" method="POST" style="display: inline-block;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger">Decline</button>
                </form>
                <a href="<?php echo e(route('schedules', ['user_id' => $RequestRecord->user_id ?? ''])); ?>" class="btn btn-primary" style="display: inline-block;">
                    Set Schedule for this Faculty
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- 2. Pending Requests -->
        <div class="mb-4">
            <h5>Pending Requests</h5>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($pending_requests ?? []) > 0): ?>
                <div class="accordion" id="pendingRequestsAccordion">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pending_requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="accordion-item">
                            <div class="accordion-header" id="heading<?php echo e($r->id); ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($r->id); ?>" aria-expanded="false" aria-controls="collapse<?php echo e($r->id); ?>">
                                    Request ID: <?php echo e($r->id); ?> - Reason: <?php echo e($r->reason); ?>

                                </button>
                            </div>
                            <div id="collapse<?php echo e($r->id); ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo e($r->id); ?>" data-bs-parent="#pendingRequestsAccordion">
                                <div class="accordion-body">
                                    <p><strong>Details:</strong> <?php echo e($r->letter); ?></p>
                                    <p><strong>Status:</strong> <?php echo e($r->status); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="alert alert-info text-center">No pending requests.</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">No pending requests.</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

</body>
</html>

<?php /**PATH /Volumes/shared/capstone project/backups/emonitor 3rd phase copy/resources/views/partials/approve.blade.php ENDPATH**/ ?>