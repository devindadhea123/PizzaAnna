<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\PizzaUkuran;
use App\Models\Topping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class KasirController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:kasir');
    }

    public function menuPesanan()
    {
        $menu = Menu::with('pizzaUkuran')->get();
        return view('kasir.menu-pesanan', compact('menu'));
    }

     public function cetakPDF(Request $request)
    {
        $data = $request->all();
        
        $pdf = Pdf::loadView('kasir.struk-pdf', [
            'order_data' => $data['order_data'],
            'total' => $data['total'],
            'customer_name' => $data['customer_name'],
            'invoice_number' => $data['invoice_number'],
            'no_meja' => $data['no_meja'],
            'payment_method' => $data['payment_method'],
            'order_type' => $data['order_type'],
            'tanggal' => now()->format('d/m/Y H:i:s')
        ]);
        
        return $pdf->download('struk_' . $data['invoice_number'] . '.pdf');
    }
    
 public function storePesanan(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string|max:100',
        'items' => 'required|array|min:1',
        'total' => 'required|numeric|min:0',
        'payment_method' => 'required|in:tunai,qris',
        'table_number' => 'nullable|string|max:5'
    ]);

    $no_invoice = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

    DB::beginTransaction();
    
    try {
        $pesanan = Pesanan::create([
            'no_invoice' => $no_invoice,
            'tanggal' => now(),
            'id_kasir' => Auth::id(),
            'nama_customer' => $request->customer_name,
            'no_meja' => $request->table_number,
            'total_bayar' => $request->total,
            'metode_bayar' => $request->payment_method
        ]);

        foreach ($request->items as $item) {
            $hargaSatuan = isset($item['harga']) && $item['qty'] > 0 
                ? (int) $item['harga'] / (int) $item['qty']
                : (int) $item['harga'];
            
            $detail = DetailPesanan::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'id_menu' => $item['id_menu'],
                'harga_satuan' => $hargaSatuan,
                'jumlah' => (int) $item['qty'],
                'subtotal' => (int) $item['harga'],
            ]);

            if (!empty($item['topping_ids']) && is_array($item['topping_ids'])) {
                foreach ($item['topping_ids'] as $toppingId) {
                    DB::table('detail_pesanan_topping')->insert([
                        'detail_pesanan_id' => $detail->id_detail,
                        'topping_id' => $toppingId,
                    ]);
                }
            }
        }
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil disimpan',
            'invoice' => $no_invoice
        ]);
        
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan: ' . $e->getMessage()
        ], 500);
    }
}
    public function riwayatPesanan()
    {
        $pesanan = Pesanan::where('id_kasir', Auth::id())
            ->with('detailPesanan.menu', 'detailPesanan.toppings')
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('kasir.riwayat-pesanan', compact('pesanan'));
    }

    public function getHargaByUkuran(Request $request)
    {
        $ukuranPizza = PizzaUkuran::where('id_menu', $request->id_menu)
            ->where('ukuran', $request->ukuran)
            ->first();

        $menu = Menu::findOrFail($request->id_menu);

        return response()->json([
            'success' => true,
            'harga' => $ukuranPizza ? $ukuranPizza->harga : $menu->harga,
            'nama_menu' => $menu->nama_menu . ' (' . $request->ukuran . ')',
            'ukuran' => $request->ukuran
        ]);
    }

    public function getOrders(Request $request)
    {
        $query = Pesanan::where('id_kasir', Auth::id())
            ->with('kasir');

        if ($request->month) {
            $query->whereMonth('tanggal', $request->month);
        }

        if ($request->year) {
            $query->whereYear('tanggal', $request->year);
        }

        if ($request->search) {
            $query->where('nama_customer', 'like', '%' . $request->search . '%');
        }

        $orders = $query->orderBy('tanggal', 'desc')
            ->paginate(15);

        return response()->json($orders);
    }

public function getOrderDetail($id)
{
    // Cek apakah user login
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    // Cek apakah user role kasir
    if (Auth::user()->role !== 'kasir') {
        return response()->json(['error' => 'Forbidden'], 403);
    }
    
    $order = Pesanan::with(['kasir', 'detailPesanan.menu', 'detailPesanan.toppings'])
        ->where('id_kasir', Auth::id()) // Pastikan hanya pesanan kasir ini
        ->findOrFail($id);
    
    // Format topping untuk setiap detail pesanan
    foreach ($order->detailPesanan as $detail) {
        $detail->topping_list = $detail->toppings->map(function($topping) {
            return [
                'nama' => $topping->nama_topping,
                'harga' => $topping->harga,
                'jumlah' => $topping->pivot->jumlah ?? 1
            ];
        });
    }
    
    return response()->json($order);
}

public function toppings()
{
    return $this->belongsToMany(Topping::class, 'detail_topping_pesanan', 'id_detail_pesanan', 'id_topping')
                ->withPivot('jumlah');
}


public function getToppings(Request $request)
{
    $ukuran = $request->ukuran;

    $toppings = Topping::where('ukuran', $ukuran)->get();

    return response()->json([
        'success' => true,
        'data' => $toppings
    ]);
}

}