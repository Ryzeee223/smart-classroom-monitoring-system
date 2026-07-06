<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\roombldg;
class room_bldg_controller extends Controller
{
    public function show()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
        $rooms = \Illuminate\Support\Facades\DB::table('rooms')->get();
        $buildings = \Illuminate\Support\Facades\DB::table('buildings')->get();
    }
}
