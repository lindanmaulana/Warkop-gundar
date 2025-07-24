<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Throwable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.menu.product.index');
    }

    public function getAllProduct(Request $request)
    {
        $queryKeyword = $request->query("keyword");
        $queryCategory = $request->query('category');
        $queryPage = $request->query("page");
        $queryLimit = $request->query("limit");

        $page = max(1, (int)$queryPage);
        $limit = max(1, (int)$queryLimit);

        if ($limit > 20) $limit = 5;
        
        $products = Product::with('category')
                        ->latest()
                        ->when($queryCategory, function($query) use($queryCategory) {
                            $query->whereHas('category', function($q) use ($queryCategory) {
                                $q->where('name', 'like', "%{$queryCategory}%");
                            });
                        })
                        ->when($queryKeyword, function($query) use($queryKeyword) {
                            $query->where("name", "like", "%{$queryKeyword}%");
                        })
                        ->paginate($limit);

        return response()->json([
            'message' => "Data product berhasil di ambil",
            'data' => $products
        ]);
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
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0'
        ]);

        $imagePath = null;

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');

            $fileName = 'product_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            $imagePath = $image->storeAs('products', $fileName, 'public');
        }

        $product = new Product();
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->image_url = $imagePath;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->save();

        return redirect()->route('dashboard.menu.products', ['page' => 1, 'limit' => 5])->with('success', 'Produk berhasil di tambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('dashboard.menu.product.show', compact('product'));
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
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|numeric|min:0'
        ]);

        $imagePath = $product->image_url;

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');

            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }

            $fileName = 'product_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            $imagePath = $image->storeAs('products', $fileName, 'public');
        }

        unset($validatedData['image_url']);

        $product->update($validatedData);

        $product->image_url = $imagePath;
        $product->save();


        return redirect()->route('dashboard.menu.products', ['page' => 1, 'limit' => 5])->with('success', 'Produk berhasil di perbarui.');
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

                if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                    Storage::disk('public')->delete($product->image_url);
                }

                $product->delete();

                foreach ($affectedOrderIds as $orderId) {
                    $remainingItemsCount = OrderItem::where('order_id', $orderId)->count();

                    if ($remainingItemsCount === 0) {
                        Order::destroy($orderId);
                    }
                }
            });

            return redirect()->route('dashboard.menu.products', ['page' => 1, 'limit' => 5])->with('success', 'Produk dan item pesanan terkait berhasil dihapus.');
        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
