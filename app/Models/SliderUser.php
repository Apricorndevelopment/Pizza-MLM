<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderUser extends Model
{
    use HasFactory;

    protected $table = 'slider_users';
    protected $fillable = ['name', 'rank', 'photo'];
}