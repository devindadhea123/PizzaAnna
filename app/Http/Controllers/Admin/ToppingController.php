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
        $request->validate([
            'nama_topping' => 'required',
            'ukuran' => 'required',
            'harga' => 'required|numeric',
        ]);

        Topping::create([
            'nama_topping' => $request->nama_topping,
            'ukuran' => $request->ukuran,
            'harga' => $request->harga,
        ]);

        return back()->with('success', 'Topping berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $topping = Topping::findOrFail($id);

        $topping->update([
            'nama_topping' => $request->nama_topping,
            'ukuran' => $request->ukuran,
            'harga' => $request->harga,
        ]);

        return back()->with('success', 'Topping berhasil diupdate');
    }

    public function destroy($id)
    {
        $topping = Topping::findOrFail($id);

        $topping->delete();

        return back()->with('success', 'Topping berhasil dihapus');
    }
}