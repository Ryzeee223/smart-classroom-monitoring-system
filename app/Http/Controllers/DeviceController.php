<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;

class DeviceController extends Controller
{
    public function pulse(Request $request)
    {
        $validated = $request->validate([
            'mac_address' => 'required|string',
            'ip_address' => 'required|ip',
        ]);

        // Matches 'last_seen' to your model's fillable field
        $device = Device::updateOrCreate(
            ['mac_address' => $validated['mac_address']], 
            [
                'ip_address' => $validated['ip_address'],
                'status'     => 'online',
                'last_seen'  => now(), // Corrected from last_seen_at
            ]
        );

        return response()->json([
            'status' => 'success',
            'assigned_room_id' => $device->room_id, 
        ], 200);
    }
}