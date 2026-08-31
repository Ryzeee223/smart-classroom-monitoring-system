<?php
    $role = (int) (session('user_role') ?? 0);
?>

<style>
    .content-scroll {
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>

<nav class="app-sidebar" aria-label="Sidebar">
    <div class="app-sidebar__header">
        <div class="app-sidebar__brand">
            <a class="app-sidebar__brand-link" href="/">RFInsiDe</a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('logged_in')): ?>
                <div class="app-sidebar__user"><?php echo e(session('user_name') ?? ''); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role === 2 || $role === 3 || $role === 4 || $role === 5): ?>
             <button type="button"
            class="notifications-trigger app-sidebar__notifications btn btn-light btn-sm rounded-circle"
                aria-label="Open notifications"
            aria-haspopup="dialog"
            aria-controls="notificationsModal"
            aria-expanded="false"
                title="Notifications"
                data-notifications-url="<?php echo e(route('notifications.modal')); ?>">
            <span aria-hidden="true" class="notification-icon" style="filter: grayscale(1) brightness(0);">🔔</span>
        </button>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
       
    </div>

    <div class="app-sidebar__body card-body content-scroll">
        <ul class="nav flex-column ">
            

            
           <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role === 1 ): ?>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('users.index') ? 'active fw-bold' : ''); ?>" href="/users">Users</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('college') ? 'active fw-bold' : ''); ?>" href= "/college">Colleges</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('rooms.index') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('rooms.index')); ?>">Rooms and Buildings</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('settings') ? 'active fw-bold' : ''); ?>" href="/settings">Settings</a></li>

                <li class="nav-item"><span class="nav-link text-muted">Reports</span></li>
                
            <?php elseif($role === 2): ?>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('users.index') ? 'active fw-bold' : ''); ?>" href=<?php echo e(route('users.index')); ?>>Users</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('myschedule') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('myschedule')); ?>">My Schedule</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('course') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route ('course')); ?>">Course</a></li>
            
           
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('programs') ? 'active fw-bold' : ''); ?>" href="/programs">Programs</a></li>
            
            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('settings')); ?>">Settings</a></li>

            <?php elseif($role === 3): ?>
            
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
        <div class="app-sidebar__footer " style="margin-bottom: 5rem;">
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

    body > .app-sidebar + * {
        margin-left: 260px;
        width: calc(100% - 260px);
    }

    .app-sidebar__header{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:12px;
        margin-bottom:16px;
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
    .app-sidebar__notifications{
        width:36px;
        height:36px;
        padding:0;
        flex:0 0 auto;
    }
    .notification-icon{
        display:inline-block;
        line-height:1;
    }
    .app-sidebar__notifications:focus-visible{
        outline:3px solid #0d6efd;
        outline-offset:2px;
    }
    .app-sidebar__body{flex:1; overflow:auto; padding-right:6px;}
    .app-sidebar__body .nav-link{border-radius:10px; margin:2px 0; color:#212529;}
    .app-sidebar__body .nav-link.active{background:#0d6efd22; color:#0d6efd;}
    .app-sidebar__footer{padding-top:12px;}

    
    .app-shell{display:block; min-height:100vh;}
    .app-shell__content{
        min-width:0;
        margin-left:260px;
        width:calc(100% - 260px);
    }

    
    @media (max-width: 767.98px){
        /* keep sidebar content visible when included on mobile pages */
        .app-sidebar{display:block; position:relative; width:100%; height:auto; border-right:none; padding:12px;}
        .app-sidebar__body{max-height:none; overflow:visible;}
        .app-shell__content{margin-left:0; width:100%;}
        body > .app-sidebar + * {
            margin-left: 0 !important;
            width: 100% !important;
        }
        /* prevent pages from being pushed off-screen on mobile */
        .page-content{margin-left:0 !important; width:100% !important;}
    }


</style>

<script>
    let lastDetectedUid = null;

    // Check the server for a new scan every 2 seconds
    setInterval(() => {
        fetch('/api/check-latest-attendance')
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
    }, 2000); 


</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.notifications-trigger');
        if (!buttons.length) return;

        buttons.forEach(function (button) {
            function bindModal(modal) {
                if (!modal || modal.dataset.notificationsAccessibilityBound) return;

                modal.dataset.notificationsAccessibilityBound = 'true';
                modal.addEventListener('shown.bs.modal', function () {
                    button.setAttribute('aria-expanded', 'true');
                });
                modal.addEventListener('hidden.bs.modal', function () {
                    button.setAttribute('aria-expanded', 'false');
                    button.focus();
                });
            }

            button.addEventListener('click', async function (event) {
                event.preventDefault();
                let modal = document.getElementById('notificationsModal');

                if (!modal) {
                    button.disabled = true;
                    button.setAttribute('aria-busy', 'true');

                    try {
                        const response = await fetch(button.dataset.notificationsUrl, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });

                        if (!response.ok) return;

                        document.body.insertAdjacentHTML('beforeend', await response.text());
                        modal = document.getElementById('notificationsModal');
                    } finally {
                        button.disabled = false;
                        button.removeAttribute('aria-busy');
                    }
                }

                if (modal && window.bootstrap) {
                    bindModal(modal);
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        });
    });
</script>
<?php /**PATH D:\capstone project\backups\emonitor 3rd phase copy\resources\views/sidebar.blade.php ENDPATH**/ ?>