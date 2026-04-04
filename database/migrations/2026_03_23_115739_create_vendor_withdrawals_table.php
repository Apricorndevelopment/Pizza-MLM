<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Create Vendor Withdrawals Table
        Schema::create('vendor_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_ulid')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('vendor_charge', 10, 2)->default(0); // Admin charge for vendor
            $table->decimal('credited_amount', 10, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('payment_method', ['bank', 'upi']);
            $table->text('admin_remark')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 2. Add Vendor Withdraw Charge to percentage_incomes table
        Schema::table('percentage_incomes', function (Blueprint $table) {
            $table->decimal('vendor_withdraw_charge', 5, 2)->default(0)->after('tds_charge');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_withdrawals');
        
        Schema::table('percentage_incomes', function (Blueprint $table) {
            $table->dropColumn('vendor_withdraw_charge');
        });
    }
};