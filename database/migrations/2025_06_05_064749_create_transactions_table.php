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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->unsignedBigInteger('id_merchant');
            $table->unsignedBigInteger('id_finance');
            $table->timestamps();

            $table->foreign('id_merchant')
                ->on('merchants')
                ->references('id')
                ->onDelete('cascade');
            $table->foreign('id_finance')
                ->on('finances')
                ->references('id')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
