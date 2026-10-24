<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = users::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put([
            'logged_in' => true,
            'user_id' => $user->id,
            'user_role' => (int) ($user->role ?? 0),
            'college_id' => $user->college_id ?? 0,
        ]);

        // Redirect based on role
        return match((int) ($user->role ?? 0)) {
            1 => redirect('/dashboard')->with('success', 'Logged in successfully!'),
            2 => redirect('/dashboard')->with('success', 'Logged in successfully!'),
            3 => redirect('/dashboard')->with('success', 'Logged in successfully!'),
            4 => redirect('/dashboard')->with('success', 'Logged in successfully!'),
            5 => redirect('/dashboard')->with('success', 'Logged in successfully!'),
            default => redirect('/')->withErrors(['email' => 'Unknown role.']),
        };
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
