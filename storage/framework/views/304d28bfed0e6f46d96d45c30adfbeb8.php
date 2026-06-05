<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <title>Edit Course</title>
</head>
<body>
    <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>
        .app-shell{display:flex;}
        .app-shell__content{flex:1; min-width:0; margin-left:260px;}
        @media (max-width: 767.98px){
            .app-shell__content{margin-left:0;}
        }
    </style>

    <div class="app-shell">
        <main class="app-shell__content container mt-4 mb-5 p-4">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title mb-0">Edit Course</h3>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('course.update', $course->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="mb-3">
                            <label for="course_code" class="form-label">Course Code</label>
                            <input type="text" class="form-control" id="course_code" name="course_code" value="<?php echo e(old('course_code', $course->course_code)); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="course_name" class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="course_name" name="course_name" value="<?php echo e(old('course_name', $course->course_name)); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description', $course->description)); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Course</button>
                        <a href="<?php echo e(route('course')); ?>" class="btn btn-outline-secondary ms-2">Back</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

<?php /**PATH /Users/macbook/Documents/capstone project/backups/emonitor 3rd phase copy/resources/views/courses/edit.blade.php ENDPATH**/ ?>