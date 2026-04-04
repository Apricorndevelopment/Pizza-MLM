<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('auto_pools', function (Blueprint $table) {
            $table->id();
            $table->integer('pool_level')->unique(); // 1, 2, 3...
            $table->string('rank_name'); // Star, Bronze, Silver...
            $table->integer('required_pv'); // 100, 200, 400...
            $table->decimal('income', 10, 2); // 400, 800...
            $table->integer('direct_condition')->default(0); // 0, 1, 2...
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auto_pools');
    }
};
