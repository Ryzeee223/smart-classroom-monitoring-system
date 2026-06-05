<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <title>eMonitor - Programs</title>
</head>
<body>
    <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>
        /* match dashboard-admin layout so sidebar occupies its space */
        .app-shell{display:flex;}
        .app-shell__content{flex:1; min-width:0; margin-left:260px;}
        @media (max-width: 767.98px){
            .app-shell__content{margin-left:0;}
        }
    </style>

    <div class="app-shell">
    <main class="app-shell__content container mt-4 mb-5 p-4 gap-4">
        <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        
                        <!-- create -->
                        <h3 class="card-title mb-0">Add New Program</h3>
                    </div>
                    <div class="card-body">
<form method="POST" action="<?php echo e(route('course.store')); ?>"> 
                            <div class="mb-3">
                                <label for="course_code" class="form-label">Program Code</label>
                                <input type="text" class="form-control" id="course_code" name="course_code" placeholder="e.g., BSCS, BSED">
                            </div>
                            <div class="mb-3">
                                <label for="course_name" class="form-label">Program Name</label>
                                <input type="text" class="form-control" id="course_name" name="course_name" placeholder="e.g., Bachelor of Science in Computer Science">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the course"></textarea>
                            </div>
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-primary">Save Program</button>
                            <?php if(session('success')): ?>
                                <div class="alert alert-success mt-3"><?php echo e(session('success')); ?></div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-4">
                <!-- list -->
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Existing program</h3>
                    </div>
                    <div class="card-body">
                        <p>List of program currently in the system.</p>
                        <ul class="list-group list-group-flush">
<?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo e($course->course_code); ?></strong> - <strong><?php echo e($course->course_name); ?></strong>
                                    <?php if($course->description): ?><br><small><?php echo e($course->description); ?></small><?php endif; ?>
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('course.edit', $course->id)); ?>">Edit</a>
                                    <form method="POST" action="<?php echo e(route('course.destroy', $course->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this course?')">Delete</button>
                                    </form>
                                </div>
                            </li>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="list-group-item text-muted">No program yet.</li>
<?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<?php /**PATH /Users/macbook/Documents/capstone project/backups/emonitor 3rd phase copy/resources/views/course.blade.php ENDPATH**/ ?>