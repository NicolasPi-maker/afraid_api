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
        Schema::create('paragraphes', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->integer('order'); // order of the paragraph in the chapter (1, 2, 3, ...)
            $table->unsignedBigInteger('chapter_id');
            $table->timestamps();
        });

        Schema::table('paragraphes', function (Blueprint $table) {
            $table->foreign('chapter_id')
                ->references('id')
                ->on('chapters')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paragraphes');
    }
};
