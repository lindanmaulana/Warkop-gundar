<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function handleMidtransCallback(Request $request)
    {

        $notification = new Notification();

        $serverKey = config('midtrans.server_key');
        $signature = hash(
            'sha512',
            $request->order_id .
                $request->status_code .
                $request->gross_amount .
                $serverKey
        );

        if ($signature !== $notification->signature_key) {
            Log::warning('Invalid signature (manual check)', [
                'expected' => $signature,
                'provided' => $notification->signature_key,
                'midtrans_notification_data' => $request->all()
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        Log::info('Midtrans callback received', $request->all());
        Log::info("order_id: " . $request->order_id);
        Log::info("status_code: " . $request->status_code);
        Log::info("gross_amount: " . $request->gross_amount);
        Log::info("server_key: " . config('midtrans.server_key'));
        Log::info("signature from midtrans: " . $request->signature_key);
        Log::info("your generated signature: " . $signature);



        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $orderId = $notification->order_id;
        $midtransTransactionId = $notification->transaction_id; 
        $grossAmount = $notification->gross_amount; 
        $fraudStatus = $notification->fraud_status;
        $currency = $notification->currency;
        $transactionTime = $notification->transaction_time;
        $statusCode = $notification->status_code;
        $statusMessage = $notification->status_message;


        $id = (int)$orderId;
        $order = Order::find($id);

        if (!$order) {
            Log::error('Order not found with ID:', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($transactionStatus == 'settlement') {
            $order->status = OrderStatus::Processing;
        } elseif ($transactionStatus == 'pending') {
            $order->status = OrderStatus::Pending;
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'refund'])) {
            $order->status = OrderStatus::cancelled;
        } else {
            $order->status = OrderStatus::Pending;
        }

        $order->save();

        $transaction = Transaction::updateOrCreate(
            [
                'midtrans_transaction_id' => $midtransTransactionId,
            ],
            [
                'order_id' => $order->id,
                'midtrans_order_id' => $orderId, 
                'payment_type' => $paymentType,
                'transaction_time' => $transactionTime,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'gross_amount' => (float)$grossAmount, 
                'currency' => $currency,
                'status_code' => $statusCode,
                'status_message' => $statusMessage,
                'raw_response' => json_encode($request->all()),
            ]
        );

        return response()->json(['message' => 'Callback processed'], 200);
    }
}
