<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->enum('subject_type', ['theory', 'lab'])->nullable()->comment('theory, lab');
            $table->string('credit_hour');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('credits');
    }
};
