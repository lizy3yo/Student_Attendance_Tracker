<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('class_name', 100);
            $table->string('class_code', 20);
            $table->unsignedTinyInteger('year');
            $table->char('block', 1);
            $table->string('semester', 20)->default('First');
            $table->string('academic_year', 20)->default('2025-2026');
            $table->unsignedInteger('capacity')->default(40);
            $table->timestamps();

            $table->unique(['user_id', 'class_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
};
