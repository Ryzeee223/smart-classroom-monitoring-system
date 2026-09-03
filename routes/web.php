<?php

use App\Http\Controllers\RequestController;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Schedule;
use App\Models\semyr;
use App\Models\Report;
use App\Http\Controllers\ReportController;




Route::get('/', function () {
    return view('login');
});

Route::post('/', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// {{-- 1=admin 2=dean 3=asst. dean 4=faculty 5=Programhead --}}
Route::get('/dashboard', function () {
    if (!session('logged_in') || !in_array((int) session('user_role'), [1, 2, 3, 4, 5], true)) {
        return redirect('/');
    }

    $userRole = (int) (session('user_role') ?? 0);
    $currentUserId = session('user_id') ?? auth()->id();
    $currentUser = \App\Models\users::find($currentUserId);
    $userCollegeId = $currentUser ? (int) $currentUser->college_id : (int) (session('college_id') ?? 0);

    // Scope recent faculty and counts to the logged-in user's college when available
    if (in_array($userRole, [2, 3], true) && $userCollegeId > 0) {
        $recent_faculty = \App\Models\users::whereIn('role', [2, 3, 4, 5])
            ->where('college_id', $userCollegeId)
            ->latest('created_at')
            ->take(5)
            ->get();

        $faculty_count = \App\Models\users::whereIn('role', [2, 3, 4, 5])
            ->where('acc_status', 1)
            ->where('college_id', $userCollegeId)
            ->count();

        $pending_count = \App\Models\users::where('acc_status', 0)
            ->where('college_id', $userCollegeId)
            ->count();

        // Leave requests are only visible to Dean (2) and Assistant Dean (3) and scoped by college
        $requests = \Illuminate\Support\Facades\DB::table('requests')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->whereIn('users.role', [2, 3, 4, 5])
            ->where('users.college_id', $userCollegeId)
            ->where('requests.status', 'pending')
            ->select(
                'users.first_name',
                'users.last_name',
                'users.id as user_id',
                'users.role',
                'requests.id',
                'requests.letter',
                'requests.reason',
                'requests.status',
                'requests.created_at'
            )
            ->orderByDesc('requests.created_at')
            ->get();

        $req = $requests->groupBy('user_id');
    } else {
        $recent_faculty = \App\Models\users::whereIn('role', [2, 3, 4, 5])
            ->when($userCollegeId > 0, fn ($query) => $query->where('college_id', $userCollegeId))
            ->latest('created_at')
            ->take(5)
            ->get();

        $faculty_count = \App\Models\users::whereIn('role', [2, 3, 4, 5])->where('acc_status', 1)->count();
        $pending_count = \App\Models\users::where('acc_status', 0)->count();

        $req = collect();
    }

    $role_name = match ($userRole) {
        1 => 'Admin',
        2 => 'Dean',
        3 => 'Assistant Dean',
        4 => 'Faculty',
        5 => 'Program Head',
        default => 'Unknown',
    };

// 1. Current Date/Time Setup
$now = \Illuminate\Support\Carbon::now();
$todayDate = $now->toDateString();
$todayFull = $now->format('l'); // e.g., "Monday"
$todayShort = $now->format('D'); // e.g., "Mon"

// 2. Fetch Static UI Dropdowns
$buildings = \App\Models\bldg::all();
$rooms = \App\Models\room::with('building')->get();
$currentDeans = \App\Models\users::where('role', 2)
    ->when($userCollegeId > 0, fn ($q) => $q->where('college_id', $userCollegeId))
    ->orderBy('last_name')
    ->orderBy('first_name')
    ->get();

// 3. Retrieve Base Schedules for Today
$schedulesQuery = \App\Models\Schedule::with(['User', 'course', 'room', 'Program'])
    ->where(function ($q) use ($todayFull, $todayShort) {
        $q->whereRaw('LOWER(day) LIKE ?', ['%' . strtolower($todayFull) . '%'])
          ->orWhereRaw('LOWER(day) LIKE ?', ['%' . strtolower($todayShort) . '%']);
    })
    ->orderBy('start_time', 'asc');

// Role-based scope for today's schedules
if (in_array($userRole, [2, 3], true)) {
    $facultyIds = \App\Models\users::where('college_id', $userCollegeId)->pluck('id');
    $schedulesQuery->whereIn('user_id', $facultyIds);
} elseif (in_array($userRole, [4, 5], true)) {
    $schedulesQuery->where('user_id', $currentUserId);
}

$todaySchedules = $schedulesQuery->get();

// 4. Batch Auto-Create Missing Attendance (Reports) for Today
if (in_array($userRole, [2, 3, 4, 5], true) && $todaySchedules->isNotEmpty()) {
    $nowTimestamp = now();

    $attendanceData = $todaySchedules->map(function ($schedule) use ($todayDate, $nowTimestamp) {
        return [
            'user_id'         => $schedule->user_id,
            'schedule_id'     => $schedule->id,
            'attendance_date' => $todayDate,
            'room_id'         => $schedule->room_id,
            'day'             => $schedule->day,
            'status'          => 'waiting',
            'created_at'      => $nowTimestamp,
            'updated_at'      => $nowTimestamp,
        ];
    })->toArray();

    Report::upsert(
        $attendanceData,
        ['user_id', 'schedule_id', 'attendance_date'],[] 
        // Empty array ensures existing records (e.g. marked attendance) are untouched
    );
}

// 5. Transform Schedules for Live Grid & Auto-Mark Late Absences
$attendanceClasses = $todaySchedules
    ->whereBetween('start_time', ['07:00:00', '18:00:00'])
    ->map(function ($schedule) use ($now, $todayDate) {
        $start = \Illuminate\Support\Carbon::parse("{$todayDate} {$schedule->start_time}");
        $end = \Illuminate\Support\Carbon::parse("{$todayDate} {$schedule->end_time}");
        if ($end->lt($start)) $end->addDay();

        $attendance = Report::syncForSchedule($schedule, $todayDate, $now);

        // Ended classes are synchronized above but are not shown in the live grid.
        if ($end->lt($now)) return null;

        return [
            'id' => $schedule->id,
            'faculty' => trim(($schedule->User?->first_name ?? '') . ' ' . ($schedule->User?->last_name ?? '')) ?: 'Faculty',
            'course_code' => $schedule->course?->course_code ?? 'N/A',
            'subject' => $schedule->course?->course_name ?? 'N/A',
            'room' => $schedule->room?->room_name ?? 'N/A',
            'start' => $start->format('H:i:s'),
            'end' => $end->format('H:i:s'),
            'start_display' => $start->format('g:i A'),
            'end_display' => $end->format('g:i A'),
            'is_live' => $now->between($start, $end, true),
            'status' => $attendance?->status ?? 'waiting',
            'time_in' => $attendance?->time_in,
        ];
    })->filter()->values();

// 6. Final Attendance for Current User
$myAttendance = Report::with(['schedule.user', 'schedule.course', 'schedule.room'])
    ->whereDate('attendance_date', $todayDate)
    ->where('user_id', $currentUserId)
    ->get()
    ->sortBy(fn ($a) => $a->schedule?->start_time ?? '23:59:59')
    ->values();

return view('dashboard', compact(
    'recent_faculty', 'role_name', 'faculty_count', 'pending_count',
    'req', 'buildings', 'rooms', 'currentDeans', 'attendanceClasses', 'myAttendance'
));
});

Route::get('/dashboard/attendance', function () {
    $userId = session('user_id') ?? auth()->id();

    if (!$userId) {
        return response()->json(['attendance' => []], 401);
    }

    $today = now();
    $todayDay = $today->format('l');
    $schedules = \App\Models\Schedule::with('User')
        ->where('user_id', $userId)
        ->where(function ($query) use ($todayDay) {
            $query->whereRaw('LOWER(day) LIKE ?', ['%' . strtolower($todayDay) . '%'])
                ->orWhereRaw('LOWER(day) LIKE ?', ['%' . strtolower(substr($todayDay, 0, 3)) . '%']);
        })
        ->get();

    foreach ($schedules as $schedule) {
        Report::syncForSchedule($schedule, $today->toDateString(), $today);
    }

    $attendance = Report::with(['schedule.user', 'schedule.course', 'schedule.room'])
        ->whereDate('attendance_date', now()->toDateString())
        ->where('user_id', $userId)
        ->get()
        ->sortBy(fn ($record) => $record->schedule?->start_time ?? '23:59:59')
        ->values()
        ->map(fn ($record) => [
            'id' => $record->id,
            'class' => $record->schedule?->course?->course_code ?? 'N/A',
            'faculty' => trim(($record->schedule?->user?->first_name ?? '') . ' ' . ($record->schedule?->user?->last_name ?? '')) ?: 'N/A',
            'room' => $record->schedule?->room?->room_name ?? 'N/A',
            'time_in' => $record->time_in,
            'time_out' => $record->time_out,
            'course' => $record->schedule?->course?->course_name ?? 'N/A',
            'status' => $record->status ?? 'waiting',
        ]);

    return response()->json(['attendance' => $attendance]);
})->name('dashboard.attendance');

Route::get('/college', function(){
    return view('college');

})->name('college');

Route::get('/approve/{id?}', [App\Http\Controllers\RequestController::class, 'show'])->name('approval');
Route::get('/notifications/modal', [App\Http\Controllers\RequestController::class, 'show'])->name('notifications.modal');

Route::get('/schedules', [App\Http\Controllers\schedulecontroller::class, 'index'])->name('schedules');

Route::get('/myschedule', function () {
    if (!session('logged_in') || !in_array((int) session('user_role'), [2, 3, 4, 5], true)) {
        return redirect('/dashboard');
    }

    $user_id = session('user_id');
    $current_user = \App\Models\User::find($user_id);
    $schedules = \App\Models\Schedule::where('user_id', $user_id)->get();
    $programs = \App\Models\Programs::all();
    

    return view('myschedule', compact('current_user', 'schedules', 'programs'));
})->name('myschedule');



Route::get('school-year-settings', function () {
    return view('partials.school-year-settings');
})->name('school-year-settings');
Route::post('school-year-settings', function (Illuminate\Http\Request $request) {
    $validatedData = $request->validate([
        'school_year' => 'required|string',
        'semester' => 'required|string',
    ]);

    
    \App\Models\semyr::create([
        'Semester' => $validatedData['semester'],
        'School_year' => $validatedData['school_year'],
    ]);

    return redirect()->back()->with('success', 'School year and semester settings updated successfully!');
})->name('school-year-settings.update');


Route::get('/course', function () {
    return view('course');
})->name('course');



Route::get('/settings/school-year', [App\Http\Controllers\semyrController::class, 'schoolYearSettings'])->name('settings.school_year');
Route::post('/settings/change-school-year', [App\Http\Controllers\semyrController::class, 'store'])->name('settings.store_school_year');   


Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/request', [App\Http\Controllers\RequestController::class, 'storeRequest'])->name('profile.request.store');


Route::post('/schedules/store', [App\Http\Controllers\schedulecontroller::class, 'store'])->name('schedules.store');
Route::put('/schedules/{id}', [App\Http\Controllers\schedulecontroller::class, 'update'])->name('schedules.update');
Route::delete('/schedules/{id}', [App\Http\Controllers\schedulecontroller::class, 'destroy'])->name('schedules.destroy');

// Conflict checker (booking system)
Route::post('/schedules/bookingsystem', [App\Http\Controllers\schedulecontroller::class, 'bookingsystem'])->name('schedules.bookingsystem');

Route::get('/programs', [\App\Http\Controllers\ProgramController::class, 'index'])->name('programs');
Route::post('/programs', [\App\Http\Controllers\ProgramController::class, 'store'])->name('programs.store');
Route::get('/programs/{programs}/edit', [\App\Http\Controllers\ProgramController::class, 'edit'])->name('programs.edit');
Route::put('/programs/{programs}', [\App\Http\Controllers\ProgramController::class, 'update'])->name('programs.update');
Route::delete('/programs/{programs}', [\App\Http\Controllers\ProgramController::class, 'destroy'])->name('programs.destroy');

Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
Route::post('/settings/assign_rfid', [App\Http\Controllers\AdminController::class, 'assignRfid'])->name('settings.assign_rfid');
Route::post('/settings/reset_password', [App\Http\Controllers\AdminController::class, 'resetPassword'])->name('settings.reset_password');
Route::post('/settings/reset_user_password', [App\Http\Controllers\AdminController::class, 'resetAnyUserPassword'])->name('settings.reset_user_password');



Route::get('/users', [App\Http\Controllers\AdminController::class, 'index'])->name('users.index');
Route::post('/users', [App\Http\Controllers\AdminController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [App\Http\Controllers\userscontroller::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [App\Http\Controllers\userscontroller::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [App\Http\Controllers\userscontroller::class, 'destroy'])->name('users.destroy');

Route::get('/course', [App\Http\Controllers\CourseController::class, 'index'])->name('course');
Route::post('/course', [App\Http\Controllers\CourseController::class, 'store'])->name('course.store');
Route::get('/course/{course}/edit', [App\Http\Controllers\CourseController::class, 'edit'])->name('course.edit');
Route::put('/course/{course}', [App\Http\Controllers\CourseController::class, 'update'])->name('course.update');
Route::delete('/course/{course}', [App\Http\Controllers\CourseController::class, 'destroy'])->name('course.destroy');

Route::get('/college', [App\Http\Controllers\collegecontroller::class, 'index'])->name('college');
Route::post('/college', [App\Http\Controllers\collegecontroller::class, 'store'])->name('college.store');

Route::get('/college/{college}/edit', [App\Http\Controllers\collegecontroller::class, 'edit'])->name('college.edit');
Route::put('/college/{college}', [App\Http\Controllers\collegecontroller::class, 'update'])->name('college.update');
Route::delete('/college/{college}', [App\Http\Controllers\collegecontroller::class, 'destroy'])->name('college.destroy');

Route::post('/rooms/', [App\Http\Controllers\room_bldg_controller::class, 'storeRoom'])->name('storeRoom.store');
Route::get('/rooms/', [App\Http\Controllers\room_bldg_controller::class, 'show'])->name('rooms.index');
Route::get('/dashboard', [App\Http\Controllers\room_bldg_controller::class, 'display'])->name('dashboard');

Route::post('/rooms/store', [App\Http\Controllers\room_bldg_controller::class, 'storeBldg'])->name('storeBldg.store');
Route::get('/rooms/{id}', [App\Http\Controllers\room_bldg_controller::class, 'show']);
Route::delete('/rooms/{id}', [App\Http\Controllers\room_bldg_controller::class, 'destroy'])->name('building.destroy');;
Route::delete('/rooms/', [App\Http\Controllers\room_bldg_controller::class, 'explode'])->name('room.explode');

Route::post('/requests/approve/{id}', [App\Http\Controllers\RequestController::class, 'approval'])->name('requests.approve');
Route::post('/requests/decline/{id}', [App\Http\Controllers\RequestController::class, 'decline'])->name('requests.decline');
Route::get('/notifications-modal', [RequestController::class, 'show'])->name('request.show');

Route::get('/partials.approve',function() {
    return view('partials.approve');
})->name('approve');

Route::get('/partials.approve/{id}',[App\Http\Controllers\RequestController::class, 'showreason'])->name('showReason');
Route::get('/partials.notifications-modal/{id}',[App\Http\Controllers\RequestController::class, 'showreason'])->name('showReqReason');

Route::get('/reports', [ReportController::class, 'index'])->name('reports');

Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');