<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance', function (Blueprint $table): void {
            $table->index(['user_id', 'attendance_date'], 'attendance_user_date_idx');
            $table->index(['schedule_id', 'attendance_date'], 'attendance_schedule_date_idx');
            $table->index(['room_id', 'attendance_date', 'status'], 'attendance_room_date_status_idx');
            $table->index(['college_id', 'attendance_date'], 'attendance_college_date_idx');
        });

        Schema::table('schedule', function (Blueprint $table): void {
            $table->index(
                ['room_id', 'day', 'School_year', 'Semester', 'start_time'],
                'schedule_room_conflict_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('schedule', function (Blueprint $table): void {
            $table->dropIndex('schedule_room_conflict_idx');
        });

        Schema::table('attendance', function (Blueprint $table): void {
            $table->dropIndex('attendance_college_date_idx');
            $table->dropIndex('attendance_room_date_status_idx');
            $table->dropIndex('attendance_schedule_date_idx');
            $table->dropIndex('attendance_user_date_idx');
        });
    }
};
