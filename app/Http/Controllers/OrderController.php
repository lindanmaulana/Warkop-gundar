<?php

namespace App\Http\Controllers;

use App\Enums\BranchWarkop;
use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentProofs;
use App\Models\Product;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        if (!Auth::check()) redirect()->route('auth.login')->with('error', 'Anda harus login untuk mengakses halaman ini.');

        $user = Auth::user();
        $orders = collect();

        if ($user->role === UserRole::Customer) {
            $orders = Order::where('user_id', $user->id)->with('orderItems')->get();
        } else {

            $orders = Order::with('user', 'orderItems')->get();
        }

        return view('dashboard.order.index', compact('orders'));
    }

    public function getDetailOrder(Order $order)
    {
        $order->load('orderItems.product');
        $paymentProofs = PaymentProofs::where('order_id', $order->id)->first();

        return view('dashboard.order.detail', compact('order', 'paymentProofs'));
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
        try {
            $validatedData = $request->validate([
                'cart' => 'required|array|min:1',
                'cart.*.productId' => 'required|integer|exists:products,id',
                'cart.*.qty' => 'required|integer|min:1',
                'customer_information.customer_name' => 'required|string',
                'customer_information.branch' => ['required', new Enum(BranchWarkop::class)],
                'customer_information.delivery_location' => 'required|string',
                'customer_information.description' => 'nullable|string'
            ]);
        } catch (ValidationException $err) {
            return response()->json([
                'message' => 'Data order tidak valid.',
                'errors' => $err->errors()
            ], 422);
        }

        $cartItems = $validatedData['cart'];
        $customerInformation = $validatedData['customer_information'];
        $user = $request->user();

        DB::beginTransaction();

        try {
            $totalOrderPrice = 0;
            $orderItemsData = [];


            foreach ($cartItems as $item) {
                $product = Product::find($item['productId']);

                if (!$product) throw new \Exception("Produk dengan ID '{$item['productId']}' tidak di temukan.");

                if ($product->stock < $item['qty']) throw new \Exception("Stok produk '{$product->name}' tidak mencukupi. Tersisa: {$product->stock}.");

                $itemPrice = $product->price;
                $itemTotalPrice = $itemPrice * $item['qty'];
                $totalOrderPrice += $itemTotalPrice;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $itemPrice,
                ];

                $product->stock -= $item['qty'];
                $product->save();
            }

            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $customerInformation['customer_name'],
                'delivery_location' => $customerInformation['delivery_location'],
                'branch' => $customerInformation['branch'],
                'total_price' => $totalOrderPrice,
                'description' => $customerInformation['description'],
                'status' => OrderStatus::Pending->value
            ]);

            foreach ($orderItemsData as &$orderItem) {
                $orderItem['order_id'] = $order->id;
            }
            unset($orderItem);

            OrderItem::insert($orderItemsData);

            DB::commit();

            return response()->json([
                'message' => 'Order berhasil dibuat!',
                'order_id' => $order->id,
                'customer_name' => $order->customer_name,
                'delivery_location' => $order->delivery_location,
                'branch' => $order->branch,
                'total_price' => $order->total_price,
                'description' => $order->description,
                'status' => $order->status->value
            ], 200);
        } catch (\Exception $err) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal membuat order.',
                'error' => $err->getMessage()
            ], 500);
        }
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
    public function edit(Order $order)
    {

        $paymentProof = PaymentProofs::where('order_id', $order->id)->get();
        return view('dashboard.order.update', compact('order', 'paymentProof'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {

        if ($order->status === "done" || $order->status === "cancelled") return redirect()->route('dashboard.order', compact('order'))->with('error', 'Gagal perbarui status order.');

        $validatedData = $request->validate([
            'status' => ['required', new Enum(OrderStatus::class)],
        ]);

        $order->update($validatedData);

        return redirect()->route('dashboard.orders')->with('success', 'Order status berhasil di perbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function showOrderCart()
    {
        return view('dashboard.order.cart');
    }

    public function showOrderCheckout()
    {
        if (!Auth::check()) redirect()->route('auth.login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        $user = Auth::user();

        return view('dashboard.order.checkout', compact('user'));
    }
}
