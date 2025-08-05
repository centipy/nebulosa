<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'customer_type',
        'full_name',
        'address',
        'city',
        'state',
        'zip_code',
        'status',
        'first_name',
        'middle_name',
        'paternal_last_name',
        'maternal_last_name',
        'email',
        'home_phone',
        'work_phone',
        'mobile_phone',
        'distributor',
        'seller',
        'level',
        'credit_limit',
        'date_increased',
        'increased_by',
        'available_credit',
        'monthly_payment',
        'closing_date',
        'current_balance',
        'current_amount',
        'due_0_30_days',
        'due_31_60_days',
        'due_61_90_days',
        'due_over_90_days',
        'original_order_date',
        'last_purchase_date',
        'last_payment_date',
        'orders',
        'birthday', // Nuevo campo
        'telemarketing_observations', // Nuevo campo
        'advisor_observations', // Nuevo campo
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_increased' => 'date',
        'closing_date' => 'date',
        'original_order_date' => 'date',
        'last_purchase_date' => 'date',
        'last_payment_date' => 'date',
        'birthday' => 'date', // Castear como fecha
    ];
}
