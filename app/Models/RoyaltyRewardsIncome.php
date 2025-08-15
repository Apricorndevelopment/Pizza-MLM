<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoyaltyRewardsIncome extends Model
{
    // Table name
    protected $table = 'royalty_rewards_income';

    protected $fillable = [
        'user_id',
        'user_ulid',
        'points',
        'rank',
        'status',
    ];
}
