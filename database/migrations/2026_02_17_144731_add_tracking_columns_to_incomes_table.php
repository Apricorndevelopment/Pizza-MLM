<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // List of all income tables
        $tables = [
            'direct_income', 
            'bonus_income', 
            'cashback_income', 
            'level_incomes', 
            'repurchase_incomes', 
            'vendor_incomes'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('order_id')->nullable()->after('id');
                $table->unsignedBigInteger('vendor_id')->nullable()->after('order_id')->comment('If product belongs to vendor');
                $table->unsignedBigInteger('admin_id')->nullable()->after('vendor_id')->comment('If product belongs to admin');
            });
        }
    }

    public function down()
    {
        $tables = [
            'direct_income', 
            'bonus_income', 
            'cashback_income', 
            'level_incomes', 
            'repurchase_incomes', 
            'vendor_incomes'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['order_id', 'vendor_id', 'admin_id']);
            });
        }
    }
};