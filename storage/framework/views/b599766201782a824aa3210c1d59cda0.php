<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $__env->make('partials.favicon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/bootstrap-vendor.js']); ?>
<title>RFInsiDe - Colleges</title>
</head>
<body>
    <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="app-shell">
        <main class="app-shell__content container mt-4 mb-5 p-4 gap-4">
            <div class="container-fluid dashboard-container px-4">
        <div class="row">
            <div class="col-12 col-lg-6 mb-4">
                <?php echo $__env->make('partials.feedback-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="card shadow">
                    <div class="card-header">
                        
                        <!-- create -->
                        <h3 class="card-title mb-0">Add New College</h3>
                    </div>
                    <div class="card-body">
<form method="POST" action="<?php echo e(route('college.store')); ?>"> 
                            <div class="mb-3">
<label for="abbreviation" class="form-label">College Abbreviation</label>
                                <input type="text" class="form-control" id="abbreviation" name="abbreviation" placeholder="e.g., CCIT, CCJE">
                            </div>
                            <div class="mb-3">
                                <label for="college_name" class="form-label">College Name</label>
                                <input type="text" class="form-control" id="college_name" name="college_name" placeholder="e.g., College of Communication and Information Technology">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the course"></textarea>
                            </div>
                            <?php echo csrf_field(); ?>

                            <button type="submit" class="btn btn-primary">Save College</button>

                            
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-4">
                <!-- list -->
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Existing College</h3>
                    </div>
                    <div class="card-body">
                        <p>List of Program currently in the system.</p>
                        <ul class="list-group list-group-flush">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $college ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo e($college->college_name); ?></strong> - <strong><?php echo e($college->abbreviation); ?></strong>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($college->description): ?><br><small><?php echo e($college->description); ?></small><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <a class="btn btn-sm btn-outline-secondary btn-edit" href="<?php echo e(route('college.edit', $college->id)); ?>" title="Edit" aria-label="Edit">✏️</a>
                                    <form method="POST" action="<?php echo e(route('college.archive', $college->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold" title="Archive" aria-label="Archive" onclick="return confirm('Archive this college?')">🗑</button>
                                    </form>
                                </div>
                            </li>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <li class="list-group-item text-muted">No Program yet.</li>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </main>
    </div>
    <?php echo $__env->make('partials.notifications-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH /Volumes/shared file/4th year/capstone project/RFInsiDe/resources/views/college.blade.php ENDPATH**/ ?>