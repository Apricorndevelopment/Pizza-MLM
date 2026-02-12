<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('product-package', function (Blueprint $table) {
            $table->boolean('manage_stock')->default(false)->after('capping');
            $table->integer('stock_quantity')->default(0)->after('manage_stock');
        });
    }

    public function down()
    {
        Schema::table('product-package', function (Blueprint $table) {
            $table->dropColumn(['manage_stock', 'stock_quantity']);
        });
    }
};
