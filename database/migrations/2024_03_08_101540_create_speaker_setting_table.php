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
        Schema::create('speaker_setting', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('speaker_id');
            $table->unsignedBigInteger('setting_id');
            $table->timestamps();
        });

        Schema::table('speaker_setting', function (Blueprint $table) {
            $table->foreign('speaker_id')
                ->references('id')
                ->on('speakers')
                ->onDelete('cascade');
            $table->foreign('setting_id')
                ->references('id')
                ->on('settings')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speaker_setting');
    }
};
