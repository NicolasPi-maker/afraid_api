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
        Schema::create('illustrations', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('alt');
            $table->string('extension');
            $table->unsignedBigInteger('paragraph_id');
            $table->timestamps();
        });

        Schema::table('illustrations', function (Blueprint $table) {
            $table->foreign('paragraph_id')
                ->references('id')
                ->on('paragraphes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('illustrations');
    }
};
