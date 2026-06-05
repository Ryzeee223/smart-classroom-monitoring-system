<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Course;


class AdminController extends Controller
{
    public function index()
    {
        // Admin-level users can manage all account roles.
        // Role mapping: 1=Admin, 2=Dean, 3=Assistant Dean, 4=Faculty, 5=Program Head
        $account_users = User::whereIn('role', [1,2,3,4,5])->get();
        $faculty_users = User::where('role', [2,3,4,5])->get();
        // Your migrations create the table as `courses`.
        // The model (Course) points to `courses`, so we override the base table here.
        $courses = Course::query()->from('courses')->get();

        $colleges = \App\Models\college::query()->select(['id','college_name','abbreviation','description','user_id'])->get();

        return view('users', compact('account_users', 'faculty_users', 'courses', 'colleges'));
    }




    public function store(Request $request)

    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_ID' => 'required|string|unique:users,employee_ID',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|integer',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'employee_ID' => $request->employee_ID,
            'role' => $request->role,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'college' => '',
            'profile_picture' => null,
            'RFID_code' => null,
            'acc_status' => 1,
            'status' => 0,
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



    public function assignRfid(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rfid_code' => 'required|string|max:255|unique:users,RFID_code',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['RFID_code' => $request->rfid_code]);

        return back()->with('success', 'RFID assigned successfully!');
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
        return view('dashboard', compact('recent_faculty', 'role_name', 'faculty_count', 'pending_count'));

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
