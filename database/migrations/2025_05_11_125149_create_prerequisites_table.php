<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prerequisites', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('subject_id');         // The subject requiring prerequisites
            $table->unsignedBigInteger('prerequisite_id');    // A prerequisite subject
            $table->unsignedInteger('required_credits')->default(0); // Required credits (whole numbers only)

            // Foreign keys
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('prerequisite_id')->references('id')->on('subjects')->onDelete('cascade');

            // Ensure a subject can't have the same prerequisite twice
            $table->unique(['subject_id', 'prerequisite_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prerequisites');
    }
};
