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
         Schema::create('college', function(blueprint $table){
$table->id();
$table->string('college_name')->unique();
$table->string('abbreviation')->unique();
$table->string('description')->nullable();
$table->timestamp();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('college');
    }
};
