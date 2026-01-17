<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPackagePurchase extends Model
{
    protected $table = 'package2_purchases';
    protected $fillable = [
        'user_id',
        'ulid',
        'package2_id',
        'package2_detail_id',
        'package_name',
        'quantity',
        'maturity',
        'endorsed',
        'payout_processed',
        'payout_amount',
        'rate',
        'capital',
        'time',
        'profit_share',
        'final_price',
        'purchased_at',
        'invoice_no',
        'bed_no',
    ];

    protected $casts = [
        'time' => 'integer',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package2()
    {
        return $this->belongsTo(ProductPackage::class);
    }

    public function rateDetail()
    {
        return $this->belongsTo(ProductPackageDetails::class, 'package2_detail_id');
    }

    public function maturityMonthlyDeductions()
    {
        return $this->hasMany(MaturityMonthlyDeduction::class);
    }
}
