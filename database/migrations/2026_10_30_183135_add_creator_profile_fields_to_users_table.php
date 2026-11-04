<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('creator_specialties')->nullable();
            $table->text('creator_bio')->nullable();
            $table->boolean('show_in_creator_space')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'creator_specialties',
                'creator_bio',
                'show_in_creator_space',
            ]);
        });
    }
};