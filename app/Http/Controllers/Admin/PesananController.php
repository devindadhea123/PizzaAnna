<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    // ==================== VIEWS ====================
    public function index()
    {
        $pesanan = Pesanan::with('kasir', 'detailPesanan.menu')
            ->orderBy('tanggal', 'desc')
            ->paginate(15);
        return view('admin.riwayat-pesanan', compact('pesanan'));
    }

    // ==================== API ====================
    public function getOrders(Request $request)
    {
        $query = Pesanan::with('kasir');
        
        if ($request->month) {
            $query->whereMonth('tanggal', $request->month);
        }
        if ($request->year) {
            $query->whereYear('tanggal', $request->year);
        }
        
        $orders = $query->orderBy('tanggal', 'desc')->paginate(15);
        
        return response()->json($orders);
    }

    public function getOrderDetail($id)
    {
        $order = Pesanan::with('kasir', 'detailPesanan.menu')->findOrFail($id);
        return response()->json($order);
    }

    public function exportExcel(Request $request)
    {
        return response()->json(['message' => 'Export Excel feature coming soon']);
    }

    public function exportPDF(Request $request)
    {
        return response()->json(['message' => 'Export PDF feature coming soon']);
    }
}