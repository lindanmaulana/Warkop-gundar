<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(string $categoryId)
    {
        $category = Category::find($categoryId);

        if(!$category) {
            abort(404, 'Kategori tidak ditemukan.');
        }

        $products = Product::where('category_id', $categoryId)->get();

        return view('dashboard.menu.product', compact('category','products'));
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

        return redirect()->route('dashboard.menu')->with('success', 'Produk berhasil di tambahkan.');
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

        return redirect()->route('dashboard.menu')->with('success', 'Produk berhasil di perbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Product::destroy($product->id);

        return redirect()->route('dashboard.menu')->with('success', 'Produk berhasil di hapus.');
    }
}
