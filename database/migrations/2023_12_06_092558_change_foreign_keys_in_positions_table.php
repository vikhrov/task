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
        Schema::table('employees', function (Blueprint $table) {
            // Удалите старые внешние ключи
            $table->dropForeign(['admin_created_id']);
            $table->dropForeign(['admin_updated_id']);

            // Добавьте новые внешние ключи
            $table->foreign('admin_created_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('admin_updated_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Удалите новые внешние ключи
            $table->dropForeign(['admin_created_id']);
            $table->dropForeign(['admin_updated_id']);

            // Добавьте обратно старые внешние ключи
            $table->foreign('admin_created_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('admin_updated_id')->references('id')->on('admins')->onDelete('set null');
        });
    }
};

