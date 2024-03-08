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
        Schema::create('speeches', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('extension');
            $table->unsignedBigInteger('speaker_id');
            $table->unsignedBigInteger('story_id');
            $table->unsignedBigInteger('language_id');
            $table->timestamps();
        });

        Schema::table('speeches', function (Blueprint $table) {
            $table->foreign('speaker_id')
                ->references('id')
                ->on('speakers')
                ->onDelete('cascade');
            $table->foreign('story_id')
                ->references('id')
                ->on('stories')
                ->onDelete('cascade');
            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speeches');
    }
};
