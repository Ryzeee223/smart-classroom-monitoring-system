<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $__env->make('partials.favicon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - RFInsiDe</title>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/bootstrap-vendor.js']); ?>
    
</head>
<body>

<style>
   body{
    overflow-x: hidden;
    background: #f4f7fb;
    color: #1f2937;
    transition: background 0.25s ease, color 0.25s ease;
   }

   body[data-theme="dark"] {
    background: #0b1220;
    color: #edf2f7;
   }

   body[data-theme="dark"] .card,
   body[data-theme="dark"] .card-body,
   body[data-theme="dark"] .card-header,
   body[data-theme="dark"] .form-control,
   body[data-theme="dark"] .form-select,
   body[data-theme="dark"] .table,
   body[data-theme="dark"] .modal-content {
    background: #111827;
    color: #edf2f7;
    border-color: rgba(148, 163, 184, 0.22);
   }

   body[data-theme="dark"] .text-muted,
   body[data-theme="dark"] .form-label,
   body[data-theme="dark"] label,
   body[data-theme="dark"] .small,
   body[data-theme="dark"] h4,
   body[data-theme="dark"] h5,
   body[data-theme="dark"] h3 {
    color: #e2e8f0 !important;
   }

   body[data-theme="dark"] .bg-light,
   body[data-theme="dark"] .table-light {
    background: #1f2937 !important;
    color: #edf2f7 !important;
   }

   body[data-theme="dark"] .form-control,
   body[data-theme="dark"] .form-select {
    background-color: #0f172a;
    border-color: rgba(148, 163, 184, 0.26);
    color: #f8fafc;
   }

   body[data-theme="dark"] .btn-outline-danger,
   body[data-theme="dark"] .btn-primary,
   body[data-theme="dark"] .btn-warning {
    box-shadow: 0 0 18px rgba(59, 130, 246, 0.12);
   }

    .settings-header {
     flex-wrap: wrap;
     gap: .75rem;
    }

    .theme-mode-toggle {
     display: inline-flex;
     align-items: center;
     gap: .75rem;
     margin: 0;
     padding: .45rem .75rem;
     border: 1px solid rgba(46, 111, 181, .2);
     border-radius: 999px;
     background: rgba(46, 111, 181, .06);
    }

    .theme-mode-toggle .form-check-label {
     cursor: pointer;
     white-space: nowrap;
    }

    .theme-mode-toggle .form-check-input {
     float: none;
     width: 2.7rem;
     height: 1.5rem;
     margin: 0;
     cursor: pointer;
     background-color: #d7e0e5;
     border: 1px solid #9aadb7;
     box-shadow: none;
     transition: background-color .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .theme-mode-toggle .form-check-input:checked {
     background-color: #2f8f62;
     border-color: #2f8f62;
     box-shadow: 0 0 0 2px rgba(243, 214, 93, .2), 0 0 16px rgba(47, 143, 98, .28);
    }

    .theme-mode-toggle .form-check-input:focus-visible {
     border-color: #2e6fb5;
     box-shadow: 0 0 0 3px rgba(46, 111, 181, .24);
    }

    body[data-theme="dark"] .theme-mode-toggle {
     background: rgba(46, 111, 181, .14);
     border-color: rgba(125, 211, 252, .28);
    }

    body[data-theme="dark"] .theme-mode-toggle .form-check-input {
     background-color: #334155;
     border-color: #64748b;
    }

    body[data-theme="dark"] .theme-mode-toggle .form-check-input:checked {
     background-color: #2f8f62;
     border-color: #6ee7a8;
     box-shadow: 0 0 0 2px rgba(243, 214, 93, .18), 0 0 18px rgba(47, 143, 98, .35), 0 0 22px rgba(46, 111, 181, .22);
    }

    .settings-page .card {
     overflow: hidden;
     border: 1px solid rgba(46, 111, 181, .12);
     border-radius: .75rem;
     background: #fff;
    }

    .settings-page .card-header {
     padding: 1rem 1.25rem;
     background: linear-gradient(135deg, rgba(47, 143, 98, .13), rgba(46, 111, 181, .12));
     border-bottom: 1px solid rgba(46, 111, 181, .12);
    }

    .settings-page .card-body {
     padding: 1.25rem;
    }

    .settings-page .settings-panel {
     margin-top: 1rem;
     border-color: rgba(46, 111, 181, .16);
     background: linear-gradient(145deg, rgba(47, 143, 98, .035), rgba(46, 111, 181, .045));
    }

    .settings-page .settings-section-title {
     padding-left: .7rem;
     border-left: 4px solid #2f8f62;
     color: #1f6d4a;
    }

    body[data-theme="dark"] .settings-page .card {
     border-color: rgba(148, 163, 184, .22);
     background: #111827;
    }

    body[data-theme="dark"] .settings-page .card-header {
     background: linear-gradient(135deg, rgba(47, 143, 98, .24), rgba(46, 111, 181, .24));
     border-bottom-color: rgba(148, 163, 184, .2);
    }

    body[data-theme="dark"] .settings-page .settings-panel {
     background: linear-gradient(145deg, rgba(47, 143, 98, .08), rgba(46, 111, 181, .1));
    }

    body[data-theme="dark"] .settings-page .settings-section-title {
     border-left-color: #f3d65d;
     color: #b7f0ce !important;
    }

   /* desktop offset provided by sidebar.blade.php styles */
   .page-content{
        margin-left: 260px;
        width: calc(100% - 260px);
   }
   @media (max-width: 767.98px){
        .page-content{margin-left:0 !important; width:100% !important;}
   }
</style>

<?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="page-content">
    <main class="container settings-page mt-4 mb-5">

        <?php echo $__env->make('partials.feedback-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="card settings-shell shadow-lg">
            <div class="card-header settings-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Settings</h3>
                <div class="form-check form-switch theme-mode-toggle">
                    <label class="form-check-label fw-semibold" for="darkModeToggle">Dark mode</label>
                    <input class="form-check-input theme-mode-toggle__input" type="checkbox" role="switch" id="darkModeToggle" aria-label="Toggle dark mode" />
                </div>
            </div>

            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((int) (session('user_role') ?? 0) === 2 || (int) (session('user_role') ?? 0) === 3): ?>
                    <?php echo $__env->make('partials.school-year-settings', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <!-- RFID Assign -->
                    <br>
                    <h4 class="settings-section-title mb-3">Assign RFID</h4>
                    <form action="<?php echo e(route('settings.assign_rfid')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="rfid_user_id" class="form-label">User</label>
                            <select name="user_id" id="rfid_user_id" class="form-select" required>
                                <option value="">Select a user</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <?php
                                        $rfidCode = strtoupper(trim((string) $user->RFID_code));
                                        $hasAssignedRfid = $rfidCode !== '' && !in_array($rfidCode, ['0', 'N/A'], true);
                                    ?>
                                    <option value="<?php echo e($user->id); ?>" data-rfid-assigned="<?php echo e($hasAssignedRfid ? '1' : '0'); ?>"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?> (<?php echo e($user->email); ?>)</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>


<div class="mb-3">
    <label class="form-label">RFID Code <small class="text-muted">(Tap card)</small></label>
    <input type="hidden" name="RFID_code" id="rfid_input">
    <span id="rfid_label" class="form-control bg-light" style="font-family:monospace">Waiting for card...</span>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rfidInput = document.getElementById('rfid_input');
        const rfidLabel = document.getElementById('rfid_label');
        const userSelect = document.getElementById('rfid_user_id');
        const assignButton = document.getElementById('assign_rfid_button');
        const updateButton = document.getElementById('update_rfid_button');

        function updateRfidAction() {
            const selectedOption = userSelect.selectedOptions[0];
            const hasAssignedRfid = selectedOption?.dataset.rfidAssigned === '1';

            assignButton.classList.toggle('d-none', !selectedOption?.value || hasAssignedRfid);
            updateButton.classList.toggle('d-none', !selectedOption?.value || !hasAssignedRfid);
            assignButton.disabled = true;
            updateButton.disabled = true;
            rfidInput.value = '';
            rfidLabel.textContent = 'Waiting for card...';
        }

        userSelect.addEventListener('change', updateRfidAction);
        updateRfidAction();

        // POST /api/rfid-scan (sent by the ESP8266 when a card is tapped).
        setInterval(async () => {
            try {
                const response = await fetch('/api/check-latest-assignment', { cache: 'no-store' });
                if (!response.ok) throw new Error(`Scan check failed: ${response.status}`);
                const data = await response.json();

                if (data.uid && data.uid !== 'N/A' && data.uid !== rfidInput.value) {
                    rfidInput.value = data.uid;
                    rfidLabel.textContent = data.uid;
                    assignButton.disabled = false;
                    updateButton.disabled = false;
                }
            } catch (error) {
                console.error("Error fetching RFID scan:", error);
                rfidLabel.textContent = 'Unable to read scanner';
            }
        }, 2000);
    });
</script>

                        <button type="submit" id="assign_rfid_button" class="btn btn-primary d-none mx-3" disabled>Assign RFID</button>
                        <button type="submit" id="update_rfid_button" class="btn btn-warning d-none mx-3" disabled>Update RFID</button>
                    </form>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <br>

                
                <div class="card settings-panel">
                    <div class="card-body mb-0">
                        <h5 class="settings-section-title">Reset my password</h5>
                        <form action="<?php echo e(route('settings.reset_password')); ?>" method="POST" class="mt-3">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label class="form-label">Current password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New password</label>
                                <input type="password" name="password" class="form-control" required minlength="8">
                            </div>

                            <button type="submit" class="btn btn-primary">Reset password</button>
                        </form>
                    </div>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((int) (session('user_role') ?? 0) === 1): ?>
                    <div class="card settings-panel mt-3">
                        <div class="card-body">
                            <h4 class="settings-section-title">Reset password (Admin)</h4>

                            
                            <form action="<?php echo e(route('settings.reset_user_password')); ?>" method="POST" class="mt-3">
                                <?php echo csrf_field(); ?>

                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User</label>
                                    <select name="user_id" id="user_id" class="form-select" required>
                                        <option value="">Select a user</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?> (<?php echo e($user->email); ?>)</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">New password</label>
                                    <input type="password" name="password" class="form-control" required minlength="8">
                                </div>

                                <button type="submit" class="btn btn-primary">Reset user password</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </main>
</div>
<?php echo $__env->make('partials.notifications-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH /Volumes/shared file/4th year/capstone project/RFInsiDe/resources/views/settings.blade.php ENDPATH**/ ?>