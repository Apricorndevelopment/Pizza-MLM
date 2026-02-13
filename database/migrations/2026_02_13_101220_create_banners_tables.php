<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Vendor Banners Table
        Schema::create('vendor_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('banner_image'); // File Path
            $table->string('link')->nullable(); // Optional redirect link
            $table->timestamps();
        });

        // 2. Product Banners Table
        Schema::create('product_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('banner_image'); // File Path
            $table->string('link')->nullable(); // Optional redirect link
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_banners');
        Schema::dropIfExists('product_banners');
    }
};