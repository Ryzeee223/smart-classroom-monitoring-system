<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $userId = session('user_id');
        $user = $userId ? User::find($userId) : null;

        // Load requests for this user (for the profile display)
        $requests = $userId ? \Illuminate\Support\Facades\DB::table('requests')
            ->where('user_request', $userId)
            ->orderByDesc('created_at')
            ->get() : collect();

        return view('profile', compact('user', 'requests'));
    }

    public function update(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $userId = session('user_id');
        $user = User::findOrFail($userId);

        // Normalize profile_picture: if you have older values that store only filename,
        // convert them to the path format used by the uploader.
        // (Expected: profile_pictures/<filename>.jpg)
        if ($user->profile_picture && !str_contains($user->profile_picture, 'profile_pictures/')) {
            // If it looks like a filename only, prefix it.
            // This keeps existing uploads working.
            $user->profile_picture = 'profile_pictures/' . ltrim($user->profile_picture, '/');
        }

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $file = $request->file('profile_picture');

        // Store in storage/app/public so it can be served via /storage
        // (then we show it with asset('storage/' . $user->profile_picture))
        $path = $file->store('profile_pictures', 'public');


        // Optionally delete old file (if you want)
        if ($user->profile_picture) {
            // profile_picture stores relative path like profile_pictures/xyz.jpg
            try {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_picture);
            } catch (\Throwable $e) {
                // ignore
            }
        }

        $user->update([
            'profile_picture' => $path,
        ]);

        return redirect()->route('profile')->with('success', 'Profile photo updated successfully!');
    }

    public function storeRequest(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $userId = session('user_id');
        $userRole = (int) (session('user_role') ?? 0);

        // Only roles 2,3,4,5 can submit requests
        if (!in_array($userRole, [2, 3, 4, 5], true)) {
            return redirect()->route('profile')->with('success', 'You are not allowed to submit a request.');
        }

        $validated = $request->validate([
            'letter' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
        ]);

        // Request table name is `Request` (capital R)
        // Store request_id (custom) into the auto table.
        $requestId = random_int(1_000_000, 9_999_999);

        \Illuminate\Support\Facades\DB::table('requests')->insert([
            'request_id' => $requestId,
            'letter' => $validated['letter'],
            'reason' => $validated['reason'],
            'user_request' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('profile')->with('success', 'Request submitted successfully!');
    }
}


