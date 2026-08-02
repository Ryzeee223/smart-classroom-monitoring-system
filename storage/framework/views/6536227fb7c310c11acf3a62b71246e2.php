<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <title>Document</title>
</head>
<body>
    <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="container">
        <div class="row-md-3">
            
            <div class="card-body">
                <h4>Generate Semetral Reports</h4>
                <divcol-md-3>
                    <button>Generate</button>
                </div>
            </div>
        </div>
        <div class="col-md-9"></div>
    </div>
</body>
</html><?php /**PATH /Volumes/shared/capstone project/backups/emonitor 3rd phase copy/resources/views/reports.blade.php ENDPATH**/ ?>