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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 2000);
            $table->string('author', 2000);
            $table->string('genre', 2000);
            $table->longText('description')->nullable();
            $table->integer('isbn')->unique();
            $table->string('image', 2000);
            $table->date('published')->nullable();
            $table->string('publisher', 1000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
