<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use App\Models\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
 
        $userId = session('user_id');
        $user = $userId ? User::query()
            ->select([
                'id',
                'first_name',
                'middle_name',
                'last_name',
                'employee_ID',
                'role',
                'college_id',
                'profile_picture_data',
                'profile_picture_mime',
            ])
            ->with('college')
            ->find($userId) : null;

        // Load requests for this user (for the profile display)
        $requests = $userId ? \Illuminate\Support\Facades\DB::table('requests')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get() : collect();

             $sessionStatus = session('user_id');
        $AccStatus = User::whereKey($sessionStatus)->value('acc_status');
        
        
        return view('profile', compact('user', 'requests', 'AccStatus'));
    }

    public function update(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $userId = session('user_id');
        $user = User::findOrFail($userId);

        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $file = $request->file('profile_picture');
        $user->profile_picture_data = base64_encode($file->get());
        $user->profile_picture_mime = $file->getMimeType();
        $user->profile_picture = null;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile picture updated successfully!');
    }

    public function picture(User $user)
    {
        if (!session('logged_in')) {
            return response('', 401);
        }

        $mime = $user->profile_picture_mime;
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            abort(404);
        }

        $image = base64_decode((string) $user->profile_picture_data, true);
        if ($image === false || $image === '') {
            Log::warning('Profile picture data is missing or invalid.', ['user_id' => $user->id]);
            abort(404);
        }

        return response($image, 200, [
            'Content-Type' => $mime,
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function updateName(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $user = User::findOrFail(session('user_id'));

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'employee_ID' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'employee_ID')->ignore($user->id),
            ],
        ]);

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    public function showStatus()
    {

    }
}
