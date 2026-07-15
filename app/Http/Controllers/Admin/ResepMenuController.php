<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResepMenu;
use App\Models\Menu;
use App\Models\BahanBaku;
use Illuminate\Http\Request;

class ResepMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    // ==================== VIEW ====================
    public function index()
    {
        $resep = ResepMenu::with(['menu', 'bahanBaku'])->get();
        $menus = Menu::orderBy('nama_menu')->get();
        $bahan = BahanBaku::orderBy('nama_bahan')->get();
        return view('admin.resep-menu', compact('resep', 'menus', 'bahan'));
    }

    // ==================== VIEW CREATE ====================
    public function create()
    {
        $menus = Menu::orderBy('nama_menu')->get();
        $bahan = BahanBaku::orderBy('nama_bahan')->get();
        return view('admin.resep-menu-create', compact('menus', 'bahan'));
    }

    // ==================== VIEW EDIT ====================
public function edit($id)
{
    // ✅ Ambil resep berdasarkan id_resep untuk mendapatkan id_menu
    $resepPertama = ResepMenu::findOrFail($id);
    $menuId = $resepPertama->id_menu;
    $ukuran = $resepPertama->ukuran;
    
    // ✅ Ambil SEMUA resep untuk menu & ukuran yang sama
    $resep = ResepMenu::where('id_menu', $menuId)
        ->where(function($q) use ($ukuran) {
            if ($ukuran) {
                $q->where('ukuran', $ukuran);
            } else {
                $q->whereNull('ukuran');
            }
        })
        ->get();
    
    $menu = Menu::find($menuId);
    $bahan = BahanBaku::orderBy('nama_bahan')->get();
    $resepId = $id;
    
    return view('admin.resep-menu-edit', compact('resep', 'menu', 'bahan', 'ukuran', 'resepId'));
}

    // ==================== API GET ALL ====================
    public function getAll()
    {
        $resep = ResepMenu::with(['menu', 'bahanBaku'])->get();
        return response()->json($resep);
    }

    // ==================== API GET DETAIL ====================
    public function show($id)
    {
        $resep = ResepMenu::with(['menu', 'bahanBaku'])->findOrFail($id);
        return response()->json($resep);
    }

    // ==================== API GET BY MENU ====================
    public function getByMenu($id)
    {
        $resep = ResepMenu::with('bahanBaku')
            ->where('id_menu', $id)
            ->get();
        return response()->json($resep);
    }

    // ==================== STORE MULTIPLE BAHAN ====================
    public function storeBulk(Request $request)
    {
        try {
            $request->validate([
                'id_menu' => 'required|exists:menu,id_menu',
                'ukuran' => 'nullable|in:S,M,L',
                'resep' => 'required|array|min:1',
                'resep.*.id_bahan' => 'required|exists:bahan_baku,id_bahan',
                'resep.*.jumlah' => 'required|numeric|min:0.01',
                'resep.*.satuan' => 'required|string|max:20',
            ]);

            $menuId = $request->id_menu;
            $ukuran = $request->ukuran;

            // Hapus resep lama untuk menu & ukuran ini
            ResepMenu::where('id_menu', $menuId)
                ->where(function($q) use ($ukuran) {
                    if ($ukuran) {
                        $q->where('ukuran', $ukuran);
                    } else {
                        $q->whereNull('ukuran');
                    }
                })
                ->delete();

            // Simpan resep baru
            $saved = 0;
            foreach ($request->resep as $item) {
                ResepMenu::create([
                    'id_menu' => $menuId,
                    'id_bahan' => $item['id_bahan'],
                    'ukuran' => $ukuran,
                    'jumlah' => $item['jumlah'],
                    'satuan' => $item['satuan'],
                ]);
                $saved++;
            }

            return response()->json([
                'success' => true,
                'message' => 'Resep berhasil disimpan (' . $saved . ' bahan)'
            ]);

        } catch (\Exception $e) {
            \Log::error('storeBulk error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== UPDATE MULTIPLE BAHAN ====================
    public function updateBulk(Request $request, $id)
    {
        try {
            $request->validate([
                'id_menu' => 'required|exists:menu,id_menu',
                'ukuran' => 'nullable|in:S,M,L',
                'resep' => 'required|array|min:1',
                'resep.*.id_bahan' => 'required|exists:bahan_baku,id_bahan',
                'resep.*.jumlah' => 'required|numeric|min:0.01',
                'resep.*.satuan' => 'required|string|max:20',
            ]);

            $menuId = $request->id_menu;
            $ukuran = $request->ukuran;

            // Hapus resep lama untuk menu & ukuran ini
            ResepMenu::where('id_menu', $menuId)
                ->where(function($q) use ($ukuran) {
                    if ($ukuran) {
                        $q->where('ukuran', $ukuran);
                    } else {
                        $q->whereNull('ukuran');
                    }
                })
                ->delete();

            // Simpan resep baru
            $saved = 0;
            foreach ($request->resep as $item) {
                ResepMenu::create([
                    'id_menu' => $menuId,
                    'id_bahan' => $item['id_bahan'],
                    'ukuran' => $ukuran,
                    'jumlah' => $item['jumlah'],
                    'satuan' => $item['satuan'],
                ]);
                $saved++;
            }

            return response()->json([
                'success' => true,
                'message' => 'Resep berhasil diupdate (' . $saved . ' bahan)'
            ]);

        } catch (\Exception $e) {
            \Log::error('updateBulk error: ' . $e->getMessage() . ' - Line: ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== DELETE RESEP ====================
    public function destroy($id)
    {
        try {
            // Hapus semua resep untuk menu ini (karena 1 menu bisa punya banyak bahan)
            $resep = ResepMenu::findOrFail($id);
            $menuId = $resep->id_menu;
            $ukuran = $resep->ukuran;
            
            ResepMenu::where('id_menu', $menuId)
                ->where(function($q) use ($ukuran) {
                    if ($ukuran) {
                        $q->where('ukuran', $ukuran);
                    } else {
                        $q->whereNull('ukuran');
                    }
                })
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Resep berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}