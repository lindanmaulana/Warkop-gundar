<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('dashboard.category.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.category.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string'
        ]);

        Category::create($validatedData);

        return redirect()->route('dashboard.categories')->with('success', 'Kategori berhasil di tambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Category $category)
    {
        return view('dashboard.category.update', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string'
        ]);

        $category->update($validatedData);

        return redirect()->route('dashboard.categories')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        if (!$category) {
            Log::warning('Attempted to delete non-existent category.', ['category_id' => request()->route('category')]);
            return redirect()->route('dashboard.categories')->with('error', 'Kategori tidak ditemukan.');
        }

        $category->load('product');
        $productsInThisCategory = $category->product;

        Log::info('Deleting category: ' . $category->id . '. Found ' . $productsInThisCategory->count() . ' products associated.');

        if ($productsInThisCategory->isNotEmpty()) {
            foreach ($productsInThisCategory as $product) {
                if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                    Storage::disk('public')->delete($product->image_url);
                    Log::info('Deleted product image: ' . $product->image_url . ' for product ID: ' . $product->id);
                } else {
                    Log::info('Product image not found or missing URL for product ID: ' . $product->id . ' (Image URL: ' . ($product->image_url ?? 'null') . ')');
                }
            }
        } else {
            Log::info('No products found or product collection is empty for category ' . $category->id . '. No images to delete.');
        }

        $category->delete();

        Log::info('Category ' . $category->id . ' and associated product images deleted successfully.');

        return redirect()->route('dashboard.categories')->with('success', 'Kategori berhasil di hapus.');
    }

    public function getAllCategory()
    {
        $categories = Category::all();

        return response()->json([
            "message" => "Data kategori berhasil diambil.",
            "data" => $categories
        ]);
    }
}
