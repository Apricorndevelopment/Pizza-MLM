<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * (Optional if your table is plural 'complaints', but good for clarity)
     */
    protected $table = 'complaints';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'is_vendor',
        'subject',
        'message',
        'image',
        'status',       // pending, in_progress, resolved, rejected
        'admin_reply'
    ];

    /**
     * Relationship: Get the user that filed the complaint.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: Get the full image URL automatically.
     * Usage: $complaint->image_url
     */
    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : null;
    }
}
