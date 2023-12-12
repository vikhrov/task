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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('position_id'); // Змінено поле на ID посади
            $table->date('date_of_employment');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->integer('salary');
            $table->string('photo');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('admin_created_id')->nullable();
            $table->unsignedBigInteger('admin_updated_id')->nullable();
            $table->integer('level')->nullable();
            $table->timestamps();

            $table->foreign('position_id')->references('id')->on('positions'); // Доданий зовнішній ключ на таблицю positions
            $table->foreign('parent_id')->references('id')->on('employees');
            $table->foreign('admin_created_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('admin_updated_id')->references('id')->on('users')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
