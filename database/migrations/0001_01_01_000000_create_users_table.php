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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->unique();
            $table->string('last_name')->unique();
            $table->string('employee_ID')->unique();
            $table->string('email')->unique();
            // profile_picture stored as path/string (image() macro not available)
            $table->string('profile_picture')->nullable();

            $table->string('password');
            $table->integer('role');
            $table->string('course')->nullable();
            // profile_picture already defined above

            $table->string('RFID_code')->nullable()->unique();
            $table->tinyInteger('acc_status')->default(1);
            $table->tinyInteger('status')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamp('login_time')->nullable();
            $table->timestamp('logout_time')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
//course
          Schema::create('Programs', function (Blueprint $table) {
            $table->id();
            $table->string('course_code')->unique();
            $table->string('course_name')->unique();
            $table->string('description')->nullable();
            $table->foreignId('college_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_code')->unique();
            $table->string('subject_name');
            $table->text('description')->nullable();
            // Avoid FK error: courses table may not exist in this migration set
            $table->unsignedBigInteger('course_id')->nullable();
            $table->timestamps();
        });

        Schema::create('schedule', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('course');
            $table->string('year_level');
            $table->string('section');
            $table->enum('Day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
            $table->string('Time');
            $table->string('Subject');
            $table->string('Room');
            $table->string('Semester');
            $table->string('School_year');
            $table->timestamps();
        });
                Schema::create('building', function(Blueprint $table) {
            $table->id('id')->primary();
            $table->string('bldg_name');
            $table->string('Room_type');
            $table->string('Room _code');
            $table->foreignId('Colleges_id')->constrained()->onDelete('cascade')->nullable();
            $table->timestamps();

});
Schema::create('Request', function(Blueprint $table) {
        $table->id('id')->primary();
        $table->integer('request_id');
        $table->string('letter');
        $table->string('reason');
        $table->foreignid('user_request')->constrained('users')->onDelete('cascade');
        $table->timestamps();
});

//college
Schema::create('college', function(blueprint $table){
$table->id('id')->primary();
$table->string('college_name')->unique();
$table->string('abbreviation')->unique();
$table->string('description')->nullable();

});
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        schema::dropIfExists('building');
        Schema::dropIfExists('Request');
    }
};
