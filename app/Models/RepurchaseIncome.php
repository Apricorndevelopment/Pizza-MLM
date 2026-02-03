<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepurchaseIncome extends Model
{
    protected $table = 'repurchase_incomes';
    protected $fillable = ['user_id' , 'from_ulid' , 'from_name' , 'purchase_amount' , 'purchase_pv' , 'commission' , 'level'];
}
