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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vote_type_id')->unsigned();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->unsignedBigInteger('story_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->foreign('vote_type_id')
                ->references('id')
                ->on('vote_types')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('story_id')
                ->references('id')
                ->on('stories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
