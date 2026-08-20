<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - eMonitor</title>
    <link href="<?php echo e(asset('bootstrap-5.3.8-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js')); ?>"></script>

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

            <div class="container-fluid dashboard-container px-4">
                
                <div class="mb-2 d-flex justify-content-end " style="radius:10px;">
            <button type="button"
                    class="btn btn-sm btn-light shadow-sm rounded-circle d-flex align-items-center justify-content-center "
                    style="z-index: 10;"
                    aria-label="Open notifications"
                    data-bs-toggle="modal"
                    data-bs-target="#notificationsModal">
                <span aria-hidden="true">&#128276;</span>
             </button>
                     </div>
            <div class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">

            <div class="col">
                <div class="card stats-card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Rooms</h6>
                        <div class="display-4 fw-bold text-primary mb-0"><?php echo e($showrm ?? $roomnm ?? 0); ?></div>

                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card stats-card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Occupied</h6>
                        <div class="display-4 fw-bold text-success mb-0">0</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card stats-card h-100 shadow-sm">

                    <div class="card-body d-flex flex-column align-items-center text-center p-4">
                        <h6 class="text-muted mb-2">Faculty</h6>
                        
                        <div class="display-4 fw-bold text-info mb-0"><?php echo e($faculty_count ?? 0); ?></div>
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
                                            class="nav-link building-tab <?php echo e($bIndex === 0 ? 'active' : ''); ?>"
                                            type="button"
                                            data-building="<?php echo e($abbr); ?>"
                                            data-building-name="<?php echo e($name); ?>"
                                        >
                                            <?php echo e($abbr); ?>

                                        </button>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <?php else: ?>
                                    <button class="nav-link active building-tab" type="button" data-building="A">N/A</button>
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
                                                <span class="badge bg-success rounded-pill px-2 py-1 fs-6">Vacant</span>
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
                $viewerRole = (int) (session('user_role') ?? 0);
            ?>



            <div class="row g-3 mb-5">
            <!-- My Attendance -->
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
                                                <th>Course</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = ($myAttendance ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <?php $schedule = $attendance->schedule; ?>
                                                <tr>
                                                    <td><?php echo e($schedule?->course?->course_code ?? 'N/A'); ?></td>
                                                    <td><?php echo e(trim(($schedule?->user?->first_name ?? '') . ' ' . ($schedule?->user?->last_name ?? '')) ?: 'N/A'); ?></td>
                                                    <td><?php echo e($schedule?->room?->room_name ?? 'N/A'); ?></td>
                                                    <td><?php echo e($attendance->time_in ?? '-'); ?></td>
                                                    <td><?php echo e($schedule?->course?->course_name ?? 'N/A'); ?></td>
                                                    <td><?php echo e(ucfirst(str_replace('_', ' ', $attendance->status_in ?? 'N/A'))); ?></td>
                                                </tr>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                <tr class="table-active">
                                                    <td colspan="6" class="text-center py-4 text-muted">No attendance records found.</td>
                                                </tr>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </main>
</body>
</html>





<?php /**PATH /Volumes/shared/capstone project/backups/emonitor 3rd phase copy/resources/views/dashboard.blade.php ENDPATH**/ ?>