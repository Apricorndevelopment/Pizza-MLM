<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet2Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_ulid',
        'wallet2',
        'notes',
        'admin_id',
        'balance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}