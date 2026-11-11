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
        Schema::table('posts', function (Blueprint $table) {
            // Флаг "это репост"
            $table->boolean('is_repost')->default(false)->after('views');

            // Ссылка на оригинальный пост
            $table->foreignId('original_post_id')
                ->nullable()
                ->after('is_repost')
                ->constrained('posts')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['original_post_id']);
            $table->dropColumn(['is_repost', 'original_post_id']);
        });
    }
};
