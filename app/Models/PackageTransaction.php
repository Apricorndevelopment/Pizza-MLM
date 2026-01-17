<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'package1_id',
        'ulid',
        'package_name',
        'price',
        'transaction_date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package1::class, 'package1_id');
    }
}