<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Order Main Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('order_id')->unique(); // e.g., ORD-2024001
            $table->decimal('total_amount', 10, 2); // Total DP Value
            $table->decimal('wallet1_deducted', 10, 2); // Main Balance
            $table->decimal('wallet2_deducted', 10, 2); // Cashback Balance (Multiples of 50)
            $table->string('status')->default('placed'); // placed, shipped, delivered
            $table->timestamps();
        });

        // 2. Order Items Table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('product_type'); // 'admin' (ProductPackage) or 'vendor' (Product)
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->string('product_image')->nullable();
            $table->decimal('price', 10, 2); // Unit DP
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->unsignedBigInteger('vendor_id')->nullable(); // Null if Admin Product
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
