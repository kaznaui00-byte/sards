<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('novel_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title', 150);
            $table->longText('content');

            $table->unsignedInteger('chapter_number');

            $table->boolean('is_published')->default(true);

            $table->timestamps();

            $table->unique(['novel_id', 'chapter_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};