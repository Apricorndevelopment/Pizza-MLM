<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'ulid',
        'email',
        'password',
        'phone',
        'address',
        'state',
        'status',
        'sponsor_id',
        'role',
        'wallet1_balance',
        'wallet2_balance',
        'is_vendor',
        'user_doa',
        'profile_picture',
        'current_rank',
        'adhar_no',
        'pan_no',
        'adhar_photo',
        'pan_photo',
        'nom_name',
        'nom_relation',
        'bank_name',
        'account_no',
        'ifsc_code',
        'upi_id',
        'passbook_photo',
        'left_business',
        'right_business',
    ];

    public function pointsTransactions()
    {
        return $this->hasMany(Wallet1Transaction::class);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
