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
        Schema::create('Scanner', function(Blueprint $table){
    $table->id();
    $table->string('RFID_code')->unique();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->timestamp('scanned_at')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scanner');
    }
};
