<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - RFINSIDE</title>
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<style>
    .app-sidebar{position:fixed; top:0; left:0; width:260px; height:100vh;}
    .app-shell{display:flex; min-height:100vh;}
    .app-shell__content{flex:1; min-width:0; margin-left:260px;}
    @media (max-width: 767.98px){
        .app-shell__content{margin-left:0;}
    }

    .edit-page { width: 100%; max-width: 900px; }
</style>

<?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="app-shell">
    <div class="app-shell__content">
        <main class="container edit-page py-4">
            <h1 class="mb-4">Edit User</h1>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Could not save changes:</strong>
                    <ul class="mb-0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <li><?php echo e($error); ?></li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="POST" action="<?php echo e(route('users.update', $user->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label form-label-sm">First Name</label>
                                <input type="text" class="form-control form-control-sm" name="first_name" value="<?php echo e(old('first_name', $user->first_name)); ?>" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label form-label-sm">Middle Name</label>
                                <input type="text" class="form-control form-control-sm" name="middle_name" value="<?php echo e(old('middle_name', $user->middle_name)); ?>">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label form-label-sm">Last Name</label>
                                <input type="text" class="form-control form-control-sm" name="last_name" value="<?php echo e(old('last_name', $user->last_name)); ?>" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label form-label-sm">Role</label>
                                <select name="role" class="form-select form-select-sm" required>
                                    <?php
                                        $roleLabels = [
                                            2 => 'Dean',
                                            3 => 'Assistant Dean',
                                            4 => 'Faculty',
                                            5 => 'Program Head',
                                        ];
                                    ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $roleLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleId => $roleName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <option value="<?php echo e($roleId); ?>" <?php echo e((string)old('role', $user->role) === (string)$roleId ? 'selected' : ''); ?>>
                                            <?php echo e($roleName); ?>

                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>

                           <div class="col-md-12">
                            <label class="form-label form-label-sm">Status</label>
                            <select name="acc_status" class="form-select form-select-sm">
                                <option value="Present" <?php echo e(old('acc_status', $user->acc_status) === 'Present' ? 'selected' : ''); ?>>Present</option>
                                <option value="Absent" <?php echo e(old('acc_status', $user->acc_status) === 'Absent' ? 'selected' : ''); ?>>Absent</option>
                            </select>
                           </div>

                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary btn-sm">Back</a>
                            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>
</body>
</html>

<?php /**PATH D:\capstone project\backups\emonitor 3rd phase copy\resources\views\users\edit.blade.php ENDPATH**/ ?>