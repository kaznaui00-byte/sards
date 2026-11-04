 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('post_type')->default('text');
           Schema::table('quotes', function (Blueprint $table) {
    $table->string('post_type')->default('text');
    $table->string('title')->nullable();
    $table->string('audio_path')->nullable();
});
        Schema::table('quotes', function (Blueprint $table) {
            $table->text('text')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['post_type', 'title']);
        });
    }
};