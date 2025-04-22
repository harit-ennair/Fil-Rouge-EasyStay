<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'appartement_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'payment_intent_id',
        'stripe_customer_id',
        'paid_at'
    ];
    public function appartement()
    {
        return $this->belongsTo(appartements::class, 'appartement_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
