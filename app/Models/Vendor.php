<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';

    protected $fillable = [
        'user_id',
        'vendor_name',
        'company_name',
        'comany_address',
        'company_city',
        'company_state',
        'zip_code',
        'status',
        'gst',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
