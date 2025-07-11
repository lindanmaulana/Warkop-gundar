<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalPayments = Payment::count();
        $totalOrderPending = Order::where('status', 'pending')->count();
        $latestOrdersData = collect();

        $totalOrderByCustomer = 0;
        if ($user->role === UserRole::Customer) {
            $totalOrderByCustomer = Order::where('user_id', $user->id)->count();
            $latestOrdersData = Order::where('user_id', $user->id)->with('orderItems.product')->latest()->take(3)->get();
        }

        if ($user->role === UserRole::Admin) {
            $latestOrdersData = Order::with('orderItems.product')->latest()->take(3)->get();
        }

        return view('dashboard.index', compact('totalProducts', 'totalOrders', 'totalPayments' ,'totalOrderPending', 'latestOrdersData', 'totalOrderByCustomer'));
    }

    public function showDashboardMenu()
    {
        $categories = Category::all();

        $products = Product::with('category')->get();

        return view('dashboard.menu.index', compact('categories', 'products'));
    }

    public function showDashboardOrder()
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

    public function showDashboardSetting()
    {
        $user = Auth::user();

        return view('dashboard.setting', compact('user'));
    }
}
