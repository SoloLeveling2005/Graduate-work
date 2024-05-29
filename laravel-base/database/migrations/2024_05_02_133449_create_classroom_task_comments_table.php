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
        Schema::create('classroom_task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroomTaskId')->constrained(table:'classroom_tasks')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('userStudentId')->constrained(table:'user_students')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_task_comments');
    }
};
