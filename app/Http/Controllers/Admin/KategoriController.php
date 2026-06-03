<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['getKategoriForPublic']);
        $this->middleware('role:admin')->except(['getKategoriForPublic']);
    }

    public function index()
    {
        $kategoris = Kategori::withCount('menu')->get();
        return view('admin.kategori', compact('kategoris'));
    }

    

    public function getKategori()
    {
        $kategoris = Kategori::withCount('menu')->get();
        return response()->json($kategoris);
    }

    public function getKategoriById($id)
    {
        $kategori = Kategori::findOrFail($id);
        return response()->json($kategori);
    }

    public function storeKategori(Request $request)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:50|unique:kategori,nama_kategori',
            ]);

            $kategori = Kategori::create([
                'nama_kategori' => $request->nama_kategori,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateKategori(Request $request, $id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            
            $request->validate([
                'nama_kategori' => 'required|string|max:50|unique:kategori,nama_kategori,' . $id . ',id_kategori',
            ]);

            $kategori->update([
                'nama_kategori' => $request->nama_kategori,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteKategori($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            
            if ($kategori->menu()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak dapat dihapus karena masih memiliki menu'
                ], 422);
            }
            
            $kategori->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ PUBLIC API FOR KASIR (Tidak perlu login/role)
    public function getKategoriForPublic()
    {
        try {
            $kategoris = Kategori::all();
            return response()->json($kategoris);
        } catch (\Exception $e) {
            Log::error('getKategoriForPublic error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}