<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use App\Models\User;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Display a listing of requests (for admin/approval view).
     */
    public function index()
    {
        $requests = RequestModel::with('user')->orderByDesc('created_at')->get();

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
// display the request of faculties
    {
        $userRole = (int) (session('user_role') ?? 0);

        // Only Dean (2) and Assistant Dean (3) can view the approval modal
        $req = collect();
        if (in_array($userRole, [2, 3], true)) {
            $req = RequestModel::with('user')
                ->whereHas('user', function ($q) {
                    $q->whereIn('role', [2, 3, 4, 5]); // All roles that submit requests
                })
                ->where('status', 'pending') // Only pending requests
                ->get()
                ->groupBy('user_id');
        }

        $recent_faculty = \App\Models\users::whereIn('role', [2, 3, 4, 5])
            ->latest('created_at')
            ->get();

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
            'letter' => 'required|string|max:50',
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
        $user =User::findOrFail($requestHandle->user_id);
       

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

