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
        // Таблица хранит запросы на замену.
        Schema::create('group_schedule_class_replacement_requests', function (Blueprint $table) {
            $table->id();
            // Новые данные. Данные по номеру занятия и дню расписания берется через groupScheduleClassId
            $table->foreignId('userTeacherId')->nullable()->constrained('user_teachers')->onUpdate('cascade')->onDelete('set null')->default(null);
            // Данные по замене
            $table->foreignId('groupScheduleClassId')->constrained(table:'group_schedule_classes')->cascadeOnUpdate()->cascadeOnDelete(); // Какое занятие заменяем.
            $table->string('subgroup', 1)->nullable(); // A группа  /  B группа  / null - общая пара.
            $table->string('reason');  // Причина.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_schedule_class_replacement_requests');
    }
};
