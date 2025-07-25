<?php

namespace App\Http\Controllers;

use App\Enums\BranchWarkop;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentProofs;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allProducts = Product::count();
        $productsLatest = Product::latest()->take(3)->get();

        if ($allProducts <= 3) {
            $productsForYou = $productsLatest;
        } else {
            $productsForYou = Product::latest()->skip(3)->take(6)->get();
        }

        return view('home.index', compact('productsLatest', 'productsForYou'));
    }

    public function showOurLocation()
    {

        return view("home/ourLocation");
    }

    public function showMenu()
    {

        $products = Product::where('stock', '>', 0)->get();

        $productsFood = Product::whereHas('category', function ($query) {
            $query->where('name', 'makanan');
        })->latest()->take(5)->get();

        $productsCoffe = Product::whereHas('category', function ($query) {
            $query->where('name', 'minuman');
        })->latest()->take(5)->get();

        return view('home.menu', compact('products', 'productsFood', 'productsCoffe'));
    }

    public function showMenuDetail(Product $product)
    {
        $product->with("category")->get();

        return view('/home/menuDetail', compact('product'));
    }

    public function showCart()
    {
        return view('home.cart');
    }

    public function showCheckout()
    {
        return view('home.checkout');
    }

    public function showProfile()
    {

        $user = Auth::user();
        return view('home.profile', compact('user'));
    }

    public function showOrder()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->get();

        return view('home.order', compact('orders'));
    }

    public function showDetailOrder(Order $order)
    {
        $order->load('orderItems.product');
        $transactionTime = "N/A";
        $expiryTime = "N/A";
        $settlementTime = "N/A";

        if ($order->transactions) {
            if ($order->transactions->raw_response) {
                $order->transactions->parsed_raw_response = json_decode($order->transactions->raw_response, true);
            } else {
                $order->transactions->parsed_raw_response = [];
            }

            if ($order->transactions->transaction_time) {
                $transactionTime = Carbon::parse($order->transactions->transaction_time)
                    ->setTimezone('Asia/Jakarta')
                    ->format('d M Y, H:i:s') . ' WIB';
            }

            if (isset($order->transactions->parsed_raw_response['expiry_time'])) {
                $expiryTime = Carbon::parse($order->transactions->parsed_raw_response['expiry_time'])
                    ->setTimezone('Asia/Jakarta')
                    ->format('d M Y, H:i:s') . ' WIB';
            }

            if (isset($order->transactions->parsed_raw_response['settlement_time'])) {
                $settlementTime = Carbon::parse($order->transactions->parsed_raw_response['settlement_time'])
                    ->setTimezone('Asia/Jakarta')
                    ->format('d M Y, H:i:s') . ' WIB';
            }
        };

        return view('home.orderDetail', compact('order', 'transactionTime', 'expiryTime', 'settlementTime'));
    }

    public function showPayment(Order $order)
    {
        $payments = Payment::where('is_active', 1)->get();
        $paymentProof = PaymentProofs::where('order_id', $order->id)->first();

        return view('home.payment', compact('order', 'payments', 'paymentProof'));
    }

    public function showTransaction(Order $order)
    {
        $order = Order::with("orderItems.product")->findOrFail($order->id);

        return view("home.transaction", compact("order"));
    }

    public function showTransactionHistory(Order $order)
    {
        $order->load("transactions", 'user');
        $provider = "Unknow";
        $transactionTime = "N/A";
        $settlementTime = "N/A";

        if ($order->transactions) {
            if ($order->transactions->raw_response) {
                $order->transactions->parsed_raw_response = json_decode($order->transactions->raw_response, true);
            } else {
                $order->transactions->parsed_raw_response = [];
            }

            switch ($order->transactions->payment_type) {
                case "bank_transfer":
                    if (isset($order->transactions->parsed_raw_response['va_numbers'])) {
                        $provider = $order->transactions->parsed_raw_response['va_numbers'][0]['bank'];
                    } elseif (isset($order->transactions->parsed_raw_response['permata_va_number'])) {
                        $provider = 'Permata';
                    }
                    break;
                case "qris":
                    $provider = $order->transactions->parsed_raw_response['issuer'];
                    break;
                default:
                    $provider = "Unknow";
            }


            if ($order->transactions->transaction_time) {
                $transactionTime = Carbon::parse($order->transactions->transaction_time)
                    ->setTimezone('Asia/Jakarta')
                    ->format('d M Y, H:i:s') . ' WIB';
            }

            if (isset($order->transactions->parsed_raw_response['settlement_time'])) {
                $settlementTime = Carbon::parse($order->transactions->parsed_raw_response['settlement_time'])
                    ->setTimezone('Asia/Jakarta')
                    ->format('d M Y, H:i:s') . ' WIB';
            }
        } else {
            return redirect()->route("home.order")->with("error", "Pembayaran di batalkan.");
        }

        return view("/home/transactionHistory", compact("order", "provider", "transactionTime", "settlementTime"));
    }

    public function createOrder(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'cart' => 'required|array|min:1',
                'cart.*.productId' => 'required|integer|exists:products,id',
                'cart.*.qty' => 'required|integer|min:1',
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

    public function updateProfile(Request $request, User $user)
    {
        $userValidated = $request->validate([
            'name' => 'required|string',
        ]);

        $user->update($userValidated);

        return redirect()->route('home.profile')->with('success', 'Profile berhasil di perbarui.');
    }

    public function cancelOrder(Order $order)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            return response()->json([
                'error' => "Anda tidak diizinkan untuk membatalkan pesanan ini.",
            ]);
        }

        if ($order->status !== OrderStatus::Pending && $order->status != OrderStatus::cancelled) {
            return response()->json([
                'error' => "Pesanan sudah diproses dan tidak bisa dibatalkan.",
            ]);
        }

        if ($order->status === OrderStatus::cancelled) {
            return response()->json([
                'error' => "Status Pesanan telah batal.",
            ]);
        }

        try {
            DB::beginTransaction();
            $order->status = OrderStatus::cancelled;
            $order->save();

            foreach ($order->orderItems as $item) {
                $product = Product::find($item->product_id);

                if ($product) {
                    $product->stock += $item->qty;
                    $product->save();
                    Log::info("Stok produk '{$product->name}' ditambahkan {$item->qty} karena pembatalan pesanan {$order->id}.");
                }
            }

            DB::commit();

            Log::channel('order_activity')->info("Pesanan ID: {$order->id} dibatalkan oleh Pengguna ID: {$user->id}.");

            return response()->json([
                'message' => "Pesanan berhasil di batalkan.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal membatalkan pesanan {$order->id}: " . $e->getMessage());
            return response()->json([
                'error' => "Terjadi kesalahan saat membatalkan pesanan. Silakan coba lagi.",
            ]);
        }
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

    public function getAllMenu(Request $request)
    {
        $queryPage = $request->query("page");
        $queryLimit = $request->query("limit");
        $queryKeyword = $request->query("keyword");
        $queryCategory = $request->query("category");

        $limit = max(1, (int)$queryLimit);

        if ($limit > 20) $limit = 5;

        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->when($queryKeyword, function ($query) use ($queryKeyword) {
                $query->where("name", "like", "%{$queryKeyword}%");
            })
            ->when($queryCategory, function ($query) use ($queryCategory) {
                $query->whereHas('category', function ($q) use ($queryCategory) {
                    $q->where('name', 'like', "%{$queryCategory}%");
                });
            })
            ->paginate($limit);

        $productsFood = Product::whereHas('category', function ($query) {
            $query->where('name', 'makanan');
        })->get();

        $productsCoffe = Product::whereHas('category', function ($query) {
            $query->where('name', 'minuman');
        })->get();

        return response()->json([
            "message" => "Data produk berhasil di ambil.",
            'data' => [
                'pagination' => $products,
                'productsFood' => $productsFood,
                'productsCoffe' => $productsCoffe
            ]
        ]);
    }
}
