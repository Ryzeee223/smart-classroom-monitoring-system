<?php
    $role = (int) (session('user_role') ?? 0);
?>

<style>
    :root {
        --college-green: #2f8f62;
        --college-green-deep: #1d6a47;
        --college-yellow: #f3d65d;
        --college-blue: #2e6fb5;
        --college-bg: #f5faf7;
        --college-panel: #ffffff;
        --college-text: #1d2a2a;
        --college-muted: #5e6d71;
    }

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
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">📊Dashboard</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('users.index') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('users.index')); ?>">👥Users</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('college') ? 'active fw-bold' : ''); ?>" href= "<?php echo e(route('college')); ?>">🏫Colleges</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('settings') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('settings')); ?>">⚙️Settings</a></li>
                
            <?php elseif($role === 2): ?>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>"> 📊Dashboard</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('users.index') ? 'active fw-bold' : ''); ?>" href=<?php echo e(route('users.index')); ?>>👥Users</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('rooms.index') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('rooms.index')); ?>">🏙Rooms and Buildings</a></li>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('myschedule') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('myschedule')); ?>">📆My Schedule</a></li>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('schedules') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('schedules')); ?>">✏️Create Schedules</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('course') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route ('course')); ?>">📚Subject</a></li>
            
           
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('programs') || request()->routeIs('programs.index') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('programs')); ?>">🎓Programs</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('settings') || request()->is('settings*') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('settings')); ?>">⚙️Settings</a></li>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('reports') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('reports')); ?>">📋Reports</a></li>

            <?php elseif($role === 3): ?>
            
                        <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">📊Dashboard</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('users.index') ? 'active fw-bold' : ''); ?>" href=<?php echo e(route('users.index')); ?>>👥Users</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('myschedule') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('myschedule')); ?>">📆My Schedule</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('course') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route ('course')); ?>">📚Subject</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('schedules') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('schedules')); ?>">✏️Create Schedules</a></li>
           
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('programs') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('programs')); ?>">🎓Programs</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('settings') || request()->is('settings*') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('settings')); ?>">⚙️Settings</a></li>

            
        <?php elseif($role === 4): ?>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">📊Dashboard</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('myschedule') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('myschedule')); ?>">📆My Schedule</a></li>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('settings') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('settings')); ?>">⚙️Settings</a></li>
        <?php elseif($role === 5): ?>
        <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">📊Dashboard</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('myschedule') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('myschedule')); ?>">📆My Schedule</a></li>
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('settings') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('settings')); ?>">⚙️Settings</a></li>
        <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('reports') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('reports')); ?>">📋Reports</a></li>
            <?php else: ?>
            
            <li class="nav-item"><a class="nav-link" href="/">⏻Logout</a></li>
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
    <a class="nav-link <?php echo e(request()->routeIs('profile') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('profile')); ?>">👤Profile</a>
