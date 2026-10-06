<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Models\Report;
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

    public function test_public_request_generates_waiting_attendance_without_a_login_session(): void
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
            'start_time' => '09:40:00',
            'end_time' => '12:00:00',
            'Semester' => '1st Semester',
            'School_year' => '2026-2027',
        ]);

        $this->get('/')->assertOk();
        $this->get('/')->assertOk();

        $this->assertDatabaseHas('attendance', [
            'user_id' => $userId,
            'schedule_id' => $schedule->id,
            'attendance_date' => $now->toDateString(),
            'status' => 'waiting',
        ]);
        $this->assertDatabaseCount('attendance', 1);
        $this->assertDatabaseHas('room', [
            'id' => $roomId,
            'status' => 'vacant',
        ]);
        $this->getJson('/api/live-classrooms')
            ->assertOk()
            ->assertJsonPath('rooms.0.status', 'vacant');

        Carbon::setTestNow();
    }

    public function test_room_in_scan_request_is_used_to_validate_the_schedule(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-03 10:00:00', 'Asia/Manila'));

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
        DB::table('room')->insert([
            'room_name' => 'CC101',
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
        $end = $today->copy()->setTime(12, 0);

        $schedule = Schedule::create([
            'user_id' => $userId,
            'program_id' => $programId,
            'course_id' => $courseId,
            'room_id' => $roomId,
            'year_level' => '2',
            'section' => 'A',
            'day' => $day,
            'start_time' => $today->copy()->setTime(9, 40)->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
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

        $this->assertDatabaseHas('attendance', [
            'user_id' => $userId,
            'schedule_id' => $schedule->id,
            'time_in' => null,
            'time_out' => null,
        ]);
        $this->assertDatabaseHas('room', [
            'id' => $roomId,
            'status' => 'vacant',
        ]);

        $this->postJson('/api/attendance-scan', [
            'uid' => 'AB12CD34',
            'room' => 'CC102',
        ])->assertOk()
            ->assertJsonPath('status', 'accepted')
            ->assertJsonPath('time_in', '10:00:00')
            ->assertJsonPath('attendance_status', 'ongoing');

        $this->assertDatabaseHas('attendance', [
            'user_id' => $userId,
            'time_in' => '10:00:00',
            'time_out' => null,
            'status' => 'ongoing',
        ]);
        $this->assertDatabaseHas('room', [
            'id' => $roomId,
            'status' => 'occupied',
        ]);
    }

    public function test_attendance_scan_checks_out_after_the_local_schedule_end_time(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-03 14:20:00', 'Asia/Manila'));
        $now = Carbon::now('Asia/Manila');

        $collegeId = DB::table('college')->insertGetId([
            'college_name' => 'College of Engineering',
            'abbreviation' => 'COE',
            'description' => 'Engineering',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $userId = DB::table('users')->insertGetId([
            'first_name' => 'Checkout',
            'last_name' => 'Faculty',
            'middle_name' => '',
            'employee_ID' => 'CO-101',
            'email' => 'checkoutfaculty@example.com',
            'password' => bcrypt('secret'),
            'role' => 4,
            'college_id' => $collegeId,
            'RFID_code' => 'EF56GH78',
            'acc_status' => 'Attended',
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
            'room_name' => 'CC103',
            'room_type' => 'Lecture',
            'status' => 'vacant',
            'bldg_id' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $courseId = DB::table('courses')->insertGetId([
            'college_id' => $collegeId,
            'course_code' => 'CS102',
            'course_name' => 'Data Structures',
            'description' => 'Data structures',
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
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'Semester' => '1st Semester',
            'School_year' => '2026-2027',
        ]);

        $this->postJson('/api/attendance-scan', [
            'uid' => 'EF56GH78',
            'room' => 'CC103',
        ])->assertOk()
            ->assertJsonPath('status', 'accepted')
            ->assertJsonPath('time_in', '14:20:00')
            ->assertJsonPath('attendance_status', 'ongoing');

        $this->assertDatabaseHas('attendance', [
            'user_id' => $userId,
            'schedule_id' => $schedule->id,
            'time_in' => '14:20:00',
            'time_out' => null,
            'status' => 'ongoing',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'acc_status' => 'Attended',
        ]);

        $noShowSchedule = $schedule->replicate();
        $noShowSchedule->section = 'B';
        $noShowSchedule->save();

        Report::syncForSchedule(
            $noShowSchedule,
            $now->toDateString(),
            Carbon::parse('2026-10-03 14:30:00', 'Asia/Manila')
        );

        $this->assertDatabaseHas('attendance', [
            'user_id' => $userId,
            'schedule_id' => $noShowSchedule->id,
            'time_in' => null,
            'time_out' => null,
            'status' => 'absent',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'acc_status' => 'Attended',
        ]);

        Carbon::setTestNow(Carbon::parse('2026-10-03 15:09:59', 'Asia/Manila'));
        $this->postJson('/api/attendance-scan', [
            'uid' => 'EF56GH78',
            'room' => 'CC103',
        ])->assertOk()
            ->assertJsonPath('status', 'accepted')
            ->assertJsonPath('time_in', '14:20:00')
            ->assertJsonPath('time_out', '15:09:59')
            ->assertJsonPath('attendance_status', 'attended');

        $this->assertDatabaseHas('attendance', [
            'user_id' => $userId,
            'schedule_id' => $schedule->id,
            'time_in' => '14:20:00',
            'time_out' => '15:09:59',
            'status' => 'attended',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'acc_status' => 'Attended',
        ]);
    }
}
