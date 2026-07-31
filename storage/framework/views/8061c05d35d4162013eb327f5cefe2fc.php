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
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($req->reason === 'Summer class'): ?>
    <!-- 1. Summer Class View -->
    <div class="card container-fluid bg-light">
        <div class="card-body text-center">
            <h1>Summer Class Approval</h1>
            <p>Please review the academic schedule details below.</p>
        </div>
    </div>

<?php elseif($req->reason === 'official business leave'): ?>
    <!-- 2. Official Business View -->
    <div class="card container-fluid border-primary">
        <div class="card-body text-center">
            <h1>Official Business Approval</h1>
            <p>Requires travel documents and authorization signatures.</p>
        </div>
    </div>

<?php elseif($req->reason === 'Sick leave'): ?>
    <!-- 3. Sick Leave View -->
    <div class="card container-fluid border-danger">
        <div class="card-body text-center">
            <h1>Sick Leave Approval</h1>
            <p>Please ensure a medical certificate is attached.</p>
        </div>
    </div>

<?php elseif($req->reason === 'others'): ?>
    <!-- 4. Others View -->
    <div class="card container-fluid">
        <div class="card-body text-center">
            <h1>General Approval</h1>
            <p>Reason: <?php echo e($req->custom_reason ?? 'No description provided'); ?></p>
        </div>
    </div>

<?php else: ?>
    <!-- 5. Fallback Default View (Optional) -->
    <div class="alert alert-warning text-center">
        Unknown request type.
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



</body>
</html><?php /**PATH /Volumes/shared/capstone project/backups/emonitor 3rd phase copy/resources/views/partials/approve.blade.php ENDPATH**/ ?>