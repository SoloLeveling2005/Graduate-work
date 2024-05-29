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
        // Таблица с подролями(правами) админов.
        Schema::create('admin_privileges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userAdminId')->constrained(table:'user_admins')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('privilege');  // Privileges: Суперадмин, Оператор, Менеджер групп, Координатор расписания
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_privileges');
    }
};
