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
        // Таблица групп. Имеет специальность и куратора (преподавателя).
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('title')->uniqie();
            $table->foreignId('departmentId')->nullable()->constrained('departments')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('userTeacherId')->nullable()->constrained('user_teachers')->onUpdate('cascade')->onDelete('set null');
            $table->string('color');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