</div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('logged_in')): ?>
        <div class="app-sidebar__footer " style="margin-bottom: 5rem;">
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-danger w-100">⏻Logout</button>
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
        background: linear-gradient(180deg, #f4fbf7 0%, #f5f9ff 100%);
        border-right:1px solid rgba(47,143,98,0.12);
        padding:16px;
        display:flex;
        flex-direction:column;
        box-shadow: 0 12px 30px rgba(20, 42, 55, 0.06);
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
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(46,111,181,0.08);
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
        color: var(--college-green-deep);
        letter-spacing: 0.03em;
    }
    .app-sidebar__user{font-size:.85rem;color: var(--college-muted); margin-top:4px;}
    .app-sidebar__notifications{
        width:36px;
        height:36px;
        padding:0;
        flex:0 0 auto;
        background: linear-gradient(135deg, var(--college-yellow-soft), #fff);
        border: 1px solid rgba(243,214,93,0.38);
        color: var(--college-text);
    }
    .notification-icon{
        display:inline-block;
        line-height:1;
    }
    .app-sidebar__notifications:focus-visible{
        outline:3px solid rgba(46,111,181,0.35);
        outline-offset:2px;
    }
    .app-sidebar__body{flex:1; overflow:auto; padding-right:6px;}
    .app-sidebar__body .nav-link{
        border-radius:12px; 
        margin:2px 0; 
        color: var(--college-text);
        font-weight: 600;
        padding: 0.7rem 0.8rem;
        transition: 0.2s ease;
    }
    .app-sidebar__body .nav-link:hover{
        background: linear-gradient(135deg, rgba(243,214,93,0.18), rgba(46,111,181,0.12));
        transform: translateX(2px);
        box-shadow: 0 0 0 2px rgba(243,214,93,0.12), 0 8px 18px rgba(46,111,181,0.14), 0 0 18px rgba(47,143,98,0.10);
    }
    .app-sidebar__body .nav-link.active{
        background: linear-gradient(135deg, var(--college-green) 0%, var(--college-blue) 100%);
        color:#fff;
        box-shadow: 0 0 0 2px rgba(243,214,93,0.16), 0 12px 22px rgba(47,143,98,0.28), 0 0 22px rgba(46,111,181,0.22);
    }
    .app-sidebar__footer{padding-top:12px;}

    body[data-theme="dark"] {
        background: #0b1220;
        color: #edf2f7;
    }

    body[data-theme="dark"] .page-content,
    body[data-theme="dark"] .app-shell__content,
    body[data-theme="dark"] main,
    body[data-theme="dark"] .container,
    body[data-theme="dark"] .container-fluid {
        background: transparent;
    }

    body[data-theme="dark"] .app-sidebar {
        background: linear-gradient(180deg, #111827 0%, #0f172a 100%);
        border-right: 1px solid rgba(148, 163, 184, 0.16);
        box-shadow: 0 12px 28px rgba(2, 6, 23, 0.45);
    }

    body[data-theme="dark"] .app-sidebar__notifications {
        background: linear-gradient(135deg, rgba(243,214,93,0.95), rgba(125,211,252,0.9));
        border-color: rgba(243,214,93,0.8);
        color: #0f172a;
        box-shadow: 0 0 18px rgba(243,214,93,0.35), 0 0 24px rgba(96,165,250,0.22);
    }

    body[data-theme="dark"] .app-sidebar__notifications .notification-icon {
        filter: none;
    }

    body[data-theme="dark"] .app-sidebar .btn-outline-dark {
        background: linear-gradient(135deg, rgba(47,143,98,0.16), rgba(46,111,181,0.16));
        border-color: rgba(148, 163, 184, 0.2);
        color: #edf2f7;
    }

    body[data-theme="dark"] .app-sidebar .btn-outline-dark .nav-link,
    body[data-theme="dark"] .app-sidebar .btn-outline-dark a {
        color: #edf2f7 !important;
    }

    body[data-theme="dark"] .btn,
    body[data-theme="dark"] .btn-outline-dark,
    body[data-theme="dark"] .btn-light,
    body[data-theme="dark"] .btn-outline-primary,
    body[data-theme="dark"] .btn-outline-secondary,
    body[data-theme="dark"] .btn-primary,
    body[data-theme="dark"] .btn-warning,
    body[data-theme="dark"] .btn-danger {
        border-color: rgba(148, 163, 184, 0.24);
        box-shadow: 0 0 0 1px rgba(148, 163, 184, 0.08);
    }

    body[data-theme="dark"] .btn-outline-dark,
    body[data-theme="dark"] .btn-outline-primary,
    body[data-theme="dark"] .btn-outline-secondary {
        background: linear-gradient(135deg, rgba(47,143,98,0.18), rgba(46,111,181,0.18));
        color: #edf2f7;
    }

    body[data-theme="dark"] .btn-outline-dark:hover,
    body[data-theme="dark"] .btn-outline-primary:hover,
    body[data-theme="dark"] .btn-outline-secondary:hover {
        background: linear-gradient(135deg, rgba(47,143,98,0.28), rgba(46,111,181,0.28));
        border-color: rgba(243,214,93,0.38);
        box-shadow: 0 0 18px rgba(47,143,98,0.18), 0 0 24px rgba(46,111,181,0.15);
    }

    body[data-theme="dark"] .app-sidebar__brand-link,
    body[data-theme="dark"] .app-sidebar__user,
    body[data-theme="dark"] .app-sidebar__body .nav-link,
    body[data-theme="dark"] .app-sidebar__body .nav-link.active,
    body[data-theme="dark"] .app-sidebar__body .nav-link:hover {
        color: #e2e8f0;
    }

    body[data-theme="dark"] .app-sidebar__body .nav-link {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(148, 163, 184, 0.08);
    }

    body[data-theme="dark"] .app-sidebar__body .nav-link:hover {
        background: linear-gradient(135deg, rgba(243,214,93,0.14), rgba(46,111,181,0.14));
    }

    body[data-theme="dark"] .card,
    body[data-theme="dark"] .card-body,
    body[data-theme="dark"] .card-header,
    body[data-theme="dark"] .form-control,
    body[data-theme="dark"] .form-select,
    body[data-theme="dark"] .table,
    body[data-theme="dark"] .table-responsive,
    body[data-theme="dark"] .alert,
    body[data-theme="dark"] .modal-content {
        background: linear-gradient(180deg, #111827 0%, #0f172a 100%);
        color: #edf2f7;
        border-color: rgba(148, 163, 184, 0.22);
    }

    body[data-theme="dark"] .dropdown-menu {
        background: #111827;
        border-color: rgba(148, 163, 184, 0.22);
        box-shadow: 0 0 0 2px rgba(243,214,93,0.16), 0 12px 22px rgba(47,143,98,0.28), 0 0 22px rgba(46,111,181,0.22);
    }

    body[data-theme="dark"] .dropdown-item {
        color: #e2e8f0;
    }

    body[data-theme="dark"] .dropdown-item:hover,
    body[data-theme="dark"] .dropdown-item:focus {
        background: linear-gradient(135deg, rgba(47,143,98,0.22), rgba(46,111,181,0.22));
        color: #fff;
    }

    body[data-theme="dark"] .form-select:focus,
    body[data-theme="dark"] .dropdown-toggle:focus-visible {
        border-color: rgba(243,214,93,0.55);
        box-shadow: 0 0 0 2px rgba(243,214,93,0.16), 0 12px 22px rgba(47,143,98,0.28), 0 0 22px rgba(46,111,181,0.22);
    }

    body[data-theme="dark"] .text-muted,
    body[data-theme="dark"] .form-label,
    body[data-theme="dark"] label,
    body[data-theme="dark"] .small,
    body[data-theme="dark"] h1,
    body[data-theme="dark"] h2,
    body[data-theme="dark"] h3,
    body[data-theme="dark"] h4,
    body[data-theme="dark"] h5,
    body[data-theme="dark"] h6,
    body[data-theme="dark"] .fw-semibold,
    body[data-theme="dark"] .card-title {
        color: #e2e8f0 !important;
    }

    body[data-theme="dark"] .bg-light,
    body[data-theme="dark"] .alert-light {
        background: #1f2937 !important;
        color: #edf2f7 !important;
    }

    body[data-theme="dark"] .form-control,
    body[data-theme="dark"] .form-select {
        background-color: #0f172a;
        border-color: rgba(148, 163, 184, 0.26);
        color: #f8fafc;
    }

    body[data-theme="dark"] .form-control::placeholder {
        color: rgba(226, 232, 240, 0.7);
    }

    
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
            .then(response => {
                if (!response.ok) {
                    throw new Error(`RFID endpoint returned HTTP ${response.status}`);
                }
                return response.json();
            })
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
                button.disabled = true;
                button.setAttribute('aria-busy', 'true');

                let modal;
                try {
                    const response = await fetch(button.dataset.notificationsUrl, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) {
                        throw new Error(`Notifications endpoint returned HTTP ${response.status}`);
                    }

                    document.getElementById('notificationsModal')?.remove();
                    document.body.insertAdjacentHTML('beforeend', await response.text());
                    modal = document.getElementById('notificationsModal');
                } catch (error) {
                    console.error('Error loading notifications:', error);
                } finally {
                    button.disabled = false;
                    button.removeAttribute('aria-busy');
                }

                if (modal && window.bootstrap) {
                    bindModal(modal);
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        });
    });
</script>
<?php /**PATH /Volumes/shared file/4th year/capstone project/RFInsiDe/resources/views/sidebar.blade.php ENDPATH**/ ?>