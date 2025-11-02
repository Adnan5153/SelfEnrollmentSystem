<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject_code');
            $table->enum('year', ['1st Year', '2nd Year', '3rd Year', '4th Year', 'Technical Electives'])->default('1st Year');
            $table->unsignedBigInteger('teacher_id')->nullable(); // Foreign key to teachers table
            $table->unsignedBigInteger('credit_id')->nullable();  // Foreign key to credits table
            $table->unsignedBigInteger('department_id')->nullable(); // Foreign key to departments table
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('credit_id')->references('id')->on('credits')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }


    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};
