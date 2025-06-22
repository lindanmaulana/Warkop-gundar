<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index() {
        $totalCategory = Category::count();
        $categories = Category::withCount('product')->get();

        return view('dashboard.index', compact('categories', 'totalCategory'));
    }

    public function showDashboardMenu() {
        $categories = Category::all();

        $products = Product::with('category')->get();

        return view('dashboard.menu.index', compact('categories', 'products'));
    }

    public function showDashboardAdminCategory() {
        $categories = Category::all();
        
        return view('dashboard.admin.category.index', compact('categories'));
    }
}