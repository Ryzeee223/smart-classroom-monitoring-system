<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - eMonitor</title>
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
</head>
<body>

<style>
    .app-shell { display: flex; min-height: 100vh; }
    .app-shell__content { flex: 1; min-width: 0; margin-left: 260px; }
    @media (max-width: 767.98px) {
        .app-shell__content { margin-left: 0; }
    }
</style>

<?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="app-shell">
    <main class="app-shell__content container mt-4">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3>Settings</h3>
            </div>

            <div class="card-body">
                <?php if((int) (session('user_role') ?? 0) === 1): ?>
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
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?> (<?php echo e($user->email); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">RFID Code <small class="text-muted">(Tap card)</small></label>
                            <input type="hidden" name="rfid_code" id="rfid_input">
                            <span id="rfid_label" class="form-control bg-light" style="font-family:monospace">N/A</span>

                            <script>
                                let lastUid = '';
                                setInterval(async () => {
                                  try {
                                    const res = await fetch('http://localhost:3000/status');
                                    const data = await res.json();
                                    if (data.uid && data.uid !== lastUid) {
                                      document.getElementById('rfid_label').textContent = data.uid;
                                      document.getElementById('rfid_input').value = data.uid;
                                      lastUid = data.uid;
                                    }
                                  } catch (e) {}
                                }, 100);
                            </script>

                            <script>
                                let ws;
                                function connectRfidWs() {
                                    ws = new WebSocket('ws://localhost:3001');

                                    ws.onopen = () => console.log('RFID WS connected');
                                    ws.onmessage = (event) => {
                                        const data = JSON.parse(event.data);
                                        if (data.type === 'rfid') {
                                            document.getElementById('rfid_label').textContent = data.uid;
                                            document.getElementById('rfid_input').value = data.uid;
                                            console.log('RFID:', data.uid);
                                        }
                                    };
                                    ws.onclose = () => setTimeout(connectRfidWs, 1000);
                                    ws.onerror = (err) => console.error('RFID WS error:', err);
                                }

                                function clearRfid() {
                                    document.getElementById('rfid_label').textContent = 'N/A';
                                    document.getElementById('rfid_input').value = '';
                                }

                                connectRfidWs();
                            </script>
                        </div>

                        <button type="submit" class="btn btn-primary">Assign RFID</button>
                    </form>

                <?php endif; ?>

                <br>

                
                <div class="card">
                    <div class="card-body">
                        <h5 class="mt-3">Reset my password</h5>
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

                
                <?php if((int) (session('user_role') ?? 0) === 1): ?>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h4>Reset password (Admin)</h4>

                            
                            <form action="<?php echo e(route('settings.reset_user_password')); ?>" method="POST" class="mt-3">
                                <?php echo csrf_field(); ?>

                                <div class="mb-3">
                                    <label for="user_id" class="form-label">User</label>
                                    <select name="user_id" id="user_id" class="form-select" required>
                                        <option value="">Select a user</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?> (<?php echo e($user->email); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>

<?php /**PATH /Users/macbook/Documents/capstone project/backups/emonitor 3rd phase copy/resources/views/settings.blade.php ENDPATH**/ ?>