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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_number');
            $table->dateTime('invoice_date');
            $table->dateTime('due_date');
            $table->float('total_amount');
            $table->float('amount_paid');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('tva_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('status_id')
                ->references('id')
                ->on('invoice_status')
                ->onDelete('cascade');
            $table->foreign('tva_id')
                ->references('id')
                ->on('tvas')
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
        Schema::dropIfExists('invoices');
    }
};
