<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Models\Schedule;

class scheduleTester extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = Carbon::now();
        $timenow = $now->toTimeString();
        $endtime = $now->copy()->addMinutes(2)->toTimeString();
        Schedule::create([
            'user_id' => 3,
            'room_id' => 1,
            'program_id' => 1,
            'course_id' => 1,

            'year_level' => '1st Year',
            'section' => 'A',
            'Semester' => '1st Semester',
            'School_year' => '2025-2026',
            'day' => $now->format('l'),
            'start_time' => $timenow, 
            'end_time' => $endtime,   
        ]);

    }
}
