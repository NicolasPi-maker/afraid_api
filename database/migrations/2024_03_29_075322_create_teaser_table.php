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
        Schema::create('teasers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->unsignedBigInteger('story_id');
            $table->unsignedBigInteger('prompt_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::table('teasers', function (Blueprint $table) {
            $table->foreign('story_id')
                ->references('id')
                ->on('stories')
                ->onDelete('cascade');
            $table->foreign('prompt_id')
                ->references('id')
                ->on('prompts')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teaser');
    }
};
