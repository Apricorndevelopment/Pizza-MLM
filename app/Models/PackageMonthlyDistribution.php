<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageMonthlyDistribution extends Model
{
    protected $table = 'package_monthly_distributions';
    protected $fillable = [
        'user_id',
        'user_ulid',
        'package2_purchase_id',
        'purchase_amount',
        'rate_percentage',
        'distributed_amount',
        'months_remaining',
        'distribution_date'
    ];

   public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function packagePurchase()
    {
        return $this->belongsTo(ProductPackagePurchase::class, 'package2_purchase_id');
    }

}
