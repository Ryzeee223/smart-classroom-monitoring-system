<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $__env->make('partials.favicon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - RFInsiDe</title>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/bootstrap-vendor.js']); ?>
    <link rel="stylesheet" href="bootstrap-icons-1.10.5/bootstrap-icons.css">
    
<style>
        
body {
    background:#f5f7fb;
    overflow-x:hidden;
    overflow-y:auto;
}
.dashboard-container {
    padding-top:16px;
    padding-bottom:32px;
}
.dashboard-container .card {
    border:0;
    border-radius:10px;
}
.dashboard-container .card-header {
    border-bottom:1px solid rgba(0,0,0,.06);
}
.dashboard-container .stats-card {
    min-height:132px;
}
        .account-row .col {
            padding: 0.25rem 0.5rem;
        }
        .users-table-scroll {
            max-height: 65vh;
            overflow: auto;
        }
        .users-table-scroll thead {
            position: sticky;
            top: 0;
            z-index: 1;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggles
            ['lead', 'fac'].forEach(suffix => {
                const toggle = document.getElementById('togglePassword-' + suffix);
                const password = document.getElementById('password-' + suffix);
                if (toggle && password) {
                    toggle.addEventListener('click', function () {
                        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                        password.setAttribute('type', type);
                        this.querySelector('i').classList.toggle('bi-eye');
                        this.querySelector('i').classList.toggle('bi-eye-slash');
                    });
                }
            });
        });
    </script>
</head>


    <main role="main" class="page-content">
<div class="container-fluid dashboard-container px-4" >
    <div class="d-none d-md-block app-sidebar">
        <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <!-- content -->
    <div class="">

    <div class="flex-grow-1" style="min-width: 0;">

        <!-- nav bar (mobile only) -->
        <div class="d-md-none mb-3">
            <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- main content -->
        <main class="container py-2 shadow-sm bg-light rounded-3">

            <h1 class="mb-4">Users Management</h1>


         <?php echo $__env->make('partials.feedback-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Add Users -->

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
        <div class="card p-3 shadow-sm bg-white border-1" >

            <form action="<?php echo e(route('users.store')); ?>" method="POST">
                 <?php echo csrf_field(); ?>
                <div class="row g-3">
                    
                <?php
                    $sessionRole = (int) (session('user_role') ?? 0);
                    $currentUser = \App\Models\User::find(session('user_id'));
                    $currentCollegeId = (int) ($currentUser?->college_id ?? 0);
                    $currentCollege = $currentCollegeId ? \App\Models\college::find($currentCollegeId) : null;
                    $currentCollegeName = $currentCollege?->college_name ?? '';
                ?>

                <div class="mt-2">
                    

                    <label class="form-label form-label-sm">College</label>

                    <select name="college_id" id="collegeSelect" class="form-select form-select-sm" <?php echo e(in_array($sessionRole, [2, 3], true) ? 'disabled' : ''); ?>>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($sessionRole, [2, 3], true)): ?>
                            <option value="<?php echo e($currentCollegeId); ?>" selected>
                                <?php echo e($currentCollegeName ?: 'Assigned college'); ?>

                            </option>
                        <?php else: ?>
                            <option value="" selected>Select College</option>
                            <?php $college = $college ?? collect(); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <?php
                                    $collegeId = (int) ($college->id ?? 0);
                                    // Hide the first-created college (id=1) from all user assignment choices.
                                    if ($collegeId === 1) {
                                        continue;
                                    }
                                    $collegeName = $college->college_name ?? $collegeId;
                                    $collegeCode = $college->abbreviation ?? '';
                                    $label = $collegeCode ? $collegeCode . ' - ' . $collegeName : $collegeName;
                                ?>
                                <option value="<?php echo e($collegeId); ?>"><?php echo e($label); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>

                    
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($sessionRole, [2, 3], true)): ?>
                        <input type="hidden" name="college_id" value="<?php echo e($currentCollegeId); ?>">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>
                
                     <div class="mt-3">
                    <label class="form-label form-label-sm" for="newUserRfidLabel">Assign RFID</label>
                    <input type="hidden" name="RFID_code" id="newUserRfidInput" value="<?php echo e(old('RFID_code')); ?>">
                    <div class="input-group">
                        <span class="form-control form-control-sm bg-light font-monospace" id="newUserRfidLabel" aria-live="polite">
                            <?php echo e(old('RFID_code') ?: 'Tap a card on the RFID reader to assign it'); ?>

                        </span>
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="clearNewUserRfid">Clear</button>
                    </div>
                </div>
                
                    <div class="col-md">

                        
                        <label class="form-label form-label-sm">First Name</label>
                        <input type="text" class="form-control form-control-sm" name="first_name" id="first_name-lead" placeholder="Enter first name">
                    </div>
                    <div class="col-md">
                        <label class="form-label form-label-sm">Middle Initial</label>
                        <input type="text" class="form-control form-control-sm" name="middle_name" id="middle_name-lead" placeholder="Enter middle initial">
                    </div>
                    <div class="form-label form-label-sm">Last Name
                    <input type="text" class="form-control form control-sm" name="last_name" id="last_name-lead" placeholder="Enter last name">
                </div>
            </div>

            
