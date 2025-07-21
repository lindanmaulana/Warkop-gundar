<?php

namespace App\Services;

use App\Models\Order;

class OrderServices
{
    public function getItemDetails(Order $order)
    {
        $items = [];
        foreach ($order->orderItems as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int)$item->price,
                'quantity' => (int)$item->qty,
                'name' => $item->product->name
            ];
        }
        return $items;
    }
}
