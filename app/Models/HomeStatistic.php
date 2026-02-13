<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeStatistic extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'value', 'sort_order'];
}