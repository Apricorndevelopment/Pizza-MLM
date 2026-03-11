<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PercentageIncome extends Model
{
    use HasFactory;

    protected $table = 'percentage_incomes';

    protected $fillable = [
        'direct_income',
        'bonus_income',
        'cashback_income',
        'vendor_income',
        'personal_wallet',
        'second_wallet',
        'tds_charge',
        'admin_charge',
    ];

    // Timestamps are true by default in Eloquent, 
    // matching your schema (created_at, updated_at)
    public $timestamps = true;
}
