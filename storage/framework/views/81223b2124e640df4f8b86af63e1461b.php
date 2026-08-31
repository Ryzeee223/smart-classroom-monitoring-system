<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - RFInsiDe</title>
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
</head>
<body>

<style>
   body{
    overflow-x: hidden;
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
    <main class="container mt-4 mb-5">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3>Settings</h3>
            </div>

            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if((int) (session('user_role') ?? 0) === 1): ?>
                    <?php echo $__env->make('partials.school-year-settings', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <!-- RFID Assign -->
                    <br>
                    <h4 class="mb-3">Assign RFID</h4>
                    <form action="<?php echo e(route('settings.assign_rfid')); ?>" method="POST">
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
    <label class="form-label">RFID Code <small class="text-muted">(Tap card)</small></label>
    <input type="hidden" name="rfid_code" id="rfid_input">
    <span id="rfid_label" class="form-control bg-light" style="font-family:monospace">Waiting for card...</span>
</div>


<script>
    const rfidInput = document.getElementById('rfid_input');
    const rfidLabel = document.getElementById('rfid_label');

    
    // POST /api/rfid-scan (sent by the ESP8266 when a card is tapped).
    const pollInterval = setInterval(async () => {
        try {
            const response = await fetch('/api/check-latest-assignment', { cache: 'no-store' });
            if (!response.ok) throw new Error(`Scan check failed: ${response.status}`);
            const data = await response.json();

            if (data.uid && data.uid !== 'N/A' && data.uid !== rfidInput.value) {
                // 1. Update the hidden input value
                rfidInput.value = data.uid;

                // 2. Update the visual span text
                rfidLabel.textContent = data.uid;

                // Keep polling so the next card can also be detected.
            }
        } catch (error) {
            console.error("Error fetching RFID scan:", error);
            rfidLabel.textContent = 'Unable to read scanner';
        }
    }, 2000);
</script>

                        <button type="submit" class="btn btn-primary">Assign RFID</button>
                    </form>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <br>

                
                <div class="card">
                    <div class="card-body mb-0">
                        <h5 class="form-label">Reset my password</h5>
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
                    <div class="card mt-3">
                        <div class="card-body">
                            <h4>Reset password (Admin)</h4>

                            
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

<?php /**PATH D:\capstone project\backups\emonitor 3rd phase copy\resources\views/settings.blade.php ENDPATH**/ ?>