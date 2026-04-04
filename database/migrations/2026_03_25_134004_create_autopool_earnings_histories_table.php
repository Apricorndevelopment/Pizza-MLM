<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('autopool_earnings_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('pool_id')->nullable();
            
            $table->decimal('reward_amount', 10, 2);
            $table->string('rank_name_achieved');
            
            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('auto_pool_categories')->onDelete('set null');
            $table->foreign('pool_id')->references('id')->on('auto_pools')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('autopool_earnings_histories');
    }
};