<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaturityMonthlyDeduction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maturity_monthly_deductions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_ulid',
        'package2_purchase_id',
        'deduction_amount',
        'penalty_amount',
        'total_deduction',
        'deduction_month',
        'months_remaining',
        'status',
        'deducted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deduction_amount' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'deducted_at' => 'datetime',
    ];

    /**
     * Get the user that owns the deduction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the package purchase that this deduction belongs to.
     */
    public function packagePurchase(): BelongsTo
    {
        return $this->belongsTo(ProductPackagePurchase::class, 'package2_purchase_id');
    }

}