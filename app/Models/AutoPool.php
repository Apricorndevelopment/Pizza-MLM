<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoPool extends Model
{
    use HasFactory;

    protected $fillable = [
        'pool_level',
        'rank_name',
        'required_pv',
        'income',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(AutoPoolCategory::class, 'category_id');
    }
}