<div class="row g-2 mt-2 d-box">

<div class="col-md-6 justify-content-center">
<label for="eduAttain" class="form-label">Educational Attainment</label>
<input type="text" class="form-control form-control-sm" placeholder="Enter Educational Attainment">
</div>
<div class="col-md-6 mt-2">
    <label for="acadRank" class="form-label">Academic Rank</label>
<input type="text" class="form-control form-control-sm" placeholder="Enter Academic Rank (e.g. Instructor I)">
</div>

</div>

                
                      <div class="row g-2 mt-2">
                    <div class="col-md">
                        <label class="form-label form-label-sm">Role</label>
                        <select class="form-select form-select-sm" name="role" id="role-lead">
                            <option selected>Select role</option>
                            <?php
                                
                                $myRole = (int) (session('user_role') ?? 0);
                            ?>

                                
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($myRole === 1): ?>
                                
                                <option selected value="2">Dean</option>
                                
                                
                                
                            <?php elseif($myRole === 2): ?>
                                
                                <option value="3">Assistant Dean</option>
                                <option value="4">Faculty</option>
                                <option value="5">Program Head</option>
                            <?php elseif($myRole === 3): ?>
                                
                                <option value="4">Faculty</option>
                                <option value="5">Program Head</option>
                            <?php else: ?>
                                
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                
                </div>



                <div class="row g-2 mt-2">
                    <div class="col-md">
                        
                        <label class="form-label form-label-sm">Employee ID</label>
                        <input type="text" class="form-control form-control-sm" name="employee_ID" id="employee_ID-lead" placeholder="Enter employee ID">
                    </div>

                    
                    <div class="col-md">
                        <label class="form-label form-label-sm">Email</label>
                        <input type="email" class="form-control form-control-sm" name="email" id="email-lead" placeholder="Enter email">
                    </div>
                </div>
                

                <div class="mt-2">
                    <label class="form-label form-label-sm">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-sm" name="password" id="password-lead" placeholder="Enter password">
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="togglePassword-lead">
                            
                        </button>
                    </div>
                </div>
                <div class="row g-2 mt-2">
                   

                    
                </div>
                <div class="col-md d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100 h-100">Create User</button>
                    </div>
               
                    <div class="row g-2 mt-2" id="adminCourseRow" style="display:none;">
                    
                </div>

                <script>
                    (function() {
                        const roleSelect = document.getElementById('role-lead');
                        const adminCourseRow = document.getElementById('adminCourseRow');
                        const toggleAdminCourse = () => {
                            if (!roleSelect || !adminCourseRow) return;
                            adminCourseRow.style.display = (roleSelect.value === '1') ? 'block' : 'none';
                        };
                        if (roleSelect) roleSelect.addEventListener('change', toggleAdminCourse);
                        toggleAdminCourse();

                        
                        adminCourseRow.style.display = (roleSelect.value === '1') ? 'block' : 'none';
                    })();

                    document.addEventListener('DOMContentLoaded', function () {
                        const rfidInput = document.getElementById('newUserRfidInput');
                        const rfidLabel = document.getElementById('newUserRfidLabel');
                        const clearButton = document.getElementById('clearNewUserRfid');
                        if (!rfidInput || !rfidLabel || !clearButton) return;

                        clearButton.addEventListener('click', function () {
                            rfidInput.value = '';
                            rfidLabel.textContent = 'Tap a card on the RFID reader to assign it';
                        });

                        async function readScannedRfid() {
                            try {
                                const response = await fetch('/api/check-latest-assignment', { cache: 'no-store' });
                                if (!response.ok) throw new Error(`RFID scan check failed: ${response.status}`);
                                const data = await response.json();

                                if (data.uid && data.uid !== 'N/A') {
                                    rfidInput.value = data.uid;
                                    rfidLabel.textContent = data.uid;
                                }
                            } catch (error) {
                                console.error('Unable to read the RFID scanner:', error);
                                if (!rfidInput.value) rfidLabel.textContent = 'Unable to read scanner';
                            }
                        }

                        readScannedRfid();
                        window.setInterval(readScannedRfid, 2000);
                    });
                </script>
            </form>
        </div>
        </div>
        <div class="col-lg-6">
        <div class="card p-3 shadow-sm bg-white border-1 h-100">
            <h5 class="mb-3 d-flex justify-content-between align-items-center">
                User Accounts
               
            </h5>
            <div class="table-responsive users-table-scroll mb-0">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>RFID UID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $all_users = collect();
                            $all_users = $all_users->merge($account_users ?? []);
                        ?>

