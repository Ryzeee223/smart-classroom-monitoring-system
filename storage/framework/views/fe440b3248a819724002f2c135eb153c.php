<?php
    $role = (int) (session('user_role') ?? 0);
?>

<nav class="app-sidebar card-body border" aria-label="Sidebar">
    <div class="app-sidebar__brand">
        <a class="app-sidebar__brand-link" href="/">eMonitor</a>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('logged_in')): ?>
            <div class="app-sidebar__user"><?php echo e(session('user_name') ?? ''); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('logged_in')): ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="app-sidebar__body card-body">
        <ul class="nav flex-column">
            

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role === 1 ): ?>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('users.index') ? 'active fw-bold' : ''); ?>" href="/users">Users</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('college') ? 'active fw-bold' : ''); ?>" href= "/college">Colleges</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('rooms.index') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('rooms.index')); ?>">Rooms and Buildings</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('settings') ? 'active fw-bold' : ''); ?>" href="/settings">Settings</a></li>

                <li class="nav-item"><span class="nav-link text-muted">Reports</span></li>
                
            <?php elseif($role === 2 || $role === 3): ?>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('users.index') ? 'active fw-bold' : ''); ?>" href=<?php echo e(route('users.index')); ?>>Users</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('myschedule') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('myschedule')); ?>">My Schedule</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('course') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route ('course')); ?>">Course</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('schedules') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('schedules')); ?>">Create Schedules</a></li>
           
<li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('programs') ? 'active fw-bold' : ''); ?>" href="/programs">Programs</a></li>
            
            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('settings')); ?>">Settings</a></li>
            
            
        <?php elseif($role === 4): ?>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('myschedule') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('myschedule')); ?>">My Schedule</a></li>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('settings') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('settings')); ?>">Settings</a></li>
        <?php elseif($role === 5): ?>
        <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('myschedule') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('myschedule')); ?>">My Schedule</a></li>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('settings') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('settings')); ?>">Settings</a></li>
        <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('reports') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('reports')); ?>">Reports</a></li>
            <?php else: ?>
            
            <li class="nav-item"><a class="nav-link" href="/">Logout</a></li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>
    </div>


<?php
    $latestSemyr = \App\Models\semyr::query()->latest('id')->first();
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('logged_in')): ?>
    <div class="text-center mb-2" style="font-size: .85rem; color:#6c757d;">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($latestSemyr): ?>
            <div><strong><?php echo e($latestSemyr->school_year); ?></strong></div>
            <div><?php echo e($latestSemyr->semester); ?></div>
        <?php else: ?>
            <div>School year / semester not set</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="btn btn-outline-dark w-100 nav-item text-center">
    <a class="nav-link <?php echo e(request()->routeIs('profile') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('profile')); ?>">Profile</a>
</div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('logged_in')): ?>
        <div class="app-sidebar__footer">
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-danger w-100">Logout</button>
            </form>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</nav>


<style>
    .app-sidebar{
        width:260px;
        height:100%;
        position:fixed;

        top:0;
        left:0;
        background:#f8f9fa;
        border-right:1px solid #e9ecef;
        padding:16px;
        display:flex;
        flex-direction:column;
    }

    /* default desktop content offset (safe on mobile via media query below) */
    .page-content{
        margin-left:260px;
        width:calc(100% - 260px);
    }

    .app-sidebar__brand{margin-bottom:12px;}
    .app-sidebar__brand-link{
        font-weight:800;
        font-size:1.25rem;
        text-decoration:none;
        color:inherit;
    }
    .app-sidebar__user{font-size:.85rem;color:#6c757d;margin-top:4px;}
    .app-sidebar__body{flex:1; overflow:auto; padding-right:6px;}
    .app-sidebar__body .nav-link{border-radius:10px; margin:2px 0; color:#212529;}
    .app-sidebar__body .nav-link.active{background:#0d6efd22; color:#0d6efd;}
    .app-sidebar__footer{padding-top:12px;}

    
    .app-shell{display:flex; min-height:100vh;}
    .app-shell__content{flex:1; min-width:0;}

    
    @media (max-width: 767.98px){
        /* keep sidebar content visible when included on mobile pages */
        .app-sidebar{display:block; position:relative; width:100%; height:auto; border-right:none; padding:12px;}
        .app-sidebar__body{max-height:none; overflow:visible;}
        /* prevent pages from being pushed off-screen on mobile */
        .page-content{margin-left:0 !important; width:100% !important;}
    }


</style>

<script>
    let lastDetectedUid = null;

    // Check the server for a new scan every 2 seconds
    setInterval(() => {
        fetch('/api/check-latest-scan')
            .then(response => response.json())
            .then(data => {
                // If a card is active, and it's NOT the one we just handled
                if (data.uid && data.uid !== lastDetectedUid) {
                    lastDetectedUid = data.uid;

                    if (typeof window.handleRfidScanGlobally === 'function') {
                        window.handleRfidScanGlobally(data.uid);
                    }
                } else if (!data.uid) {
                    // Clear the last detected card once it expires from cache
                    lastDetectedUid = null;
                }
            })
            .catch(error => {
                // A single failed poll must never break the whole page.
                console.error('Error checking RFID:', error);
            });
    }, 2000); // 2000ms = 2 seconds


</script>
<?php /**PATH /Volumes/shared/capstone project/backups/emonitor 3rd phase copy/resources/views/sidebar.blade.php ENDPATH**/ ?>