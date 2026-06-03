<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('kasir.menu-pesanan');
            }
        }
        return redirect()->route('login');
    }
}