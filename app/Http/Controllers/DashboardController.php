<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}