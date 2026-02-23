<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PercentageRepurchaseIncome extends Model
{
    use HasFactory;

    protected $table = 'percentage_repurchase_incomes';

    protected $fillable = [
        'level',
        'percentage',
    ];

    // Disable standard timestamps since table only has 'created_at'
    public $timestamps = false;

    // Manually handle creation time if needed, or let DB default handle it
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
        });
    }
}