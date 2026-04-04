<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAutopoolTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_category_id',
        'current_pool_id',
        'single_leg_pv',
        'category_repurchase_pv',
        'category_directs_count',
        'is_locked',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentCategory()
    {
        return $this->belongsTo(AutoPoolCategory::class, 'current_category_id');
    }

    public function currentPool()
    {
        return $this->belongsTo(AutoPool::class, 'current_pool_id');
    }
}