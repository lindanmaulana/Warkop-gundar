<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productsLatest = Product::latest()->take(3)->get();
        $productsForYou = Product::latest()->skip(3)->take(9)->get();
        return view('home.index', compact('productsLatest', 'productsForYou'));
    }

    public function showMenu() {

        $products = Product::where('stock', '>', 0)->get();

        $productsFood = Product::whereHas('category', function($query) {
            $query->where('name', 'makanan');
        })->get();

        $productsCoffe = Product::whereHas('category', function($query) {
            $query->where('name', 'minuman');
        })->get();

        return view('home.menu', compact('products', 'productsFood', 'productsCoffe'));
    }

    public function showCart() {
        return view('home.cart');
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
}
