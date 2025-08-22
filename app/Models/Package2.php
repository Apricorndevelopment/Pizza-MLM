<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package2 extends Model
{
    protected $table = 'package2';
    protected $fillable = ['package_name', 'package_quantity', 'description', 'price', 'maturity'];

    public $timestamps = false;

    public function details()
    {
        return $this->hasMany(Package2Details::class, 'package2_id');
    }

}
