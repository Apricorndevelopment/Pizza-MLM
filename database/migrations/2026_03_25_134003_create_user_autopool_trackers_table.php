<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_autopool_trackers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // Ek user ka ek hi tracker hoga
            
            // Current Status
            $table->unsignedBigInteger('current_category_id')->nullable();
            $table->unsignedBigInteger('current_pool_id')->nullable();
            
            // Single Leg PV (Niche se aane wali PV)
            $table->decimal('single_leg_pv', 10, 2)->default(0); 
            
            // Category Entry Tracking (Reset logic wale columns)
            $table->decimal('category_repurchase_pv', 10, 2)->default(0); 
            $table->integer('category_directs_count')->default(0);
            
            // Is Locked? (True = Wait kar raha hai category unlock hone ka)
            $table->boolean('is_locked')->default(true); 

            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('current_category_id')->references('id')->on('auto_pool_categories')->onDelete('set null');
            $table->foreign('current_pool_id')->references('id')->on('auto_pools')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_autopool_trackers');
    }
};