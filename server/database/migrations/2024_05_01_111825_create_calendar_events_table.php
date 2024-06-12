<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. event_selected_student_alls event_selected_student_groups event_selected_student_children - все, группа А/Б, определенный студент.
     */
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->time('time');
            $table->string('place');
            $table->boolean('eventType'); // true - Мероприятие , false - Задание
            $table->foreignId('groupId')->constrained(table:'groups')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('subgroup', 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
