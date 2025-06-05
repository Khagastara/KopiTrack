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
        Schema::create('distribution_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_image');
            $table->integer('product_quantity');
            $table->desimal('product_price', 10, 2);
            $table->string('product_description')->nullable();
            $table->unsignedBigInteger('id_admin');
            $table->timestamps();

            $table->foreign('id_admin')
                ->on('admins')
                ->references('id')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_products');
    }
};
