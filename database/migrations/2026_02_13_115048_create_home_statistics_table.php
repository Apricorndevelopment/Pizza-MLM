<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('home_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g. Active Members
            $table->string('value'); // e.g. 10K+
            $table->string('icon')->nullable(); // Optional: FontAwesome class or Image
            $table->integer('sort_order')->default(0); // Display order
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('home_statistics');
    }
};