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
        Schema::create('classroom_task_answer_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroomTaskAnswerId')->constrained(table:'classroom_task_answers')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_task_answer_files');
    }
};
