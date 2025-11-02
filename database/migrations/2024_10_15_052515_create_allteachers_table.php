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
        Schema::create('allteachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('department_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('section');
            $table->string('gender');
            $table->date('date_of_birth');
            $table->date('joining_date');
            $table->string('nid_number');
            $table->string('religion');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allteachers');
    }
};
