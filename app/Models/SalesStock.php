<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesStock extends Model
{
    use HasFactory;

    protected $table = 'sales_stock';

    protected $fillable = [
        'location',
        'user_ulid',
        'user_id',
        'product_id',
        'product_name',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}