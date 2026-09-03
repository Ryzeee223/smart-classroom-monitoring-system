<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Schedule;
use App\Models\semyr;
use Illuminate\Support\Facades\Hash;
use App\Models\Programs;
use App\Models\College;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;


class AdminController extends Controller
{
    public function index()
    {
        // Admin-level users can manage all account roles.
        // Role mapping: 1=Admin, 2=Dean, 3=Assistant Dean, 4=Faculty, 5=Program Head

        $sessionRole = (int) (session('user_role') ?? 0);
        $collegeId = session('college_id');
        if (!$collegeId && session('user_id')) {
            $collegeId = User::query()->where('id', session('user_id'))->value('college_id');
        }

        $account_usersQuery = User::whereIn('role', [1, 2, 3, 4, 5]);
        $faculty_usersQuery = User::whereIn('role', [2, 3, 4, 5]);

        // Non-admins should only see users from their own college
        if ($sessionRole !== 1 && $collegeId) {
            $account_usersQuery->where('college_id', $collegeId);
            $faculty_usersQuery->where('college_id', $collegeId);
        }

        $account_users = $account_usersQuery->get();
        $faculty_users = $faculty_usersQuery->get();

        $programs = Programs::query()->from('programs')->get();

        $colleges = \App\Models\College::query()->select(['id','college_name','abbreviation','description'])->get();

        return view('users', compact('account_users', 'faculty_users', 'programs', 'colleges'));
    }




public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'employee_ID' => 'required|string|max:255|unique:users,employee_ID',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|integer',
            'college_id' => 'required|exists:college,id',
        ]);



        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'employee_ID' => $request->employee_ID,
            'role' => $request->role,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'college_id' => $request->college_id,
            'profile_picture' => null,
            'RFID_code' => null,
            'acc_status' => 'Present',
        ]);

        return back()->with('success', 'User saved successfully!');
    }

    public function settings()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
        $users = User::all();
        return view('settings', compact('users'));
    }

    public function resetPassword(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        // Admin-only
        if ((int) (session('user_role') ?? 0) !== 1) {
            return redirect('/dashboard')->withErrors(['unauthorized' => 'Only admin can reset password.']);
        }


        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $userId = session('user_id');
        $user = User::findOrFail($userId);

        // current_password check
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Replace existing password with bcrypt hash
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function resetAnyUserPassword(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        // Admin-only
        if ((int) (session('user_role') ?? 0) !== 1) {
            return redirect('/dashboard')->withErrors(['unauthorized' => 'Only admin can reset user passwords.']);
        }



        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'password' => 'required|string|min:8',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return back()->with('success', 'User password updated successfully!');
    }



// public function assignRfid(Request $request)
//     {
//         $request->validate([
//             'user_id' => 'required|exists:users,id',
//             'rfid_code' => 'required|string|max:50|unique:users,RFID_code',
//         ]);

//         $user = User::findOrFail($request->user_id);
//         $user->update(['RFID_code' => $request->rfid_code]);

//         return back()->with('success', 'RFID assigned successfully!');
//     }

public function assignRfid(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'rfid_code' => 'required|string',
    ]);

$existingUser = User::where('RFID_code')
        ->where('id', '!=', $request->user_id)
        ->first();

    if ($existingUser) {
        return redirect()->back()->with('error', "This RFID card is already assigned.");
    }

    $user = User::findOrFail($request->user_id);
    $user->update(['RFID_code' => strtoupper(trim($request->rfid_code))]);

    // Clear cache so it doesn't leak into subsequent polls
    Cache::forget('latest_assignment_scan');
    Cache::forget('latest_assignment_scan_data');

    return redirect()->back()->with('success', 'RFID assigned successfully.');
}

    public function dashboard()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
        $user_id = session('user_id');
        $current_user = User::find($user_id);
        $role_name = $current_user ? $this->getRoleName($current_user->role) : 'Unknown';
        $recent_faculty = User::where('role', [2,3,4,5])->latest('created_at')->take(5)->get();
        $faculty_count = User::where('role', [2,3,4,5])->where('acc_status', 1)->count();
        // Pending accounts (acc_status=0) for faculty-related roles only: 2,3,4,5 (exclude admin role 1)
        $pending_count = User::whereIn('role', [2, 3, 4, 5])->where('rfid_code', 0)->count();
        
        // Fetch ongoing classes
        $now = Carbon::now();
        $todayDay = $now->translatedFormat('D');
        
        $schedules = Schedule::with(['User', 'course', 'room', 'Program'])
            ->where('day', $todayDay)
            ->whereTime('start_time', '>=', '07:00:00')
            ->whereTime('start_time', '<=', '18:00:00')
            ->orderBy('start_time', 'asc')
            ->get();
        
        $ongoingClasses = $schedules->map(function ($schedule) use ($now) {
            $startDateTime = Carbon::today()->setTimeFromTimeString($schedule->start_time);
            $endDateTime = Carbon::today()->setTimeFromTimeString($schedule->end_time);
            
            if ($endDateTime->lt($startDateTime)) {
                $endDateTime->addDay();
            }
            
            $isLive = $now->between($startDateTime, $endDateTime, true);
            
            return [
                'id' => $schedule->id,
                'faculty' => trim(($schedule->User->first_name ?? '') . ' ' . ($schedule->User->last_name ?? '')) ?: 'Faculty',
                'course_code' => $schedule->course?->course_code ?? 'N/A',
                'subject' => $schedule->course?->course_name ?? 'N/A',
                'room' => $schedule->room?->room_name ?? 'N/A',
                'start' => $startDateTime->format('H:i:s'),
                'end' => $endDateTime->format('H:i:s'),
                'start_display' => $startDateTime->format('g:i A'),
                'end_display' => $endDateTime->format('g:i A'),
                'is_live' => $isLive,
                'status' => $isLive ? 'Ongoing' : 'Scheduled',
            ];
        })->values();
        
        return view('dashboard', compact('recent_faculty', 'role_name', 'faculty_count', 'pending_count', 'ongoingClasses'));

    }



    
    private function getRoleName($role)
    {
        switch($role) {
            case 1:
                return 'Admin';
            case 2:
                return 'Dean';
            case 3:
                return 'Assistant Dean';
            case 4:
                return 'Faculty';
            case 5:
                return 'Program Head';
            default:
                return 'Unknown';
        }
    }
}
