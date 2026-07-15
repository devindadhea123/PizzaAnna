<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    // ==================== VIEW ====================
    public function index()
    {
        $bahan = BahanBaku::orderBy('nama_bahan')->get();
        return view('admin.bahan-baku', compact('bahan'));
    }

    // ==================== API CRUD ====================
    
    // GET semua data
    public function getAll()
    {
        $bahan = BahanBaku::orderBy('nama_bahan')->get();
        return response()->json($bahan);
    }

    // GET detail
    public function show($id)
    {
        $bahan = BahanBaku::findOrFail($id);
        return response()->json($bahan);
    }

    // CREATE
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_bahan' => 'required|string|max:100|unique:bahan_baku,nama_bahan',
                'satuan' => 'required|string|max:20',
                'stok' => 'required|numeric|min:0',
                'stok_minimal' => 'required|numeric|min:0',
            ]);

            $bahan = BahanBaku::create([
                'nama_bahan' => $request->nama_bahan,
                'satuan' => $request->satuan,
                'stok' => $request->stok,
                'stok_minimal' => $request->stok_minimal,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bahan baku berhasil ditambahkan',
                'data' => $bahan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        try {
            $bahan = BahanBaku::findOrFail($id);
            
            $request->validate([
                'nama_bahan' => 'required|string|max:100|unique:bahan_baku,nama_bahan,' . $id . ',id_bahan',
                'satuan' => 'required|string|max:20',
                'stok' => 'required|numeric|min:0',
                'stok_minimal' => 'required|numeric|min:0',
            ]);

            $bahan->update([
                'nama_bahan' => $request->nama_bahan,
                'satuan' => $request->satuan,
                'stok' => $request->stok,
                'stok_minimal' => $request->stok_minimal,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bahan baku berhasil diupdate',
                'data' => $bahan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // DELETE
    public function destroy($id)
    {
        try {
            $bahan = BahanBaku::findOrFail($id);
            
            // Cek apakah bahan digunakan di resep
            if ($bahan->resep()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bahan baku tidak dapat dihapus karena masih digunakan dalam resep menu'
                ], 422);
            }
            
            $bahan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bahan baku berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // TAMBAH STOK
    public function tambahStok(Request $request, $id)
    {
        try {
            $request->validate([
                'jumlah' => 'required|numeric|min:0.01',
                'tipe' => 'nullable|string',
                'referensi' => 'nullable|string',
            ]);

            $bahan = BahanBaku::findOrFail($id);
            $stokSebelum = $bahan->stok;
            $bahan->stok += $request->jumlah;
            $bahan->save();

            // Create LogStok record
            \App\Models\LogStok::create([
                'id_bahan' => $bahan->id_bahan,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $bahan->stok,
                'perubahan' => $request->jumlah,
                'tipe' => $request->tipe ?? 'tambah',
                'referensi' => $request->referensi ?? 'Manual',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil ditambahkan dan dicatat di log',
                'data' => $bahan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // KURANGI STOK
    public function kurangiStok(Request $request, $id)
    {
        try {
            $request->validate([
                'jumlah' => 'required|numeric|min:0.01',
            ]);

            $bahan = BahanBaku::findOrFail($id);
            
            if ($bahan->stok < $request->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok saat ini: ' . $bahan->stok . ' ' . $bahan->satuan
                ], 422);
            }
            
            $bahan->stok -= $request->jumlah;
            $bahan->save();

            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil dikurangi',
                'data' => $bahan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}