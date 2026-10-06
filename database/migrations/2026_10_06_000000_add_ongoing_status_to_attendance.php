<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const STATUSES = "'attended', 'absent', 'late', 'on_leave', 'waiting', 'ongoing'";

    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE attendance DROP CONSTRAINT IF EXISTS attendance_status_check');
            DB::statement('ALTER TABLE attendance ADD CONSTRAINT attendance_status_check CHECK (status IN (' . self::STATUSES . '))');
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE attendance MODIFY status ENUM('attended', 'absent', 'late', 'on_leave', 'waiting', 'ongoing') NOT NULL DEFAULT 'waiting'");
        } elseif ($driver !== 'sqlite') {
            throw new RuntimeException("Unsupported database driver for attendance status migration: {$driver}");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::table('attendance')->where('status', 'ongoing')->update(['status' => 'waiting']);
            DB::statement('ALTER TABLE attendance DROP CONSTRAINT IF EXISTS attendance_status_check');
            DB::statement("ALTER TABLE attendance ADD CONSTRAINT attendance_status_check CHECK (status IN ('attended', 'absent', 'late', 'on_leave', 'waiting'))");
        } elseif ($driver === 'mysql') {
            DB::table('attendance')->where('status', 'ongoing')->update(['status' => 'waiting']);
            DB::statement("ALTER TABLE attendance MODIFY status ENUM('attended', 'absent', 'late', 'on_leave', 'waiting') NOT NULL DEFAULT 'waiting'");
        } elseif ($driver !== 'sqlite') {
            throw new RuntimeException("Unsupported database driver for attendance status migration: {$driver}");
        } else {
            DB::table('attendance')->where('status', 'ongoing')->update(['status' => 'waiting']);
        }
    }
};
