<?php

use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\collegecontroller;
use App\Http\Controllers\coursecontroller;
use App\Http\Controllers\ProgramController;


Route::get('/', function () {
    return view('login');
});

Route::post('/', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/rooms', function () {
    return view('rooms');
})->name('rooms.index');



// {{-- 1=admin 2=dean 3=asst. dean 4=faculty 5=programhead --}}
Route::get('/dashboard', function () {
    // 1=admin 2=dean 3=asst. dean 4=faculty 5=programhead
    if (!session('logged_in') || !in_array((int) session('user_role'), [1, 2, 3, 4, 5], true)) {
        return redirect('/');
    }


    $recent_faculty = \App\Models\users::whereIn('role', [2, 3, 4, 5])
        ->latest('created_at')
        ->take(5)
        ->get();

    // Count ACTIVE faculty-related accounts: Dean(2), Assistant Dean(3), Faculty(4), Program Head(5)
    $faculty_count = \App\Models\users::whereIn('role', [2, 3, 4, 5])->where('acc_status', 1)->count();
    $pending_count = \App\Models\users::where('acc_status', 0)->count();

    $userRole = (int) (session('user_role') ?? 0);

    // Leave requests are only visible to Dean (2) and Assistant Dean (3)
    $leave_requests_by_faculty = collect();
    if (in_array($userRole, [2, 3], true)) {
        $requests = \Illuminate\Support\Facades\DB::table('requests')
            ->join('users', 'users.id', '=', 'requests.user_request')
            ->whereIn('users.role', [4, 5]) // faculty + program head accounts request letters
            ->select(
                'users.first_name',
                'users.last_name',
                'users.id as user_id',
                'requests.letter',
                'requests.reason',
                'requests.created_at'
            )
            ->orderByDesc('requests.created_at')
            ->get();


        // Group by faculty user id
        $leave_requests_by_faculty = $requests->groupBy('user_id');
    }

    $role_name = match ($userRole) {
        1 => 'Admin',
        2 => 'Dean',
        3 => 'Assistant Dean',
        4 => 'Faculty',
        5 => 'Program Head',
        default => 'Unknown',
    };

    return view('dashboard', compact(
        'recent_faculty',
        'role_name',
        'faculty_count',
        'pending_count',
        'leave_requests_by_faculty'
    ));
})->name('dashboard');

Route::get('/college', function(){
    return view('college');

})->name('college');


Route::get('/schedules', [App\Http\Controllers\schedulecontroller::class, 'index'])->name('schedules');

Route::get('/myschedule', function () {
    if (!session('logged_in') || !in_array((int) session('user_role'), [2, 3, 4, 5], true)) {
        return redirect('/dashboard');
    }

    $user_id = session('user_id');
    $current_user = \App\Models\User::find($user_id);
    $schedules = \App\Models\Schedule::where('user_id', $user_id)->get();
    $Programs = \App\Models\Programs::all();
    // $course = \App\Models\course::all();

    return view('myschedule', compact('current_user', 'schedules', 'Programs'));
})->name('myschedule');

Route::get('/schedtime', function () {
    return view('schedtime');
})->name('schedtime');

Route::post('/myschedule/store', function (Illuminate\Http\Request $request) {
    if (!session('logged_in') || !in_array((int) session('user_role'), [2, 3, 4, 5], true)) {
        return redirect('/dashboard');
    }

    $id = Str::uuid()->toString();

    $validatedData = $request->validate([
        'user_id' => 'required|exists:users,id',
        'course_id' => 'nullable|exists:course,id',
        'year_level' => 'nullable|string',
        'section' => 'nullable|string',
        'Day' => 'required',
        'Time' => 'required',
        'course' => 'required',
        'Room' => 'required',
        'Semester' => 'required',
        'School_year' => 'required',
    ]);

    $data = [
        'id' => $id,
        'user_id' => session('user_id'),
        'Programs' => $request->year_level ?? '',
        'Year_level' => $request->year_level ?? '',
        'Section' => $request->section ?? '',
        'Day' => $validatedData['Day'],
        'Time' => $validatedData['Time'],
        'course' => $validatedData['course'],
        'Room' => $validatedData['Room'],
        'Semester' => $validatedData['Semester'],
        'School_year' => $validatedData['School_year'],
    ];

    \App\Models\Schedule::insert($data);

    return redirect('/myschedule')->with('success', 'Schedule added successfully!');
})->name('myschedule.store');

Route::get('school-year-settings', function () {
    return view('partials.school-year-settings');
})->name('school-year-settings');
Route::post('school-year-settings', function (Illuminate\Http\Request $request) {
    $validatedData = $request->validate([
        'school_year' => 'required|string',
        'semester' => 'required|string',
    ]);

    // Save the school year and semester to the database or perform any other necessary actions
    // For example, you can create a new Semester record
    \App\Models\semyr::create([
        'Semester' => $validatedData['semester'],
        'School_year' => $validatedData['school_year'],
    ]);

    return redirect()->back()->with('success', 'School year and semester settings updated successfully!');
})->name('school-year-settings.update');

// NOTE: /programs and /course are handled by controllers below.
// Keep only one route definition per path/name to avoid rendering the wrong page.
Route::get('/course', function () {
    return view('course');
})->name('course');

Route::get('/settings/school-year', [App\Http\Controllers\semyrController::class, 'schoolYearSettings'])->name('settings.school_year');
Route::post('/settings/change-school-year', [App\Http\Controllers\semyrController::class, 'store'])->name('settings.store_school_year');   
 
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/request', [App\Http\Controllers\ProfileController::class, 'storeRequest'])->name('profile.request.store');



Route::post('/schedules/store', [App\Http\Controllers\schedulecontroller::class, 'store'])->name('schedules.store');
Route::put('/schedules/{id}', [App\Http\Controllers\schedulecontroller::class, 'update'])->name('schedules.update');
Route::delete('/schedules/{id}', [App\Http\Controllers\schedulecontroller::class, 'destroy'])->name('schedules.destroy');

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


Route::post('/rooms/store', [App\Http\Controllers\room_bldg_controller::class, 'storeRoom'])->name('storeRoom.store');
Route::post('/buildings/store', [App\Http\Controllers\room_bldg_controller::class, 'storeBldg'])->name('storeBldg.store');
