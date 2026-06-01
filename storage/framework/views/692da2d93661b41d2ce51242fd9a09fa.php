
<div class="card mt-4">
    <div class="card-header">
        <h4 class="mb-0">School Year Settings</h4>
    </div>

    <div class="card-body">
        
        <form method="POST" action="#" onsubmit="return false;">
            <?php echo csrf_field(); ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">School Year</label>
                    <input
                        type="text"
                        name="school_year"
                        class="form-control"
                        placeholder="e.g. 2025-2026"
                        value=""
                    />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="">Select semester</option>
                        <option value="1">1st Semester</option>
                        <option value="2">2nd Semester</option>
                    </select>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary" disabled>
                    Save School Year
                </button>
                <span class="text-muted align-self-center">
                    
                </span>
            </div>
        </form>
    </div>
</div>

<?php /**PATH /Users/macbook/Documents/capstone project/backups/emonitor 3rd phase copy/resources/views/partials/school-year-settings.blade.php ENDPATH**/ ?>