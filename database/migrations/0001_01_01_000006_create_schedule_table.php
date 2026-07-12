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
       Schema::create('schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('Programs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('year_level');
            $table->string('section');
            $table->string('day');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('Subject');
            $table->string('Room');
            $table->string('Semester');
            $table->string('School_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule');
    }
};
