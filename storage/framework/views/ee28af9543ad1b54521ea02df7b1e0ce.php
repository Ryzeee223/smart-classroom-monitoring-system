<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RFInsiDe</title>
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fontisto@v3.0.4/css/fontisto/fontisto.min.css">
</head>
<body>
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
</style>

        <!-- nav bar -->
    <?php echo $__env->make('sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    

<main role="main" class="page-content">
            <?php
                $role = (int) (session('user_role') ?? 0);
            ?>
            <div class="container-fluid dashboard-container px-4">
                
                <div class="mb-2 d-flex justify-content-end " style="radius:10px;">
                    </div>
            <div class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">

            <div class="col">
                <div class="card stats-card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Rooms</h6>
                        <div class="display-4 fw-bold text-primary mb-0"><?php echo e($countRoom ?? 0); ?></div>

                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card stats-card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Occupied</h6>
                        <div class="display-4 fw-bold text-success mb-0"><?php echo e($occupiedRooms ?? 0); ?></div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card stats-card h-100 shadow-sm">

                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Faculty</h6>
                        
                        <div class="display-4 fw-bold text-info mb-0"><?php echo e($facultyCount ?? 0); ?></div>
                    </div>
                </div> 
            </div>

            
                <div class="col">
                <div class="card stats-card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Pending RFID</h6>
                        <div class="display-4 fw-bold text-warning mb-0"><?php echo e($pending_count ?? 0); ?></div>
                    </div>
                </div>
            </div>
            
           

            </div>

        <!-- Main Content Row -->
        <div class="row g-4 mb-4">


            <!-- Live Classroom Status -->
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary bg-opacity-10">
                        <h5 class="mb-0 fw-bold text-dark">Live Classroom Status</h5>
                    </div>

                    
                    <div class="card-body p-0">
                        <p class="p-4 pb-3 text-muted small">Rooms update automatically as Faculty tap registered RFID Cards.</p>

                        
                        <div class="px-3 pb-2">
                            <div class="nav nav-pills flex-column flex-sm-row gap-2" role="tablist" aria-label="Buildings">
                                <?php
                                    $bldng = $buildings ?? null;
                                ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bldng && $buildings->count()): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bIndex => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <?php
                                            $abbr = $b->bldg_abbr ?? $b->abbr ?? $b->name ?? ('B' . ($bIndex + 1));
                                            $name = $b->bldg_name ?? $abbr;
                                        ?>

                                        <button
                                            class="nav-link building-tab <?php echo e($bIndex === 0 ?  : ''); ?>"
                                            type="button"
                                            data-building="<?php echo e($abbr); ?>"
                                            data-building-name="<?php echo e($name); ?>"
                                        >
                                            <?php echo e($abbr); ?>

                                        </button>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <?php else: ?>
                                    <button class="nav-link building-tab" type="button" data-building="None">N/A</button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </div>
                        </div>

                        
                        <div class="card-body" style="">
                            <h3 class="building-title"></h3>
                        </div>

                        
                        <div class="row row-cols-1 row-cols-md-2 g-3 px-4 pb-4" id="classroomGrid"></div>

                        
                        <?php
                        
                            // If not present, fall back to empty.
                            $roomsForGrid = $rooms ?? collect();

                            // Build the grid data in PHP so the @json directive only receives a simple variable.
                            $roomsGridData = $roomsForGrid->map(function ($r) {
                                return [
                                    'id' => $r->id ?? null,
                                    'name' => $r->room_name ?? '',
                                    'type' => $r->room_type ?? '',
                                    'bldg_abbr' => optional($r->building)->bldg_abbr ?? ($r->building_abbr ?? ''),
                                    'bldg_name' => optional($r->building)->bldg_name ?? ($r->building_name ?? ''),
                                    'status' => $r->status ?? 'unknown',
                                ];
                            })->values();
                        ?>

                        <script>
                            window.__roomsGrid = <?php echo json_encode($roomsGridData, 15, 512) ?>;
                        </script>


                    </div>
                </div>
            </div>

            <script>
                (function () {
                    const grid = document.getElementById('classroomGrid');
                    const buildingTitle = document.querySelector('.building-title');
                    const rooms = window.__roomsGrid || [];

                    function renderForBuilding(abbr, name) {
                        if (!grid) return;

                        const filtered = rooms.filter(r => (r.bldg_abbr || '') === (abbr || ''));

                        const title = name || (abbr ? `Building ${abbr}` : '');

                        if (!filtered.length) {
                            grid.innerHTML = `
                                <div class="col-12">
                                    <div class="alert alert-light border" role="alert">
                                        No rooms found for building <strong>${title}</strong>.
                                    </div>
                                </div>
                            `;
                            if (buildingTitle) buildingTitle.textContent = title;
                            return;
                        }

                        grid.innerHTML = filtered.map(r => {
                            const subtitle = [r.type].filter(Boolean).join(' ');
                            return `
                                <div class="col">
                                    <div class="card shadow-sm h-100 border-1">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <div>
                                                    <h6 class="mb-1 fw-bold small">${r.name || ''}</h6>
                                                    <p class="mb-0 text-muted fs-6">${r.bldg_abbr ? r.bldg_abbr : ''}</p>
                                                </div>
                                                <span class="badge ${r.status === 'vacant' ? 'bg-success' : r.status === 'occupied' ? 'bg-danger' : 'bg-secondary'} rounded-pill px-2 py-1 fs-6">${r.status || 'unknown'}</span>
                                                
                                            </div>
                                        </div>
                                        <div class="card-footer p-2 pt-1 bg-transparent border-0">
                                            <p class="mb-0 text-muted fs-6"><small class="fw-bold text-uppercase">Faculty:</small> None</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');

                        if (buildingTitle) buildingTitle.textContent = title;
                    }

                    document.addEventListener('DOMContentLoaded', function () {
                        const activeBtn = document.querySelector('.building-tab.active');
                        const initialAbbr = activeBtn ? activeBtn.getAttribute('data-building') : '';
                        const initialName = activeBtn ? activeBtn.getAttribute('data-building-name') : '';

                        if (initialAbbr !== undefined) renderForBuilding(initialAbbr, initialName);

                        document.querySelectorAll('.building-tab').forEach(btn => {
                            btn.addEventListener('click', function () {
                                const abbr = this.getAttribute('data-building') || '';
                                const name = this.getAttribute('data-building-name') || '';
                                renderForBuilding(abbr, name);
                            });
                        });
                    });
                })();
            </script>


                </div>
            </div>

            <?php
                $role = (int) (session('user_role') ?? 0);
            ?>



            <div class="row g-3 mb-5">

            <!-- Attendance -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(session('user_role'), [2,3,4,5])): ?>
<div class="col-lg-12 mt-3 width-100">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="mb-0 fw-bold">My Attendance</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Class</th>
                                                <th>Faculty Name</th>
                                                <th>Room</th>
                                                <th>Time In</th>
                                                <th>Time Out</th>
                                                <th>Course</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="my-attendance-body">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($myAttendance ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <?php $schedule = $attendance->schedule; ?>
        <tr>
            <td><?php echo e($schedule?->course?->course_code ?? 'N/A'); ?></td>
            <td><?php echo e(trim(($schedule?->user?->first_name ?? '') . ' ' . ($schedule?->user?->last_name ?? '')) ?: 'N/A'); ?></td>
            <td><?php echo e($schedule?->room?->room_name ?? 'N/A'); ?></td>
            <td>
                <?php echo e($attendance->time_in ? \Illuminate\Support\Carbon::parse($attendance->time_in)->format('g:i A') : '-'); ?>

            </td>
            <td>
                <?php echo e($attendance->time_out ? \Illuminate\Support\Carbon::parse($attendance->time_out)->format('g:i A') : '-'); ?>

            </td>
            <td><?php echo e($schedule?->course?->course_name ?? 'N/A'); ?></td>
            <td>
                <span class="badge <?php echo e(in_array($attendance->status, ['attended', 'present']) ? 'bg-success' : ($attendance->status === 'absent' ? 'bg-danger' : 'bg-warning')); ?>">
                    <?php echo e(ucfirst(str_replace('_', ' ', $attendance->status ?? 'N/A'))); ?>

                </span>
            </td>
        </tr>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <tr class="table-active">
            <td colspan="7" class="text-center py-4 text-muted">No attendance records found for today.</td>
        </tr>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
            </div>                
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

               

                    <script>
    let lastHandledUid = null;

    function formatAttendanceTime(value) {
        if (!value) return '-';
        const [hours, minutes] = value.split(':');
        const date = new Date(2000, 0, 1, Number(hours), Number(minutes));
        return date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
    }

    function renderAttendance(records) {
        const body = document.getElementById('my-attendance-body');
        if (!body) return;

        body.innerHTML = records.length ? records.map(record => {
            const status = record.status || 'waiting';
            const badgeClass = ['attended', 'present'].includes(status)
                ? 'bg-success'
                : status === 'absent' ? 'bg-danger' : 'bg-warning';

            return `<tr>
                <td>${record.class}</td>
                <td>${record.faculty}</td>
                <td>${record.room}</td>
                <td>${formatAttendanceTime(record.time_in)}</td>
                <td>${formatAttendanceTime(record.time_out)}</td>
                <td>${record.course}</td>
                <td><span class="badge ${badgeClass}">${status.replaceAll('_', ' ').replace(/^\\w/, letter => letter.toUpperCase())}</span></td>
            </tr>`;
        }).join('') : `<tr class="table-active">
            <td colspan="7" class="text-center py-4 text-muted">No attendance records found for today.</td>
        </tr>`;
    }

    async function refreshAttendanceTable() {
        const response = await fetch('<?php echo e(route('dashboard.attendance')); ?>', { headers: { Accept: 'application/json' } });
        if (response.ok) {
            const data = await response.json();
            renderAttendance(data.attendance || []);
        }
    }

    refreshAttendanceTable().catch(err => console.error('Attendance refresh error:', err));
    setInterval(() => refreshAttendanceTable().catch(err => console.error('Attendance refresh error:', err)), 2000);

    setInterval(async () => {
        try {
            const response = await fetch('/api/check-latest-attendance');
            const data = await response.json();

            if (data.scan_data && data.uid !== lastHandledUid) {
                const scan = data.scan_data;

                if (scan.status === 'accepted') {
                    lastHandledUid = data.uid;
                    refreshAttendanceTable().catch(err => console.error('Attendance refresh error:', err));

                    // Locate table row by attendance ID or User ID
                    const statusBadge = document.querySelector(`#status-badge-${scan.attendance_id}`) 
                                     || document.querySelector(`#status-user-${scan.user.id}`);
                    const timeInCell = document.querySelector(`#time-in-${scan.attendance_id}`);
                    const timeOutCell = document.querySelector(`#time-out-${scan.attendance_id}`);

                    if (statusBadge) {
                        statusBadge.textContent = scan.status_in.toUpperCase();

                        // Set badge colors according to enum value
                        let badgeClass = 'badge ';
                        switch(scan.status_in) {
                            case 'attended': badgeClass += 'bg-success'; break;
                            case 'late': badgeClass += 'bg-warning text-dark'; break;
                            case 'on_leave': badgeClass += 'bg-info text-dark'; break;
                            case 'absent': badgeClass += 'bg-danger'; break;
                            default: badgeClass += 'bg-secondary';
                        }
                        statusBadge.className = badgeClass;
                    }

                    if (timeInCell && scan.time_in) {
                        timeInCell.textContent = scan.time_in;
                    }
                    if (timeOutCell && scan.time_out) {
                        timeOutCell.textContent = scan.time_out;
                    }
                }
            }
        } catch (err) {
            console.error('Scan polling error:', err);
        }
    }, 2000);
</script>
            </div>
        </div>
    </main>
    <?php echo $__env->make('partials.notifications-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>





<?php /**PATH /Volumes/shared/capstone project/backups/emonitor 3rd phase copy/resources/views/dashboard.blade.php ENDPATH**/ ?>