<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('enrollment_approved_by')->nullable();
            $table->timestamp('enrollment_approved_at')->nullable();
            $table->unsignedBigInteger('payment_approved_by')->nullable();
            $table->timestamp('payment_approved_at')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('enrollment_approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('payment_approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
