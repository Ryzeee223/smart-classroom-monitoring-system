<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - eMonitor</title>
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <style>
            .app-shell__content{margin-left:260px;}
            @media (max-width: 767.98px){
                .app-shell__content{margin-left:0;}
            }
        </style>

        <!-- nav bar -->
        <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <main class="app-shell__content" role="main">
            <div class="container p-4" style="padding-top:16px;">
                
        <!-- Stats Cards -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
            <div class="col">
                <div class="card h-100 shadow border-0">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Rooms</h6>
                        <div class="display-4 fw-bold text-primary mb-0">4</div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 shadow border-0">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Occupied</h6>
                        <div class="display-4 fw-bold text-success mb-0">0</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 shadow border-0">

                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Faculty</h6>
                        
                        <div class="display-4 fw-bold text-info mb-0"><?php echo e($faculty_count ?? 0); ?></div>
                    </div>
                </div> 
            </div>

            <div class="col">
                <div class="card h-100 shadow border-0">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Pending Account</h6>
                        <div class="display-4 fw-bold text-warning mb-0"><?php echo e($pending_count ?? 0); ?></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Main Content Row -->
        <div class="row g-4">
            

            <!-- Live Classroom Status -->
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary bg-opacity-10">
                        <h5 class="mb-0 fw-bold text-dark">Live Classroom Status</h5>
                    </div>

                    
                    <div class="card-body p-0">
                        <p class="p-4 pb-3 text-muted small">Rooms update automatically as Faculty tap registered RFID Cards.</p>
                        <div class="row row-cols-1 row-cols-md-2 g-3 px-4 pb-4">
                            <div class="col">
                                <div class="card shadow-sm h-100 border-0">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div>
                                                <h6 class="mb-1 fw-bold small">Computer Lab</h6>
                                                <p class="mb-0 text-muted fs-6">CC101</p>
                                            </div>
                                            <span class="badge bg-success rounded-pill px-2 py-1 fs-6">Vacant</span>
                                        </div>
                                    </div>
                                    <div class="card-footer p-2 pt-1 bg-transparent border-0">
                                        <p class="mb-0 text-muted fs-6"><small class="fw-bold text-uppercase">Faculty:</small> None</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card shadow-sm h-100 border-0">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div>
                                                <h6 class="mb-1 fw-bold small">Computer Lab</h6>
                                                <p class="mb-0 text-muted fs-6">CC104</p>
                                            </div>
                                            <span class="badge bg-success rounded-pill px-2 py-1 fs-6">Vacant</span>
                                        </div>
                                    </div>
                                    <div class="card-footer p-2 pt-1 bg-transparent border-0">
                                        <p class="mb-0 text-muted fs-6"><small class="fw-bold text-uppercase">Faculty:</small> None</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card shadow-sm h-100 border-0">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div>
                                                <h6 class="mb-1 fw-bold small">Computer Lab</h6>
                                                <p class="mb-0 text-muted fs-6">CC205</p>
                                            </div>
                                            <span class="badge bg-success rounded-pill px-2 py-1 fs-6">Vacant</span>
                                        </div>
                                    </div>
                                    <div class="card-footer p-2 pt-1 bg-transparent border-0">
                                        <p class="mb-0 text-muted fs-6"><small class="fw-bold text-uppercase">Faculty:</small> None</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card shadow-sm h-100 border-0">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div>
                                                <h6 class="mb-1 fw-bold small">Computer Lab</h6>
                                                <p class="mb-0 text-muted fs-6">CC206</p>
                                            </div>
                                            <span class="badge bg-success rounded-pill px-2 py-1 fs-6">Vacant</span>
                                        </div>
                                    </div>
                                    <div class="card-footer p-2 pt-1 bg-transparent border-0">
                                        <p class="mb-0 text-muted fs-6"><small class="fw-bold text-uppercase">Faculty:</small> None</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">

                <!-- Leave Requests (Dean / Assistant Dean only) -->
                <?php
                    $viewerRole = (int) (session('user_role') ?? 0);
                ?>

                <?php if(in_array($viewerRole, [2, 3], true)): ?>
                    <div class="card shadow-lg mb-3">
                        <div class="card-header">

                            
                            <h5 class="mb-0 fw-bold small">Access Status / Leave Requests</h5>
                        </div>
                        <div class="card-body py-3">
                            <p class="card-text text-muted mb-3 fs-6">Click a faculty name to view their leave request details.</p>

                            <div class="list-group">
                                <?php $__empty_1 = true; $__currentLoopData = ($leave_requests_by_faculty ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facultyUserId => $reqs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $first = $reqs->first();
                                        $facultyName = trim(($first->first_name ?? '').' '.($first->last_name ?? ''));
                                        $collapseId = 'faculty-requests-' . $facultyUserId;
                                    ?>

                                    <button
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-3 py-2"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#<?php echo e($collapseId); ?>"
                                        aria-expanded="false"
                                        aria-controls="<?php echo e($collapseId); ?>"
                                    >
                                        <span class="fw-bold small"><?php echo e($facultyName ?: 'Unknown Faculty'); ?></span>
                                        <span class="badge bg-primary rounded-pill"><?php echo e($reqs->count()); ?></span>
                                    </button>

                                    <div id="<?php echo e($collapseId); ?>" class="collapse">
                                        <div class="p-3" style="border-top:1px solid rgba(0,0,0,.08)">
                                            <?php $__currentLoopData = $reqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <div class="fw-bold small"><?php echo e($r->letter); ?></div>
                                                        <div class="text-muted" style="font-size:12px;"><?php echo e(!empty($r->created_at) ? \Carbon\Carbon::parse($r->created_at)->format('Y-m-d') : '-'); ?></div>

                                                    </div>
                                                    <div class="text-muted small">Reason: <?php echo e($r->reason); ?></div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center text-muted py-4">
                                        No leave requests yet.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                    <?php if(in_array($viewerRole, [1, 2, 3], true)): ?>
                        
                <!-- Recent Faculty Assignments -->
                <div class="card shadow-lg">
                    <div class="card-header bg-info bg-opacity-10">
                        <h5 class="mb-0 fw-bold text-dark small">Recent Faculty Added</h5>
                    </div>

                    
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        <div class="list-group list-group-flush">
                            <?php $__empty_1 = true; $__currentLoopData = $recent_faculty ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a class="list-group-item list-group-item-action border-0 px-3 py-2" href="#">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-1 small fw-bold"><?php echo e($faculty->first_name); ?> <?php echo e($faculty->last_name); ?></h6>
                                            <p class="text-xs mb-0 text-muted"><?php echo e($faculty->email); ?></p>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="list-group-item text-center py-4 text-muted">
                                    No recent faculty added
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php endif; ?>
                <!-- Recent Logs -->
        <div class="row">

            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">Recent Logs</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Card Number</th>
                                        <th>Faculty Name</th>
                                        <th>Room</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Course</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                      
                                    <tr class="table-active">
                                        <td colspan="6" class="text-center py-4 text-muted">No recent activity. Check back later</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH /Users/macbook/Documents/capstone project/backups/emonitor 3rd phase copy/resources/views/dashboard.blade.php ENDPATH**/ ?>