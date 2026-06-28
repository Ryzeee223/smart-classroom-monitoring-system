<?php
    $role = (int) (session('user_role') ?? 0);
?>

<nav class="app-sidebar card-body border" aria-label="Sidebar">
    <div class="app-sidebar__brand">
        <a class="app-sidebar__brand-link" href="/">eMonitor</a>
<?php if(session('logged_in')): ?>
            <div class="app-sidebar__user"><?php echo e(session('user_name') ?? ''); ?></div>
        <?php endif; ?>

        
        <?php if(session('logged_in')): ?>
        <?php endif; ?>
    </div>

    <div class="app-sidebar__body card-body" style="max-height: calc(100vh - 160px);">
        <ul class="nav flex-column">
            

            
            <?php if($role === 1 ): ?>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('users.index') ? 'active fw-bold' : ''); ?>" href="/users">Users</a></li>
                
                 
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('college') ? 'active fw-bold' : ''); ?>" href= "/college">Colleges</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('rooms.*') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('rooms.index')); ?>">Rooms</a></li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('settings') ? 'active fw-bold' : ''); ?>" href="/settings">Settings</a></li>

                <li class="nav-item"><span class="nav-link text-muted">Reports</span></li>
                
            <?php elseif($role === 2 || $role === 3): ?>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            
            <li class="nav-item"><a class="nav-link" href=<?php echo e(route('users.index')); ?>>Users</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('myschedule') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('myschedule')); ?>">My Schedule</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('subjects') ? 'active fw-bold' : ''); ?>" href="/subjects">Subjects</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('schedules') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('schedules')); ?>">Create Schedules</a></li>
           
<li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('course') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('course')); ?>">Program</a></li>
            
            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('settings')); ?>">Settings</a></li>
            
            
<?php elseif($role === 4 || $role === 5): ?>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            
            <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('myschedule') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('myschedule')); ?>">My Schedule</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('settings')); ?>">Settings</a></li>
            
            <?php else: ?>

            <li class="nav-item"><a class="nav-link" href="/">Logout</a></li>
            <?php endif; ?>
        </ul>
    </div>

    
<div class="btn btn-outline-dark w-100 nav-item text-center">
    <a class="nav-link <?php echo e(request()->routeIs('profile') ? 'active fw-bold' : ''); ?>" href="<?php echo e(route('profile')); ?>">Profile</a>
</div>

    
    <?php if(session('logged_in')): ?>
        <div class="app-sidebar__footer">
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-danger w-100">Logout</button>
            </form>
        </div>
    <?php endif; ?>
</nav>

<style>
    .app-sidebar{
        width:260px;
        height:100vh;
        position:fixed;
        top:0;
        left:0;
        background:#f8f9fa;
        border-right:1px solid #e9ecef;
        padding:16px;
        display:flex;
        flex-direction:column;
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
        .app-sidebar{display:none;}
    }
</style>

<?php /**PATH /Users/macbook/Documents/capstone project/backups/emonitor 3rd phase copy/resources/views/sidebar.blade.php ENDPATH**/ ?>