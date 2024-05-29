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
        Schema::create('class_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupScheduleClassId')->constrained(table:'group_schedule_classes')->cascadeOnUpdate()->cascadeOnDelete(); // За какое занятие стоит посещаемость
            $table->foreignId('attendanceStatusId')->nullable()->constrained('class_attendance_statuses')->onUpdate('cascade')->onDelete('set null')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_attendances');
    }
};
