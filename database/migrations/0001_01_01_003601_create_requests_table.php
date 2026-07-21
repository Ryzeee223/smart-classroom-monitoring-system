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
        Schema::create('requests', function (Blueprint $table) {
            // Keep the existing auto-id
            $table->id();

            // Columns used by ProfileController@storeRequest()
            $table->unsignedBigInteger('request_id')->unique();
            $table->string('letter', 255);
            $table->string('reason', 255);
            $table->unsignedBigInteger('user_request');

            $table->timestamps();

            // If you want referential integrity, uncomment:
            // $table->foreign('user_request')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
