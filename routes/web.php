<?php

use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\collegecontroller;


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
    $current_user = \App\Models\users::find($user_id);
    $schedules = \App\Models\Schedule::where('user_id', $user_id)->get();
    $courses = \App\Models\Course::all();
    $subjects = \App\Models\Subject::all();

    return view('myschedule', compact('current_user', 'schedules', 'courses', 'subjects'));
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
        'course_id' => 'nullable|exists:courses,id',
        'year_level' => 'nullable|string',
        'section' => 'nullable|string',
        'Day' => 'required',
        'Time' => 'required',
        'Subject' => 'required',
        'Room' => 'required',
        'Semester' => 'required',
        'School_year' => 'required',
    ]);

    $data = [
        'id' => $id,
        'user_id' => session('user_id'),
        'Course' => $request->year_level ?? '',
        'Year_level' => $request->year_level ?? '',
        'Section' => $request->section ?? '',
        'Day' => $validatedData['Day'],
        'Time' => $validatedData['Time'],
        'Subject' => $validatedData['Subject'],
        'Room' => $validatedData['Room'],
        'Semester' => $validatedData['Semester'],
        'School_year' => $validatedData['School_year'],
    ];

    \App\Models\Schedule::insert($data);

    return redirect('/myschedule')->with('success', 'Schedule added successfully!');
})->name('myschedule.store');


Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/request', [App\Http\Controllers\ProfileController::class, 'storeRequest'])->name('profile.request.store');



Route::post('/schedules/store', [App\Http\Controllers\schedulecontroller::class, 'store'])->name('schedules.store');
Route::put('/schedules/{id}', [App\Http\Controllers\schedulecontroller::class, 'update'])->name('schedules.update');
Route::delete('/schedules/{id}', [App\Http\Controllers\schedulecontroller::class, 'destroy'])->name('schedules.destroy');

Route::get('/subjects', [\App\Http\Controllers\subjectcontroller::class, 'index'])->name('subjects');
Route::post('/subjects', [\App\Http\Controllers\subjectcontroller::class, 'store'])->name('subjects.store');
Route::get('/subjects/{subject}/edit', [\App\Http\Controllers\subjectcontroller::class, 'edit'])->name('subjects.edit');
Route::put('/subjects/{subject}', [\App\Http\Controllers\subjectcontroller::class, 'update'])->name('subjects.update');
Route::delete('/subjects/{subject}', [\App\Http\Controllers\subjectcontroller::class, 'destroy'])->name('subjects.destroy');

Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
Route::post('/settings/assign_rfid', [App\Http\Controllers\AdminController::class, 'assignRfid'])->name('settings.assign_rfid');
Route::post('/settings/reset_password', [App\Http\Controllers\AdminController::class, 'resetPassword'])->name('settings.reset_password');
Route::post('/settings/reset_user_password', [App\Http\Controllers\AdminController::class, 'resetAnyUserPassword'])->name('settings.reset_user_password');



Route::get('/users', [App\Http\Controllers\AdminController::class, 'index'])->name('users.index');
Route::post('/users', [App\Http\Controllers\AdminController::class, 'store'])->name('users.store');

Route::get('/users/{id}/edit', [App\Http\Controllers\userscontroller::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [App\Http\Controllers\userscontroller::class, 'update'])->name('users.update');

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


