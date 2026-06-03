<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Menu;

class MenuApiController extends Controller
{
    // ==================== GET SEMUA KATEGORI ====================
    public function kategori()
    {
        $kategoris = Kategori::all();
        return response()->json($kategoris);
    }

    // ==================== GET SEMUA MENU ====================
    public function menu()
    {
        $menus = Menu::with(['kategori', 'pizzaUkuran'])->get();
        
        return response()->json($menus);
    }

    // ==================== GET MENU BERDASARKAN KATEGORI ====================
  public function menuByKategori($id)
{
    $menus = Menu::with(['kategori', 'pizzaUkuran'])
        ->where('id_kategori', $id)
        ->get();

    return response()->json($menus);
}
}