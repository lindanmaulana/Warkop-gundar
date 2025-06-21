<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index() {
        return view('dashboard.index');
    }

    public function showDashboardMenu() {
        return view('dashboard.menu.index');
    }

    public function showDashboardMenuCoffe() {
        return view('dashboard.menu.menuCoffe');
    }

    public function showDashboardAdminCategory() {
        $categories = Category::all();
        
        return view('dashboard.admin.category.index', compact('categories'));
    }
}