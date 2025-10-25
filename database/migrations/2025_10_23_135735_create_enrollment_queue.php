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
        Schema::create('enrollment_queue', function (Blueprint $table) {
            $table->id();
            $table->string('enrollment_code');
            $table->string('queue_number')->unique();
            $table->string('status')->default('Waiting');
            $table->timestamps();
            $table->foreign('enrollment_code')->references('reference_code')->on('enrollments')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_queue');
    }
};
