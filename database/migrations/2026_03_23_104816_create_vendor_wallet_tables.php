<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Add vendor_wallet_balance to users table
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('vendor_wallet_balance', 10, 2)->default(0)->after('wallet2_balance');
        });

        // 2. Create vendor_wallet_transactions table
        Schema::create('vendor_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_ulid')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('notes')->nullable();
            $table->decimal('balance', 10, 2);
            $table->timestamps();

            // Foreign Key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_wallet_transactions');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('vendor_wallet_balance');
        });
    }
};