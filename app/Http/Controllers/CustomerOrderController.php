<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function cancel(Order $order)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk membatalkan pesanan ini.');
        }

        if ($order->status !== OrderStatus::Pending) {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah diproses atau selesai.');
        }

        $order->status = OrderStatus::cancelled;
        $order->save();

        return back()->with('success', 'Pesanan berhasil di batalkan');
    }
}