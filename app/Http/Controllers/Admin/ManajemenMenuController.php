<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\PizzaUkuran;
use App\Models\DetailPesanan; // ✅ TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ManajemenMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    // Views
    public function index()
    {
        $kategoris = Kategori::all();
        return view('admin.manajemen-menu', compact('kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.menu.create', compact('kategoris'));
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $kategoris = Kategori::all();
        return view('admin.menu.edit', compact('menu', 'kategoris'));
    }

    // ==================== API METHODS ====================

    public function getMenu(Request $request)
    {
        $query = Menu::with(['kategori', 'pizzaUkuran']);
        
        if ($request->kategori && $request->kategori != 'all') {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->search) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }
        
        $menus = $query->orderBy('nama_menu', 'asc')->paginate(15);
        
        return response()->json($menus);
    }

    public function getMenuById($id)
    {
        $menu = Menu::with(['kategori', 'pizzaUkuran'])->findOrFail($id);
        return response()->json($menu);
    }

    // ==================== CEK APAKAH MENU SUDAH DIPESAN ====================
    public function cekDipesan($id)
    {
        try {
            $jumlah = DetailPesanan::where('id_menu', $id)->count();
            
            return response()->json([
                'success' => true,
                'sudah_dipesan' => $jumlah
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'sudah_dipesan' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== STORE MENU ====================
    public function storeMenu(Request $request)
    {
        try {
            $request->validate([
                'nama_menu' => 'required|string|max:100',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'diskon_jenis' => 'required|in:none,persen',
                'diskon_nilai' => 'required_if:diskon_jenis,persen|numeric|min:0|max:100',
            ]);

            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
                $gambarPath = $file->storeAs('menu', $fileName, 'public');
            }

            $menu = Menu::create([
                'nama_menu' => $request->nama_menu,
                'harga' => 0,
                'id_kategori' => $request->id_kategori,
                'diskon_jenis' => $request->diskon_jenis ?? 'none',
                'diskon_nilai' => $request->diskon_nilai ?? 0,
                'gambar' => $gambarPath,
                'deskripsi' => $request->deskripsi,
            ]);

            if ($request->id_kategori == 1) {
                if ($request->harga_s) PizzaUkuran::create(['id_menu' => $menu->id_menu, 'ukuran' => 'S', 'harga' => $request->harga_s]);
                if ($request->harga_m) PizzaUkuran::create(['id_menu' => $menu->id_menu, 'ukuran' => 'M', 'harga' => $request->harga_m]);
                if ($request->harga_l) PizzaUkuran::create(['id_menu' => $menu->id_menu, 'ukuran' => 'L', 'harga' => $request->harga_l]);
            }

            return response()->json(['success' => true, 'message' => 'Menu berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ==================== UPDATE MENU ====================
    public function updateMenu(Request $request, $id)
    {
        try {
            $menu = Menu::findOrFail($id);
            
            if ($request->id_kategori != 1) {
                $request->validate([
                    'nama_menu' => 'required|string|max:100',
                    'harga' => 'required|numeric|min:0',
                    'id_kategori' => 'required|exists:kategori,id_kategori',
                    'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'diskon_jenis' => 'required|in:none,persen',
                    'diskon_nilai' => 'required_if:diskon_jenis,persen|numeric|min:0|max:100',
                ]);
            } else {
                $request->validate([
                    'nama_menu' => 'required|string|max:100',
                    'id_kategori' => 'required|exists:kategori,id_kategori',
                    'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'diskon_jenis' => 'required|in:none,persen',
                    'diskon_nilai' => 'required_if:diskon_jenis,persen|numeric|min:0|max:100',
                ]);
            }

            $hargaValue = ($request->id_kategori == 1) ? 0 : $request->harga;
            
            $data = [
                'nama_menu' => $request->nama_menu,
                'harga' => $hargaValue,
                'id_kategori' => $request->id_kategori,
                'diskon_jenis' => $request->diskon_jenis ?? 'none',
                'diskon_nilai' => $request->diskon_nilai ?? 0,
                'deskripsi' => $request->deskripsi,
            ];

            if ($request->hasFile('gambar')) {
                if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                    Storage::disk('public')->delete($menu->gambar);
                }
                $file = $request->file('gambar');
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
                $data['gambar'] = $file->storeAs('menu', $fileName, 'public');
            }

            $menu->update($data);

            if ($request->id_kategori == 1) {
                PizzaUkuran::where('id_menu', $menu->id_menu)->delete();
                
                if ($request->harga_s && $request->harga_s > 0) {
                    PizzaUkuran::create(['id_menu' => $menu->id_menu, 'ukuran' => 'S', 'harga' => $request->harga_s]);
                }
                if ($request->harga_m && $request->harga_m > 0) {
                    PizzaUkuran::create(['id_menu' => $menu->id_menu, 'ukuran' => 'M', 'harga' => $request->harga_m]);
                }
                if ($request->harga_l && $request->harga_l > 0) {
                    PizzaUkuran::create(['id_menu' => $menu->id_menu, 'ukuran' => 'L', 'harga' => $request->harga_l]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== DELETE / NONAKTIFKAN MENU ====================
    public function deleteMenu($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            
            $sudahDipesan = DetailPesanan::where('id_menu', $id)->exists();
            
            if ($sudahDipesan) {
                
                $menu->delete(); 
                
                return response()->json([
                    'success' => true,
                    'message' => 'Menu berhasil dinonaktifkan! Data historis tetap tersimpan.'
                ]);
            } else {
               
                PizzaUkuran::where('id_menu', $menu->id_menu)->delete();
                
                // Hapus gambar
                if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                    Storage::disk('public')->delete($menu->gambar);
                }
                
                // Hapus menu
                $menu->forceDelete(); // Hard delete

                return response()->json([
                    'success' => true,
                    'message' => 'Menu berhasil dihapus permanen!'
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}