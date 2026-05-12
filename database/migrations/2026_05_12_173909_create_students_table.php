<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id_number')->unique(); // e.g. 2024-00123
            $table->string('first_name');
            $table->string('last_name');
            $table->string('section');
            $table->string('email')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // teacher who owns
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
