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
        Schema::create('categories_todos', function (Blueprint $table) {
            $table->integer('todos_id');
            $table->integer('categories_id');
            // Déclaraction de la clé primaire
            $table->primary(['todos_id', 'categories_id']);

            $table->foreign('todos_id')->references('id')->on('todos')->onDelete('cascade');
            $table->foreign('categories_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_todos');
    }
};
