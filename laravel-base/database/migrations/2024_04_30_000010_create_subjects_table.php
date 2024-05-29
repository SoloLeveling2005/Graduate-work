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
        // Таблица с предметами: Математика, Физра, Основа права, Разработка мобильных приложений
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('title')->uniqie();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
