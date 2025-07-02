<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $queryCategoryId = $request->query('category');

        $categories = Category::all();

        $productsQuery = Product::with('category');

        if($queryCategoryId) {
            $productsQuery->where('category_id', $queryCategoryId);
        }

        $products = $productsQuery->get();

        return view('dashboard.menu.product.index', compact('categories', 'products'));
    }

    public function getByCategory(string $categoryId)
    {
        $category = Category::where('id', $categoryId)->firstOrFail();

        if (!$category) {
            abort(404, 'Kategori tidak ditemukan.');
        }

        $products = Product::where('category_id', $category->id)->get();

        return view('dashboard.menu.product.productByCategory', compact('category', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('dashboard.menu.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|numeric|min:0'
        ]);

        Product::create($validatedData);

        return redirect()->route('dashboard.menu.products')->with('success', 'Produk berhasil di tambahkan.');
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
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('dashboard.menu.product.update', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|numeric|min:0'
        ]);

        $product->update($validatedData);

        return redirect()->route('dashboard.menu.products')->with('success', 'Produk berhasil di perbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::transaction(function () use ($product) {

                $affectedOrderIds = OrderItem::where('product_id', $product->id)
                    ->pluck('order_id')
                    ->unique();


                OrderItem::where('product_id', $product->id)->delete();

                $product->delete(); 

                foreach ($affectedOrderIds as $orderId) {
                    $remainingItemsCount = OrderItem::where('order_id', $orderId)->count();

                    if ($remainingItemsCount === 0) {
                        Order::destroy($orderId); 
                    }
                }
            });

            return redirect()->route('dashboard.menu.products')->with('success', 'Produk dan item pesanan terkait berhasil dihapus.');
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
