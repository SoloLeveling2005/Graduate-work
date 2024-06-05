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
        // Таблица с преподавателями.
        Schema::create('user_teachers', function (Blueprint $table) {
            $table->id();
            $table->string('login', 155)->unique();
            $table->string('password', 155);
            $table->string('fio', 155)->unique();
            $table->foreignId('auditoriaId')->nullable()->constrained('auditoria')->onUpdate('cascade')->onDelete('set null')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_teachers');
    }
};
