<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('novels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->string('cover_image_path')->nullable();

            // التصنيف الأدبي
            $table->string('genre', 50)->default('عام');

            // قصيرة أو طويلة
            $table->string('length_type', 20)->default('short');

            // مستمرة أو مكتملة
            $table->string('status', 20)->default('ongoing');

            $table->boolean('is_published')->default(true);

            $table->timestamps();

            $table->index(['is_published', 'genre']);
            $table->index(['is_published', 'length_type']);
            $table->index(['is_published', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novels');
    }
};