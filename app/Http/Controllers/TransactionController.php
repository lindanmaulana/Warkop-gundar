<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;

class TransactionController extends Controller
{

    public function __construct(private OrderServices $orderService)
    {
        // Properti $orderService akan otomatis dibuat dan diisi
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getSnapToken(Request $request)
    {
        $validatedData = $request->validate([
            "order_id" => "required|integer|exists:orders,id",
        ]);

        $user = Auth::user();

        $order = Order::where('id', $validatedData['order_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $itemsDetail = $this->orderService->getItemDetails($order);

        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $order->total_price
            ],
            'customer_details' => [
                'name' => $user->name,
                'email' => $user->email
            ],
            'item_details' => $itemsDetail 
        ];

        try {
            $snapToken = Snap::getSnapToken($params); 

            return response()->json([
                'message' => 'Transaction',
                'snapToken' => $snapToken,
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mendapatkan Snap Token: ' . $e->getMessage()], 500);
        }
    }
}
