<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardsIncome extends Model
{
    use HasFactory;

    protected $table = 'rewards_incomes';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'user_ulid',
        'rank_name',
        'reward_id',
        'reward_amount',
        'reward_achivements',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'reward_id' => 'integer',
        'reward_amount' => 'integer',
        'reward_achivements' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (optional but recommended)
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reward()
    {
        return $this->belongsTo(PercentageReward::class, 'reward_id');
    }
}
