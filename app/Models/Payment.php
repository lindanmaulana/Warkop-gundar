<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'qr_code_url', 'is_active'];

    public function order() {
        return $this->hasMany(Order::class);
    }
}