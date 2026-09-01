@php
    $role = (int) (session('user_role') ?? 0);
@endphp

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
            @if(session('logged_in'))
                <div class="app-sidebar__user">{{ session('user_name') ?? '' }}</div>
            @endif
        </div>
        {{-- notification --}}
        @if ($role === 2 || $role === 3 || $role === 4 || $role === 5)
             <button type="button"
            class="notifications-trigger app-sidebar__notifications btn btn-light btn-sm rounded-circle"
                aria-label="Open notifications"
            aria-haspopup="dialog"
            aria-controls="notificationsModal"
            aria-expanded="false"
                title="Notifications"
                data-notifications-url="{{ route('notifications.modal') }}">
            <span aria-hidden="true" class="notification-icon" style="filter: grayscale(1) brightness(0);">🔔</span>
        </button>
        @endif
       
    </div>

    <div class="app-sidebar__body card-body content-scroll">
        <ul class="nav flex-column ">
            {{-- 1=admin 2=dean 3=asst. dean 4=faculty 5=programhead --}}

            {{-- for admin --}}
           @if ($role === 1 )
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('users.index') ? 'active fw-bold' : '' }}" href="/users">Users</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('college') ? 'active fw-bold' : '' }}" href= "/college">Colleges</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('rooms.index') ? 'active fw-bold' : '' }}" href="{{ route('rooms.index') }}">Rooms and Buildings</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('settings') ? 'active fw-bold' : '' }}" href="/settings">Settings</a></li>

                <li class="nav-item"><span class="nav-link text-muted">Reports</span></li>
                {{-- for dean/asst dean--}}
            @elseif ($role === 2)
            {{-- dashboard --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
            {{-- users --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('users.index') ? 'active fw-bold' : '' }}" href={{ route('users.index') }}>Users</a></li>
            {{-- mysched --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('myschedule') ? 'active fw-bold' : '' }}" href="{{ route('myschedule') }}">My Schedule</a></li>
            {{-- Subjects --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('course') ? 'active fw-bold' : '' }}" href="{{ route ('course') }}">Course</a></li>
            {{-- schedule --}}
           {{-- College --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('programs') ? 'active fw-bold' : '' }}" href="/programs">programs</a></li>
            {{-- settings --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('settings') }}">Settings</a></li>

            @elseif ($role === 3)
            {{-- Asst Dean --}}
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
            {{-- users --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('users.index') ? 'active fw-bold' : '' }}" href={{ route('users.index') }}>Users</a></li>
            {{-- mysched --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('myschedule') ? 'active fw-bold' : '' }}" href="{{ route('myschedule') }}">My Schedule</a></li>
            {{-- Subjects --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('course') ? 'active fw-bold' : '' }}" href="{{ route ('course') }}">Course</a></li>
            {{-- schedule --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('schedules') ? 'active fw-bold' : '' }}" href="{{ route('schedules') }}">Create Schedules</a></li>
           {{-- College --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('programs') ? 'active fw-bold' : '' }}" href="/programs">programs</a></li>
            {{-- settings --}}
            <li class="nav-item"><a class="nav-link" href="{{ route('settings') }}">Settings</a></li>

            {{-- for faculty and head--}}
        @elseif ($role === 4)
            {{-- dashboard --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
            {{-- mysched --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('myschedule') ? 'active fw-bold' : '' }}" href="{{ route('myschedule') }}">My Schedule</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('settings') ? 'active fw-bold' : '' }}" href="{{ route('settings') }}">Settings</a></li>
        @elseif ($role === 5)
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
            {{-- mysched --}}
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('myschedule') ? 'active fw-bold' : '' }}" href="{{ route('myschedule') }}">My Schedule</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('settings') ? 'active fw-bold' : '' }}" href="{{ route('settings') }}">Settings</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('reports') ? 'active fw-bold' : '' }}" href="{{ route('reports') }}">Reports</a></li>
            @else
            
            <li class="nav-item"><a class="nav-link" href="/">Logout</a></li>
            @endif
        </ul>
    </div>

{{-- this should show the profile name of the user + active school year/semester --}}
@php
    $latestSemyr = \App\Models\semyr::query()->latest('id')->first();
@endphp

@if(session('logged_in'))
    <div class="text-center mb-2" style="font-size: .85rem; color:#6c757d;">
        @if($latestSemyr)
            <div><strong>{{ $latestSemyr->school_year }}</strong></div>
            <div>{{ $latestSemyr->semester }}</div>
        @else
            <div>School year / semester not set</div>
        @endif
    </div>
@endif

<div class="btn btn-outline-dark w-100 nav-item text-center">
    <a class="nav-link {{ request()->routeIs('profile') ? 'active fw-bold' : '' }}" href="{{ route('profile') }}">Profile</a>
</div>

    {{-- log out --}}
    @if(session('logged_in'))
        <div class="app-sidebar__footer " style="margin-bottom: 5rem;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">Logout</button>
            </form>
        </div>
    @endif
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
