<?php

namespace Tests\Feature;

use App\Http\Controllers\ReportController;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReportCollegeScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_creates_missing_past_attendance_records_for_selected_date(): void
    {
        $collegeA = DB::table('college')->insertGetId([
            'college_name' => 'College of Engineering',
            'abbreviation' => 'COE',
            'description' => 'Engineering',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $facultyId = DB::table('users')->insertGetId([
            'first_name' => 'Past',
            'last_name' => 'Faculty',
            'middle_name' => '',
            'employee_ID' => 'PF-001',
            'email' => 'pastfaculty@example.com',
            'password' => bcrypt('secret'),
            'role' => 4,
            'college_id' => $collegeA,
            'RFID_code' => 'PF001',
            'acc_status' => 'Present',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminId = DB::table('users')->insertGetId([
            'first_name' => 'Dean',
            'last_name' => 'User',
            'middle_name' => '',
            'employee_ID' => 'DU-001',
            'email' => 'dean@example.com',
            'password' => bcrypt('secret'),
            'role' => 2,
            'college_id' => $collegeA,
            'RFID_code' => 'DU001',
            'acc_status' => 'Present',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $programId = DB::table('programs')->insertGetId([
            'college_id' => $collegeA,
            'program_abbr' => 'BSCS',
            'program_name' => 'Bachelor of Science in Computer Science',
            'description' => 'Core program',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roomId = DB::table('room')->insertGetId([
            'room_name' => 'Room 303',
            'room_type' => 'Lecture',
            'status' => 'vacant',
            'bldg_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $courseId = DB::table('courses')->insertGetId([
            'college_id' => $collegeA,
            'course_code' => 'CS201',
            'course_name' => 'Data Structures',
            'description' => 'Data structures basics',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $reportDate = Carbon::parse('2026-06-01');

        $schedule = Schedule::create([
            'user_id' => $facultyId,
            'program_id' => $programId,
            'course_id' => $courseId,
            'room_id' => $roomId,
            'year_level' => '2',
            'section' => 'A',
            'day' => $reportDate->format('l'),
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
            'Semester' => '1st Semester',
            'School_year' => '2026-2027',
        ]);

        session([
            'logged_in' => true,
            'user_role' => 2,
            'user_id' => $adminId,
            'college_id' => $collegeA,
        ]);

        $request = \Illuminate\Http\Request::create('/reports/generate', 'POST', ['date' => $reportDate->toDateString()]);
        $response = (new ReportController())->generate($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertDatabaseHas('attendance', [
            'user_id' => $facultyId,
            'college_id' => $collegeA,
            'schedule_id' => $schedule->id,
            'attendance_date' => $reportDate->toDateString(),
        ]);
    }

    public function test_program_head_only_sees_same_college_reports(): void
    {
        $collegeA = DB::table('college')->insertGetId([
            'college_name' => 'College of Engineering',
            'abbreviation' => 'COE',
            'description' => 'Engineering',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $collegeB = DB::table('college')->insertGetId([
            'college_name' => 'College of Arts',
            'abbreviation' => 'CA',
            'description' => 'Arts',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $programHeadId = DB::table('users')->insertGetId([
            'first_name' => 'Program',
            'last_name' => 'Head',
            'middle_name' => '',
            'employee_ID' => 'PH-001',
            'email' => 'ph@example.com',
            'password' => bcrypt('secret'),
            'role' => 5,
            'college_id' => $collegeA,
            'RFID_code' => 'PH001',
            'acc_status' => 'Present',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sameCollegeFacultyId = DB::table('users')->insertGetId([
            'first_name' => 'Same',
            'last_name' => 'Faculty',
            'middle_name' => '',
            'employee_ID' => 'SC-001',
            'email' => 'samefaculty@example.com',
            'password' => bcrypt('secret'),
            'role' => 4,
            'college_id' => $collegeA,
            'RFID_code' => 'SC001',
            'acc_status' => 'Present',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $otherCollegeFacultyId = DB::table('users')->insertGetId([
            'first_name' => 'Other',
            'last_name' => 'Faculty',
            'middle_name' => '',
            'employee_ID' => 'OC-001',
            'email' => 'otherfaculty@example.com',
            'password' => bcrypt('secret'),
            'role' => 4,
            'college_id' => $collegeB,
            'RFID_code' => 'OC001',
            'acc_status' => 'Present',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $programId = DB::table('programs')->insertGetId([
            'college_id' => $collegeA,
            'program_abbr' => 'BSCS',
            'program_name' => 'Bachelor of Science in Computer Science',
            'description' => 'Core program',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roomId = DB::table('room')->insertGetId([
            'room_name' => 'Room 101',
            'room_type' => 'Lecture',
            'status' => 'vacant',
            'bldg_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $courseId = DB::table('courses')->insertGetId([
            'college_id' => $collegeA,
            'course_code' => 'CS101',
            'course_name' => 'Intro to Programming',
            'description' => 'Programming basics',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $today = Carbon::today();
        $day = $today->format('l');

        $sameCollegeSchedule = Schedule::create([
            'user_id' => $sameCollegeFacultyId,
            'program_id' => $programId,
            'course_id' => $courseId,
            'room_id' => $roomId,
            'year_level' => '2',
            'section' => 'A',
            'day' => $day,
            'start_time' => '08:00:00',
            'end_time' => '09:30:00',
            'Semester' => '1st Semester',
            'School_year' => '2026-2027',
        ]);

        $otherCollegeSchedule = Schedule::create([
            'user_id' => $otherCollegeFacultyId,
            'program_id' => $programId,
            'course_id' => $courseId,
            'room_id' => $roomId,
            'year_level' => '2',
            'section' => 'B',
            'day' => $day,
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'Semester' => '1st Semester',
            'School_year' => '2026-2027',
        ]);

        session([
            'logged_in' => true,
            'user_role' => 5,
            'user_id' => $programHeadId,
            'college_id' => $collegeA,
        ]);

        $view = (new ReportController())->index();

        $this->assertDatabaseHas('attendance', [
            'user_id' => $sameCollegeFacultyId,
            'college_id' => $collegeA,
            'schedule_id' => $sameCollegeSchedule->id,
        ]);

        $this->assertDatabaseMissing('attendance', [
            'user_id' => $otherCollegeFacultyId,
            'college_id' => $collegeB,
        ]);

        $facultySchedules = $view->getData()['facultySchedules'];
        $this->assertCount(1, $facultySchedules);
        $this->assertSame('Same Faculty', $facultySchedules->first()['faculty']);
    }
}
