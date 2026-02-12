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
        Schema::table('users', function (Blueprint $table) {
            // Stores the max income allowed per day
            $table->decimal('capping_limit', 20, 2)->default(0)->after('wallet2_balance');

            // Checks if user has purchased a package (0 = No, 1 = Yes)
            $table->boolean('is_capping_enabled')->default(0)->after('capping_limit');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['capping_limit', 'is_capping_enabled']);
        });
    }
};
