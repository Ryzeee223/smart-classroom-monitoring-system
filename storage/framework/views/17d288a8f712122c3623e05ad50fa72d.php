<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - eMonitor</title>
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
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

            <?php if($errors->any()): ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Could not save changes:</strong>
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('users.update', $user->id)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label form-label-sm">First Name</label>
                                <input type="text" class="form-control form-control-sm" name="first_name" value="<?php echo e(old('first_name', $user->first_name)); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label form-label-sm">Last Name</label>
                                <input type="text" class="form-control form-control-sm" name="last_name" value="<?php echo e(old('last_name', $user->last_name)); ?>" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label form-label-sm">College</label>
                                <select name="college_code" class="form-select form-select-sm" required>
                                    <option value="" <?php echo e(empty(old('college_code', $user->course)) ? 'selected' : ''); ?>>Select College</option>

                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $abbr = $c->abbreviation ?? '';
                                            $name = $c->college_name ?? '';
                                        ?>

                                        <option value="<?php echo e($abbr); ?>" <?php echo e((string)old('college_code', $user->course) === (string)$abbr ? 'selected' : ''); ?>>
                                            <?php echo e($name); ?><?php echo e($abbr ? ' (' . $abbr . ')' : ''); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php /**PATH /Users/macbook/Documents/capstone project/backups/emonitor 3rd phase copy/resources/views/users/edit.blade.php ENDPATH**/ ?>