<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'midtrans_transaction_id',
        'order_id',
        'midtrans_order_id',
        'payment_type',
        'transaction_time',
        'transaction_status',
        'fraud_status',
        'gross_amount',
        'currency',
        'raw_response',
    ];

    protected $casts = [
        'transaction_time' => 'datetime',
        'raw_response' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}