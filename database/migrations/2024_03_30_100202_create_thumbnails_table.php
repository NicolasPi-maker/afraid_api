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
        Schema::create('thumbnails', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('alt');
            $table->string('extension');
            $table->unsignedBigInteger('teaser_id');
            $table->timestamps();
        });

        Schema::table('thumbnails', function (Blueprint $table) {
            $table->foreign('teaser_id')->references('id')->on('teasers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thumbnails');
    }
};
