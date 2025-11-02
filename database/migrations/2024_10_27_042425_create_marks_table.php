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
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');  // FK -> students.id
            $table->unsignedBigInteger('teacher_id');  // FK -> teachers.id
            $table->unsignedBigInteger('subject_id');  // FK -> subjects.id
            $table->integer('marks');  // Marks given
            $table->text('remarks')->nullable();  // Optional remarks
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('student_id')
                ->references('id')->on('students')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('teacher_id')
                ->references('id')->on('teachers')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('subject_id')
                ->references('id')->on('subjects')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
