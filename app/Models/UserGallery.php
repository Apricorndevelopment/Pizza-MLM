<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGallery extends Model
{
    protected $table = 'user_gallery';

    protected $fillable = ['title','photo'];

}
