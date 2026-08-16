<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    /**
     * Display a listing of requests (for admin/approval view).
     */
    public function index()
    {
        $currentUserId = session('user_id') ?? auth()->id();
        $currentUser = \App\Models\users::find($currentUserId);

        $userRole = $currentUser ? (int) $currentUser->role : (int) (session('user_role') ?? 0);
        $userCollegeId = $currentUser ? (int) $currentUser->college_id : (int) (session('college_id') ?? 0);

        if (in_array($userRole, [2, 3], true) && $userCollegeId > 0) {
            $requests = RequestModel::with('user')
                ->whereHas('user', function ($q) use ($userCollegeId) {
                    $q->whereIn('role', [2, 3, 4, 5])
                      ->where('college_id', '=', $userCollegeId);
                })
                ->orderByDesc('created_at')
                ->get();
        } else {
            $requests = RequestModel::with('user')->orderByDesc('created_at')->get();
        }

        return view('partials.approve', [
            'requests' => $requests,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */


public function show($id)
{
    // 1. Resolve logged-in user ID safely
    $currentUserId = session('user_id') ?? auth()->id();
    
    // 2. Fetch user instance to check credentials
    $dean = \App\Models\users::find($currentUserId);
    
    $userCollegeId = $dean ? (int)$dean->college_id : (int)session('college_id');
    $userRole = $dean ? (int)$dean->role : (int)session('user_role');

    $req = collect();
    $recent_faculty = collect();

    // 3. Enforce strictly positive integer for college_id
    if (in_array($userRole, [2, 3], true) && $userCollegeId > 0) {

        $req = RequestModel::with('user')
            ->whereHas('user', function ($q) use ($userCollegeId) {
                $q->whereIn('role', [2, 3, 4, 5])
                  ->where('college_id', '=', $userCollegeId);
            })
            ->where('status', 'pending')
            ->get()
            ->groupBy('user_id');

        $recent_faculty = \App\Models\users::whereIn('role', [2, 3, 4, 5])
            ->where('college_id', '=', $userCollegeId)
            ->latest('created_at')
            ->get();
    }

    return view('partials.notifications-modal', compact('req', 'recent_faculty'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
            'reason' => 'required|string|max:50',
        ]);

        RequestModel::create([
            'user_id'    => $userId,
            'letter'        => $validated['letter'],
            'reason'        => $validated['reason'],
            'status'        =>$validated['status'] ?? 'pending',
            
        ]);

        return redirect()->route('profile')->with('success', 'Request submitted successfully!');
    }

   
    public function approval($id)
    {
        $requestHandle = RequestModel::findOrFail($id);
        $user = users::findOrFail($requestHandle->user_id);

        $userStatus = match (strtolower($requestHandle->reason ?? '')) {
            'Sick leave' => 'Sick leave',
            'official business leave' => 'Business Leave',
            'vacation' => 'Vacation',
            'absent' => 'Absent',
        };
        
        $requestHandle->update(['status' => 'approved']);
        $user->update(['acc_status' => $userStatus]);

        return redirect()->route('dashboard')->with('success', 'Request Approved!');
    }

    
    public function decline($id)
    {
        $requestHandle = RequestModel::findOrFail($id);

        $requestHandle->update(['status' => 'declined']);

        return redirect()->route('dashboard')->with('success', 'Request Declined!');
    }

    public function showreason(Request $request)
    {
        $RequestRecord = RequestModel::findOrFail($request->id);
        return view('partials.approve', compact('RequestRecord'));
    }
}
