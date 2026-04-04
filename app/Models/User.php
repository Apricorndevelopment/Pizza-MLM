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
        'city',
        'state',
        'is_paid',
        'status',
        'sponsor_id',
        'parent_id',
        'role',
        'wallet1_balance',
        'wallet2_balance',
        'vendor_wallet_balance',
        'is_vendor',
        'user_doa',
        'profile_picture',
        'current_rank',
        'adhar_no',
        'pan_no',
        'adhar_photo',
        'adhar_back_photo',
        'pan_photo',
        'nom_name',
        'nom_relation',
        'bank_name',
        'account_no',
        'ifsc_code',
        'upi_id',
        'passbook_photo',
        'total_business',
        'single_leg_pv',
        'capping_limit',
        'is_capping_enabled',
        'capping_product_id',
    ];

    public function pointsTransactions()
    {
        return $this->hasMany(Wallet1Transaction::class);
    }

    public function autopoolTracker()
    {
        return $this->hasOne(UserAutopoolTracker::class, 'user_id');
    }

    // User's Auto Pool Earnings History
    public function autopoolEarnings()
    {
        return $this->hasMany(AutopoolEarningsHistory::class, 'user_id');
    }

    public function cappingProduct()
    {
        return $this->belongsTo(ProductPackage::class, 'capping_product_id');
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

    // Add this inside App\Models\User.php
    public function vendor()
    {
        // Assuming your vendors table has a 'user_id' column
        return $this->hasOne(Vendor::class, 'user_id');
        
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
