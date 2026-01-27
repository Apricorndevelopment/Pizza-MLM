<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            // Stores the number of coupons the user has (e.g., 10)
            $table->integer('coupon_quantity')->default(0); 
            // Fixed value of one coupon (e.g., 10.00). Useful if you ever change the rate.
            $table->decimal('coupon_value', 8, 2)->default(10.00); 
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_coupons');
    }
};