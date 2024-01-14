<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $tableName = 'employees';
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('position_id')
                ->index()
                ->constrained('positions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->date('date_of_employment');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->integer('salary');
            $table->string('photo');
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained($this->tableName)
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('admin_created_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('admin_updated_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->smallInteger('level')->nullable();
            $table->timestamps();
//            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