<?php
                            $sessionRole = (int) (session('user_role') ?? 0);
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $all_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $uRole = (int) ($user->role ?? 0);
                                $canView = (
                                    $sessionRole === 1
                                        ? $uRole === 2
                                        : ($sessionRole === 2 ? in_array($uRole, [3,4,5], true) : in_array($uRole, [3,4,5], true))
                                );
                            ?>

                            <?php if(!$canView) continue; ?>

                            <tr>
                                <td><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($user->role):
                                        case (1): ?> Admin <?php break; ?>
                                        <?php case (2): ?> Dean <?php break; ?>
                                        <?php case (3): ?> Assistant Dean <?php break; ?>
                                        <?php case (4): ?> Faculty <?php break; ?>
                                        <?php case (5): ?> Program Head <?php break; ?>
                                        <?php default: ?> Unknown
                                    <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $accountStatus = strtolower((string) ($user->acc_status ?? ''));
                                    ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($accountStatus === 'present'): ?>
                                        <span class="badge bg-success"><?php echo e($user->acc_status); ?></span>
                                    <?php elseif($accountStatus === 'absent'): ?>
                                        <span class="badge bg-danger"><?php echo e($user->acc_status); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e($user->acc_status ?? 'N/A'); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->RFID_code): ?>
                                        <span class="badge bg-success">Assigned</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Not Assigned</span>   
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" title="Edit"
                                                data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo e($user->id); ?>">
                                            ✏️
                                        </button>

                                        <form method="POST" action="<?php echo e(route('users.archive', $user->id)); ?>" onsubmit="return confirm('Archive this user?')" class="m-0">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Archive">🗑</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No User accounts</td>
                        </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $all_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php
                    $uRole = (int) ($user->role ?? 0);
                    $canView = $sessionRole === 1
                        ? $uRole === 2
                        : in_array($uRole, [3, 4, 5], true);
                ?>
                <?php if(!$canView) continue; ?>

                <div class="modal fade" id="editUserModal<?php echo e($user->id); ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?php echo e($user->id); ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <form method="POST" action="<?php echo e(route('users.update', $user->id)); ?>" class="modal-content">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel<?php echo e($user->id); ?>">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="first_name_<?php echo e($user->id); ?>" class="form-label">First Name</label>
                                        <input id="first_name_<?php echo e($user->id); ?>" type="text" class="form-control" name="first_name" value="<?php echo e($user->first_name); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="middle_name_<?php echo e($user->id); ?>" class="form-label">Middle Name</label>
                                        <input id="middle_name_<?php echo e($user->id); ?>" type="text" class="form-control" name="middle_name" value="<?php echo e($user->middle_name); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name_<?php echo e($user->id); ?>" class="form-label">Last Name</label>
                                        <input id="last_name_<?php echo e($user->id); ?>" type="text" class="form-control" name="last_name" value="<?php echo e($user->last_name); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="employee_ID_<?php echo e($user->id); ?>" class="form-label">Employee ID</label>
                                        <input id="employee_ID_<?php echo e($user->id); ?>" type="text" class="form-control" name="employee_ID" value="<?php echo e($user->employee_ID); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="role_<?php echo e($user->id); ?>" class="form-label">Role</label>
                                        <select id="role_<?php echo e($user->id); ?>" name="role" class="form-select" required>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [2 => 'Dean', 3 => 'Assistant Dean', 4 => 'Faculty', 5 => 'Program Head']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleId => $roleName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <option value="<?php echo e($roleId); ?>" <?php echo e((int) $user->role === $roleId ? 'selected' : ''); ?>><?php echo e($roleName); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="acc_status_<?php echo e($user->id); ?>" class="form-label">Status</label>
                                        <select id="acc_status_<?php echo e($user->id); ?>" name="acc_status" class="form-select">
                                            <option value="Present" <?php echo e($user->acc_status === 'Present' ? 'selected' : ''); ?>>Present</option>
                                            <option value="Absent" <?php echo e($user->acc_status === 'Absent' ? 'selected' : ''); ?>>Absent</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="Academic_rank_<?php echo e($user->id); ?>" class="form-label">Academic Rank</label>
                                        <input id="Academic_rank_<?php echo e($user->id); ?>" type="text" class="form-control" name="Academic_rank" value="<?php echo e($user->Academic_rank); ?>">
                                    </div>
                                    <div class="col-md-8">
                                        <label for="Advisory_class_<?php echo e($user->id); ?>" class="form-label">Advisory Class</label>
                                        <input id="Advisory_class_<?php echo e($user->id); ?>" type="text" class="form-control" name="Advisory_class" value="<?php echo e($user->Advisory_class); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="class_count_<?php echo e($user->id); ?>" class="form-label">Advisory Count</label>
                                        <input id="class_count_<?php echo e($user->id); ?>" type="number" class="form-control" name="class_count" value="<?php echo e($user->class_count); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
        </div>
    </div>
        </main>
    </div>
</div>
<?php echo $__env->make('partials.notifications-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</main>

</html>
<?php /**PATH /Volumes/shared file/capstone project/backups/RFInsiDe/resources/views/users.blade.php ENDPATH**/ ?>