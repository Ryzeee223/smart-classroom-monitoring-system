<?php
    $records = isset($requests)
        ? collect($requests)
        : (isset($RequestRecord) ? collect([$RequestRecord]) : collect());
?>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Request Approval</h4>
        <span class="badge bg-primary"><?php echo e($records->count()); ?></span>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requestRecord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <?php
            $requester = $requestRecord->user ?? null;
            $requesterName = trim(($requester->first_name ?? $requestRecord->first_name ?? '') . ' ' . ($requester->last_name ?? $requestRecord->last_name ?? ''));
        ?>

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="card-title mb-1"><?php echo e($requesterName ?: 'Unknown requester'); ?></h5>
                        <div class="text-muted small"><?php echo e($requestRecord->reason); ?></div>
                    </div>
                    <span class="badge bg-warning text-dark"><?php echo e(ucfirst($requestRecord->status ?? 'pending')); ?></span>
                </div>

                <p class="mb-3"><?php echo e($requestRecord->letter); ?></p>

                <div class="d-flex gap-2">
                    <form action="<?php echo e(route('requests.approve', $requestRecord->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="<?php echo e(route('requests.decline', $requestRecord->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm">Decline</button>
                    </form>
                </div>
            </div>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <div class="alert alert-secondary mb-0">No requests found.</div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /Volumes/shared/capstone project/backups/emonitor 3rd phase copy/resources/views/partials/approve.blade.php ENDPATH**/ ?>