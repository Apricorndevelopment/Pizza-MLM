<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PercentageReward extends Model
{
    use HasFactory;

    protected $table = 'percentage_rewards';

    protected $fillable = [
        'sr_no',
        'achievement',
        'reward',
        'rank',
    ];

    // Disable timestamps as they are not in your table structure
    public $timestamps = false;
}
