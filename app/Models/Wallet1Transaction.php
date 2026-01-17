<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet1Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_ulid',
        'wallet1',
        'notes',
        'balance',
        'admin_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}