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
        // Таблица. Предметы закрепляются за определнными преподавателями. 
        Schema::create('user_teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userTeacherId')->constrained('user_teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('subjectId')->constrained('subjects')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_teacher_subjects');
    }
};
