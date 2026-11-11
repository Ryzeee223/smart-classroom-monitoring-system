<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $__env->make('partials.favicon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/bootstrap-vendor.js']); ?>
    <title>Edit Program</title>
</head>
<body>
    <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>
        .app-shell { display: flex; }
        .app-shell__content { flex: 1; min-width: 0; margin-left: 260px; }
        @media (max-width: 767.98px) {
            .app-shell__content { margin-left: 0; }
        }
    </style>

    <div class="app-shell">
        <main class="app-shell__content container mt-4 mb-5 p-4">
            <div class="card shadow">
                <div class="card-header">
                    <h1 class="h4 mb-0">Edit Program</h1>
                </div>
                <div class="card-body">
                    <?php echo $__env->make('partials.feedback-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <form method="POST" action="<?php echo e(route('programs.update', $program->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="mb-3">
                            <label for="program_abbr" class="form-label">Program Abbreviation</label>
                            <input type="text" class="form-control" id="program_abbr" name="program_abbr" value="<?php echo e(old('program_abbr', $program->program_abbr)); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="program_name" class="form-label">Program Name</label>
                            <input type="text" class="form-control" id="program_name" name="program_name" value="<?php echo e(old('program_name', $program->program_name)); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description', $program->description)); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Program</button>
                        <a href="<?php echo e(route('programs')); ?>" class="btn btn-outline-secondary ms-2">Back</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php /**PATH /Volumes/shared file/capstone project/backups/RFInsiDe/resources/views/programs/edit.blade.php ENDPATH**/ ?>