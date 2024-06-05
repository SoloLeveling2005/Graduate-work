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
        Schema::create('class_attendance_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Может быть 3: н - нет, б - по болезни, у - уважительная причина, пусто - присутствует
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_attendance_statuses');
    }
};
