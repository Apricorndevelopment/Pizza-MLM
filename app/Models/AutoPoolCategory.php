<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoPoolCategory extends Model
{
    use HasFactory;

    protected $table = 'auto_pool_categories';

    protected $fillable = [
        'category_name',
        'product_package_id',
        'pv_required',
        'direct_count',
        'each_direct_pv',
        'is_active',
    ];

    // Relationship with ProductPackage
    public function package()
    {
        return $this->belongsTo(ProductPackage::class, 'product_package_id');
    }

    public function pools()
    {
        return $this->hasMany(AutoPool::class, 'category_id');
    }
}