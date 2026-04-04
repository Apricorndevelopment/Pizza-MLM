<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('auto_pool_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->unique();
            $table->boolean('is_active')->default(1); // 1 = Active, 0 = Inactive
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auto_pool_categories');
    }
};