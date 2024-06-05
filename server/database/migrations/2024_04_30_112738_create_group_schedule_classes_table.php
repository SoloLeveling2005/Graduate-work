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
        // Таблица расписания.
        Schema::create('group_schedule_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupId')->constrained(table:'groups')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('date');
            $table->foreignId('subjectId')->nullable()->constrained('group_subjects')->onUpdate('cascade')->onDelete('set null')->default(null);
            $table->string('subgroup', 1)->nullable(); // A группа  /  B группа  / null - общая пара.
            $table->integer('number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_schedule_classes');
    }
};
