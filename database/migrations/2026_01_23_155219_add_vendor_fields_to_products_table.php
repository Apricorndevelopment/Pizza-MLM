<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Vendor IDs
            $table->unsignedBigInteger('vendor_id')->nullable()->after('id'); // Vendor table ID
            $table->unsignedBigInteger('vendor_user_id')->nullable()->after('vendor_id'); // User table ID associated with vendor
            
            // Product Details
            $table->string('product_image')->nullable()->after('product_name');
            $table->decimal('mrp', 20, 2)->default(0)->after('price');
            $table->decimal('gst', 8, 2)->default(0)->after('mrp'); // GST Percentage or Amount
            $table->decimal('dp', 20, 2)->default(0)->after('gst'); // Distributor Price
            
            // Admin Controlled Fields (Nullable initially)
            $table->decimal('pv', 20, 2)->nullable()->after('dp'); // Point Value
            $table->decimal('percentage', 8, 2)->nullable()->after('pv'); // Commission Percentage
            
            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('percentage');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'vendor_id', 'vendor_user_id', 'product_image', 
                'mrp', 'gst', 'dp', 'pv', 'percentage', 'status'
            ]);
        });
    }
};