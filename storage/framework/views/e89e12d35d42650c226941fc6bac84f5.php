
<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">Set School Year</h4>
    </div>

    <div class="card-body">
        
        <form method="POST" action="<?php echo e(route('settings.store_school_year')); ?>" onsubmit="return true;">
            <?php echo csrf_field(); ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">School Year</label>
                    <input type="text" name="school_year" class="form-control" placeholder="e.g., 2023-2024">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="">Select semester</option>
                        <option value="1st sem">1st Semester</option>
                        <option value="2nd sem">2nd Semester</option>
                        <option value="midyear">Mid-Year</option>
                    </select>
                </div>

            <div class="mt-3 d-flex gap-2">
                <?php
                    $alreadyExists = isset($semyr) && $semyr->count() > 0;
                ?>
                <button type="submit" class="btn btn-primary">
                    <?php echo e($alreadyExists ? 'Change School Year' : 'Save School Year'); ?>

                </button>

                <span class="text-muted align-self-center">
                    
                </span>
            </div>
        </form>
    </div>
</div>

<?php /**PATH D:\capstone project\backups\emonitor 3rd phase copy\resources\views/partials/school-year-settings.blade.php ENDPATH**/ ?>