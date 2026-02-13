<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBanner extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'banner_image', 'link'];
}