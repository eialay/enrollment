<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('contact');
            $table->date('birthdate');
            $table->string('gender');
            $table->string('address');
            $table->string('studentImage');
            $table->string('birthCertificate');
            $table->string('form137')->nullable();
            $table->string('goodMoral')->nullable();
            $table->string('reportCard')->nullable();
            $table->string('guardianFName');
            $table->string('guardianMName');
            $table->string('guardianLName');
            $table->string('guardianEmail');
            $table->string('guardianContact');
            $table->string('guardianRelationship');
            $table->string('guardianAddress');
            $table->timestamps();
            
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
