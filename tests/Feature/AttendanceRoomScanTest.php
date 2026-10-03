<?php

namespace Tests\Feature;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AttendanceRoomScanTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_dashboard_generates_waiting_attendance_for_todays_schedule(): void
    {
        $now = Carbon::parse('2026-10-03 10:00:00');
        Carbon::setTestNow($now);

        $collegeId = DB::table('college')->insertGetId([
            'college_name' => 'College of Engineering',
            'abbreviation' => 'COE',
            'description' => 'Engineering',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $userId = DB::table('users')->insertGetId([
            'first_name' => 'Attendance',
            'last_name' => 'Faculty',
            'middle_name' => '',
            'employee_ID' => 'AT-101',
            'email' => 'attendancefaculty@example.com',
            'password' => bcrypt('secret'),
            'role' => 4,
            'college_id' => $collegeId,
            'RFID_code' => 'CD34EF56',
            'acc_status' => 'Present',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $programId = DB::table('programs')->insertGetId([
            'college_id' => $collegeId,
            'program_abbr' => 'BSCS',
            'program_name' => 'Bachelor of Science in Computer Science',
            'description' => 'Core program',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $roomId = DB::table('room')->insertGetId([
            'room_name' => 'CC102',
            'room_type' => 'Lecture',
            'status' => 'vacant',
            'bldg_id' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $courseId = DB::table('courses')->insertGetId([
            'college_id' => $collegeId,
            'course_code' => 'CS101',
            'course_name' => 'Intro to Programming',
            'description' => 'Programming basics',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $schedule = Schedule::create([
            'user_id' => $userId,
            'program_id' => $programId,
            'course_id' => $courseId,
            'room_id' => $roomId,
            'year_level' => '2',
            'section' => 'A',
            'day' => $now->format('l'),
            'start_time' => '10:30:00',
            'end_time' => '12:00:00',
            'Semester' => '1st Semester',
            'School_year' => '2026-2027',
        ]);

        $this->withSession([
            'logged_in' => true,
            'user_role' => 4,
            'user_id' => $userId,
        ])->get('/dashboard')->assertOk();

        $this->assertDatabaseHas('attendance', [
            'user_id' => $userId,
            'schedule_id' => $schedule->id,
            'attendance_date' => $now->toDateString(),
            'status' => 'waiting',
        ]);

        Carbon::setTestNow();
    }

    public function test_room_in_scan_request_is_used_to_validate_the_schedule(): void
    {
        $collegeId = DB::table('college')->insertGetId([
            'college_name' => 'College of Engineering',
            'abbreviation' => 'COE',
            'description' => 'Engineering',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId = DB::table('users')->insertGetId([
            'first_name' => 'Room',
            'last_name' => 'Faculty',
            'middle_name' => '',
            'employee_ID' => 'RF-101',
            'email' => 'roomfaculty@example.com',
            'password' => bcrypt('secret'),
            'role' => 4,
            'college_id' => $collegeId,
            'RFID_code' => 'AB12CD34',
            'acc_status' => 'Present',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $programId = DB::table('programs')->insertGetId([
            'college_id' => $collegeId,
            'program_abbr' => 'BSCS',
            'program_name' => 'Bachelor of Science in Computer Science',
            'description' => 'Core program',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roomId = DB::table('room')->insertGetId([
            'room_name' => 'CC102',
            'room_type' => 'Lecture',
            'status' => 'vacant',
            'bldg_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $courseId = DB::table('courses')->insertGetId([
            'college_id' => $collegeId,
            'course_code' => 'CS101',
            'course_name' => 'Intro to Programming',
            'description' => 'Programming basics',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $today = Carbon::today();
        $day = $today->format('l');

        Schedule::create([
            'user_id' => $userId,
            'program_id' => $programId,
            'course_id' => $courseId,
            'room_id' => $roomId,
            'year_level' => '2',
            'section' => 'A',
            'day' => $day,
            'start_time' => $today->copy()->subMinutes(30)->format('H:i:s'),
            'end_time' => $today->copy()->addMinutes(120)->format('H:i:s'),
            'Semester' => '1st Semester',
            'School_year' => '2026-2027',
        ]);

        $response = $this->postJson('/api/attendance-scan', [
            'uid' => 'AB12CD34',
            'room' => 'CC101',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'denied')
            ->assertJsonPath('message', 'This RFID card is not assigned to the scheduled room.');

        $this->assertDatabaseMissing('attendance', [
            'user_id' => $userId,
        ]);
    }
}
