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
        Schema::create('event_selected_student_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendarEventId')->constrained(table:'calendar_events')->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('group'); // true - A, false - B
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_selected_student_groups');
    }
};
