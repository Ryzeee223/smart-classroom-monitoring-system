<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE attendance DROP CONSTRAINT IF EXISTS attendance_status_check');
            DB::statement("ALTER TABLE attendance ADD CONSTRAINT attendance_status_check CHECK (status IN ('attended', 'absent', 'late', 'on_leave', 'waiting', 'ongoing'))");
        } elseif (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE attendance MODIFY status ENUM('attended', 'absent', 'late', 'on_leave', 'waiting', 'ongoing') NOT NULL DEFAULT 'waiting'");
        } elseif ($driver === 'sqlite') {
            Schema::table('attendance', function (Blueprint $table): void {
                $table->string('status')->default('waiting')->change();
            });
        } else {
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
        } elseif (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::table('attendance')->where('status', 'ongoing')->update(['status' => 'waiting']);
            DB::statement("ALTER TABLE attendance MODIFY status ENUM('attended', 'absent', 'late', 'on_leave', 'waiting') NOT NULL DEFAULT 'waiting'");
        } elseif ($driver === 'sqlite') {
            DB::table('attendance')->where('status', 'ongoing')->update(['status' => 'waiting']);
            Schema::table('attendance', function (Blueprint $table): void {
                $table->enum('status', ['attended', 'absent', 'late', 'on_leave', 'waiting'])
                    ->default('waiting')
                    ->change();
            });
        } else {
            throw new RuntimeException("Unsupported database driver for attendance status migration: {$driver}");
        }
    }
};
