<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topping;

class ToppingController extends Controller
{
    public function index()
    {
        $toppings = Topping::orderBy('nama_topping')->get();
        return view('admin.topping', compact('toppings'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_topping' => 'required|string|max:100',
                'ukuran' => 'required|in:S,M,L',
                'harga' => 'required|numeric|min:0',
            ]);

            Topping::create([
                'nama_topping' => $request->nama_topping,
                'ukuran' => $request->ukuran,
                'harga' => $request->harga,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Topping berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_topping' => 'required|string|max:100',
                'ukuran' => 'required|in:S,M,L',
                'harga' => 'required|numeric|min:0',
            ]);

            $topping = Topping::findOrFail($id);
            $topping->update([
                'nama_topping' => $request->nama_topping,
                'ukuran' => $request->ukuran,
                'harga' => $request->harga,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Topping berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $topping = Topping::findOrFail($id);
            
            // Cek apakah topping digunakan di detail_pesanan_topping
            $used = \DB::table('detail_pesanan_topping')->where('topping_id', $id)->exists();
            
            if ($used) {
                return response()->json([
                    'success' => false,
                    'message' => 'Topping tidak dapat dihapus karena sudah digunakan pada pesanan!'
                ], 422);
            }
            
            $topping->delete();

            return response()->json([
                'success' => true,
                'message' => 'Topping berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}