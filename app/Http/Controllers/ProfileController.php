<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use App\Models\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
class ProfileController extends Controller
{
    public function show()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
 
        $userId = session('user_id');
        $user = $userId ? User::with('college')->find($userId) : null;

        if ($user) {
            $now = Carbon::now();
            $today = $now->format('l');
            $schedules = Schedule::with('User')
                ->where('user_id', $user->id)
                ->where(function ($query) use ($today) {
                    $query->whereRaw('LOWER(day) LIKE ?', ['%' . strtolower($today) . '%'])
                        ->orWhereRaw('LOWER(day) LIKE ?', ['%' . strtolower(substr($today, 0, 3)) . '%']);
                })
                ->get();

            foreach ($schedules as $schedule) {
                Report::syncForSchedule($schedule, $now->toDateString(), $now);
            }
        }
       
        // Load requests for this user (for the profile display)
        $requests = $userId ? \Illuminate\Support\Facades\DB::table('requests')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get() : collect();

             $sessionStatus = session('user_id');
        $status = User::findOrFail($sessionStatus);
        $AccStatus = $status -> acc_status;
        
        
        return view('profile', compact('user', 'requests', 'AccStatus'));
    }

    public function update(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $userId = session('user_id');
        $user = User::findOrFail($userId);
           

        if ($user->profile_picture && !str_contains($user->profile_picture, 'profile_pictures/')) {
           
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

    public function showStatus()
    {

    }
}


