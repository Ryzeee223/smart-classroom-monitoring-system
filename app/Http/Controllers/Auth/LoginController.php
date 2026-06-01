<?php

namespace App\Http\Controllers\Auth;

use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
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

        Session::put('logged_in', true);
        Session::put('user_id', $user->id);
        Session::put('user_role', (int) ($user->role ?? 0));

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

    public function logout()
    {
        Session::flush();
        return redirect('/')->with('success', 'Logged out successfully!');
    }
}

