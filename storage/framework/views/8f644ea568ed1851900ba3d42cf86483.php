<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <title>RFInsiDe - Courses</title>
</head>
<body>
    <style>
        /* Ensure content doesn’t overlap the fixed sidebar (matches dashboard-admin/course patterns) */
        .app-shell { display: flex; min-height: 100vh; }
        .app-shell__content { flex: 1; min-width: 0; margin-left: 260px; }
        @media (max-width: 767.98px) {
            .app-shell__content { margin-left: 0; }
        }
    </style>

    <div class="app-shell">
        <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="app-shell__content container mt-4 mb-5 p-4">
            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Add Course</h5>
                            <form action="<?php echo e(route('course.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                <div class="mb-3">
                                    <?php
                                        $sessionRole = (int) (session('user_role') ?? 0);
                                        $currentUser = \App\Models\User::find(session('user_id'));
                                        $currentCollegeId = (int) ($currentUser?->college_id ?? 0);
                                        $currentCollegeName = $currentCollegeId
                                            ? (\App\Models\college::query()->where('id', $currentCollegeId)->value('college_name'))
                                            : null;
                                    ?>

                                    <input type="hidden" name="college_id" value="<?php echo e($currentCollegeId); ?>">

                                    <label for="college_id" class="form-label">College</label>
                                    <select id="college_id" class="form-select" disabled>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentCollegeId): ?>
                                            <option value="<?php echo e($currentCollegeId); ?>" selected>
                                                <?php echo e($currentCollegeName ?? ('Assigned to your college (ID: ' . $currentCollegeId . ')')); ?>

                                            </option>
                                        <?php else: ?>
                                            <option selected>No assigned college. Please contact Admin.</option>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <input type="text" name="course_code" placeholder="course Code" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <input type="text" name="course_name" placeholder="Course Name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" name="description" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">Add Course</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Course List</h5>

                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Desc</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $course; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <tr>
                                                <td><?php echo e($course->course_name ?? 'N/A'); ?></td>
                                                <td><?php echo e($course->course_code); ?></td>
                                                <td><?php echo e($course->course_name); ?></td>
                                                <td><?php echo e(Str::limit($course->description, 30)); ?></td>
                                                <td class="text-nowrap">
                                                    <a href="<?php echo e(route('course.edit', $course->id)); ?>" class="btn btn-sm btn-outline-primary fw-semibold">Edit</a>
                                                    <form method="POST" action="<?php echo e(route('course.destroy', $course->id)); ?>" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No course yet</td>
                                            </tr>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php echo $__env->make('partials.notifications-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</body>
</html>

<?php /**PATH D:\capstone project\backups\emonitor 3rd phase copy\resources\views/course.blade.php ENDPATH**/ ?>