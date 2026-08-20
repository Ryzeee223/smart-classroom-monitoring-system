<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance', function(Blueprint $table) 
        {
        $table ->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('schedule_id')->constrained('schedule')->onDelete('cascade');
        $table->time('time_in')->nullable();
        $table->time('time_out')->nullable(); 
        $table->timestamp('attendance_date');
        $table->enum('status_in',['attended', 'absent', 'late', 'on_leave', 'waiting'])->default('waiting');
        $table->string('status_out', 50)->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
