<?php

namespace App\Models;

use App\Enums\BranchWarkop;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'payment_id', 'delivery_location', 'branch', 'total_price', 'description', 'status'];

    protected function casts(): array
    {
        return [
            'branch' => BranchWarkop::class,
            'status' => OrderStatus::class
        ];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function payment() {
        return $this->belongsTo(Payment::class);
    }

    public function orderItem() {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentProofs() {
        return $this->hasMany(PaymentProofs::class);
    }
}