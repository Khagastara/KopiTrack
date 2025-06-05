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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('sub_price', 10, 2);
            $table->unsignedBigInteger('id_transaction');
            $table->unsignedBigInteger('id_distribution_product');
            $table->timestamps();

            $table->foreign('id_transaction')
                ->on('transactions')
                ->references('id')
                ->onDelete('cascade');
            $table->foreign('id_distribution_product')
                ->on('distribution_products')
                ->references('id')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
