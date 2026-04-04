<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutopoolEarningsHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'pool_id',
        'reward_amount',
        'rank_name_achieved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(AutoPoolCategory::class, 'category_id');
    }

    public function pool()
    {
        return $this->belongsTo(AutoPool::class, 'pool_id');
    }
}