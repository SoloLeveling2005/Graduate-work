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
        // Таблица расписания. Имеет фнукционал переходной таблицы на которую будут ссылаться, чтобы привязаться ко дню и группе.
        Schema::create('group_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupId')->constrained(table:'groups')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_schedules');
    }
};
