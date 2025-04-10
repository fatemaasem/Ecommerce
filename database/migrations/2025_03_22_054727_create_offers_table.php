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
        Schema::create('offers', function (Blueprint $table) {
            $table->id(); // Primary Key, Auto Increment
            $table->string('title',100); // Offer title
            $table->text('description'); // Offer details
            $table->enum('discount_type', ['percentage', 'fixed']); // Discount type
            $table->decimal('discount_value', 8, 2); // Discount amount
            $table->enum('applies_to', ['all', 'category', 'specific_products']); // Determines scope
            $table->unsignedBigInteger('category_id')->nullable(); // If applies to a category
            $table->timestamp('start_date')->nullable(); // Offer validity period start
            $table->timestamp('end_date')->nullable(); // Offer validity period end
            $table->timestamps(); // created_at and updated_at timestamps

            // Foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
