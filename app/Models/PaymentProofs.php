<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProofs extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'image_url', 'verified'];

    public function order() {
        return $this->belongsTo(Order::class);
    }
}