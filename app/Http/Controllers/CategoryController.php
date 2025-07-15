<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

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
        Category::destroy($category->id);

        return redirect()->route('dashboard.categories')->with('success', 'Kategori berhasil di hapus.');
    }

    public function getAllCategory () {
        $categories = Category::all();

        return response()->json([
            "message" => "Data kategori berhasil diambil.",
            "data" => $categories
        ]);
    }
}