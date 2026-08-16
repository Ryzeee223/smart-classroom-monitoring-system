<?php

use App\Http\Controllers\RequestController;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;




Route::get('/', function () {
    return view('login');
});

Route::post('/', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// {{-- 1=admin 2=dean 3=asst. dean 4=faculty 5=programhead --}}
Route::get('/dashboard', function () {
    // 1=admin 2=dean 3=asst. dean 4=faculty 5=programhead
    if (!session('logged_in') || !in_array((int) session('user_role'), [1, 2, 3, 4, 5], true)) {
        return redirect('/');
    }


    $userRole = (int) (session('user_role') ?? 0);
    $currentUserId = session('user_id') ?? auth()->id();
    $currentUser = \App\Models\users::find($currentUserId);
    $userCollegeId = $currentUser ? (int) $currentUser->college_id : (int) (session('college_id') ?? 0);

    // Recent faculty and counts: if Dean/Asst Dean, scope to their college
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

    // Buildings and their rooms for the Live Classroom Status grid
    $buildings = \App\Models\bldg::all();
    $rooms = \App\Models\room::with('building')->get();

    return view('dashboard', compact(
        'recent_faculty',
        'role_name',
        'faculty_count',
        'pending_count',
        'req',
        'buildings',
        'rooms',
    ));
})->name('dashboard');

Route::get('/college', function(){
    return view('college');

})->name('college');

Route::get('/approve/{id?}', [App\Http\Controllers\RequestController::class, 'show'])->name('approval');

Route::get('/schedules', [App\Http\Controllers\schedulecontroller::class, 'index'])->name('schedules');

Route::get('/myschedule', function () {
    if (!session('logged_in') || !in_array((int) session('user_role'), [2, 3, 4, 5], true)) {
        return redirect('/dashboard');
    }

    $user_id = session('user_id');
    $current_user = \App\Models\User::find($user_id);
    $schedules = \App\Models\Schedule::where('user_id', $user_id)->get();
    $Programs = \App\Models\Programs::all();
    

    return view('myschedule', compact('current_user', 'schedules', 'Programs'));
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

Route::get('/programs', function(){
    return view('programs');
})->name('programs');

Route::get('/programs', [\App\Http\Controllers\ProgramController::class, 'index'])->name('program');
Route::post('/programs', [\App\Http\Controllers\ProgramController::class, 'store'])->name('programs.store');
Route::get('/programs/{programs}/edit', [\App\Http\Controllers\ProgramController::class, 'edit'])->name('program.edit');
Route::put('/programs/{programs}', [\App\Http\Controllers\ProgramController::class, 'update'])->name('program.update');
Route::delete('/programs/{programs}', [\App\Http\Controllers\ProgramController::class, 'destroy'])->name('program.destroy');

Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
Route::post('/settings/assign_rfid', [App\Http\Controllers\AdminController::class, 'assignRfid'])->name('settings.assign_rfid');
Route::post('/settings/reset_password', [App\Http\Controllers\AdminController::class, 'resetPassword'])->name('settings.reset_password');
Route::post('/settings/reset_user_password', [App\Http\Controllers\AdminController::class, 'resetAnyUserPassword'])->name('settings.reset_user_password');



Route::get('/users', [App\Http\Controllers\AdminController::class, 'index'])->name('users.index');
Route::post('/users', [App\Http\Controllers\AdminController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [App\Http\Controllers\userscontroller::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [App\Http\Controllers\userscontroller::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [App\Http\Controllers\userscontroller::class, 'destroy'])->name('users.destroy');

Route::get('/course', [App\Http\Controllers\courseController::class, 'index'])->name('course');
Route::post('/course', [App\Http\Controllers\courseController::class, 'store'])->name('course.store');
Route::get('/course/{course}/edit', [App\Http\Controllers\courseController::class, 'edit'])->name('course.edit');
Route::put('/course/{course}', [App\Http\Controllers\courseController::class, 'update'])->name('course.update');
Route::delete('/course/{course}', [App\Http\Controllers\courseController::class, 'destroy'])->name('course.destroy');

Route::get('/college', [App\Http\Controllers\collegecontroller::class, 'index'])->name('college');
Route::post('/college', [App\Http\Controllers\collegecontroller::class, 'store'])->name('college.store');

Route::get('/college/{college}/edit', [App\Http\Controllers\collegecontroller::class, 'edit'])->name('college.edit');
Route::put('/college/{college}', [App\Http\Controllers\collegecontroller::class, 'update'])->name('college.update');
Route::delete('/college/{college}', [App\Http\Controllers\collegecontroller::class, 'destroy'])->name('college.destroy');

Route::post('/rooms/', [App\Http\Controllers\room_bldg_controller::class, 'storeRoom'])->name('storeRoom.store');
Route::get('/rooms/', [App\Http\Controllers\room_bldg_controller::class, 'show'])->name('rooms.index');

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