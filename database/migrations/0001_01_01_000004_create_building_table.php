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
        Schema::create('building', function(Blueprint $table) {
            $table->id();
            $table->foreignId('college_id')->nullable()->constrained('college')->onDelete('cascade');
            $table->string('bldg_name')->unique();
            $table->string('bldg_abbr')->unique();
            $table->timestamps();
        });
       
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building');
    }
};
