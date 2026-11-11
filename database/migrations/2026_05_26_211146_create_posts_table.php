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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // связи
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('content_type_id')->constrained('content_types')->onDelete('cascade');

            // поля по ТЗ
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('quote_author')->nullable();
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->string('link')->nullable();
            $table->unsignedBigInteger('views')->default(0);

            // дата создания поста — это created_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
