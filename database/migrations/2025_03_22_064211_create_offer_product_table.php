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
        Schema::create('offer_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id'); // Foreign key for offers
            $table->unsignedBigInteger('product_id'); // Foreign key for products
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade'); // Foreign key constraint
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade'); // Foreign key constraint
            $table->unique(['offer_id', 'product_id']); // Unique composite index

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_product');
    }
};
