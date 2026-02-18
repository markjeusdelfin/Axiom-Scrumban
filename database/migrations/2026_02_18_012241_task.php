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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('column_id');
            $table->uuid('organization_id');
            $table->uuid('sprint_id')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('position');
            // Using jsonb for PostgreSQL performance
            $table->jsonb('metadata')->nullable();
            $table->timestamps();

            // PostgreSQL Foreign Key
            $table->foreign('sprint_id')->references('id')->on('sprints')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */ 
    public function down(): void
    {
         Schema::dropIfExists('tasks');
    }
};
