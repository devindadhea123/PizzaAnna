<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function getMenu($category = null)
    {
        if ($category && $category != 'all') {
            $menus = Menu::whereHas('kategori', function($q) use ($category) {
                $q->where('nama_kategori', $category);
            })->with('kategori')->get();
        } else {
            $menus = Menu::with('kategori')->get();
        }
        
        return response()->json($menus);
    }
}