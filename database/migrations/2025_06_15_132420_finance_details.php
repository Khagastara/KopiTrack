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
        Schema::create('finance_details', function (Blueprint $table) {
            $table->id();
            $table->integer('expenditure_cost');
            $table->string('expenditure_description');
            $table->unsignedBigInteger('id_finance');

            $table->foreign('id_finance')
            ->on('finances')
            ->references('id')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_details');
    }
};
