<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\DetailPesanan;

class RiwayatPesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $pesanan = Pesanan::with('kasir', 'detailPesanan.menu')
            ->orderBy('tanggal', 'asc')
            ->paginate(15);

        // Ambil bulan & tahun yang ada di data pesanan
        $periode = Pesanan::selectRaw('MONTH(tanggal) as bulan, YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'asc')
            ->get();

        $bulanTersedia = $periode->pluck('bulan')->unique();
        $tahunTersedia = $periode->pluck('tahun')->unique();

        return view('admin.riwayat-pesanan', compact(
            'pesanan',
            'bulanTersedia',
            'tahunTersedia'
        ));
    }

    public function getOrders(Request $request)
    {
        $query = Pesanan::with('kasir');
        
        if ($request->month) {
            $query->whereMonth('tanggal', $request->month);
        }
        if ($request->year) {
            $query->whereYear('tanggal', $request->year);
        }
        
        // PERBAIKAN: Ubah 'desc' menjadi 'asc' agar urut dari tanggal awal
        $orders = $query->orderBy('tanggal', 'asc')->paginate(15);
        
        return response()->json($orders);
    }

    public function getOrderDetail($id)
    {
        $order = Pesanan::with(['kasir', 'detailPesanan.menu', 'detailPesanan.toppings'])
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

public function exportOrdersExcel(Request $request)
{
    $month = $request->month;
    $year = $request->year;
    
    $query = Pesanan::with('kasir')
        ->orderBy('tanggal', 'asc');
    
    if ($month) {
        $query->whereMonth('tanggal', $month);
    }
    if ($year) {
        $query->whereYear('tanggal', $year);
    }
    
    $orders = $query->get();
    
    $fileName = 'riwayat_pesanan_' . date('Ymd_His') . '.xls';
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    
    echo '<table border="1">';
    echo '<tr style="background-color: #D73535; color: white;">';
    echo '<th>No</th>';
    echo '<th>Tanggal & Jam</th>';
    echo '<th>No. Invoice</th>';
    echo '<th>Customer</th>';
    echo '<th>Meja</th>';
    echo '<th>Total</th>';
    echo '<th>Metode Bayar</th>';  // Sudah ada kolom Metode Bayar
    echo '</tr>';
    
    $no = 1;
    foreach ($orders as $order) {
        $date = new \DateTime($order->tanggal);
        $formattedDate = $date->format('d/m/Y H:i:s');
        $mejaText = $order->no_meja ? 'Meja ' . $order->no_meja : 'Take Away';
        
        // PERBAIKAN: Pastikan metode bayar ditampilkan dengan benar
        $metodeText = '';
        if ($order->metode_bayar == 'tunai') {
            $metodeText = 'Tunai';
        } elseif ($order->metode_bayar == 'qris') {
            $metodeText = 'QRIS';
        } else {
            $metodeText = '-';
        }
        
        echo '<tr>';
        echo '<td>' . $no . '</td>';
        echo '<td>' . $formattedDate . '</td>';
        echo '<td>' . $order->no_invoice . '</td>';
        echo '<td>' . $order->nama_customer . '</td>';
        echo '<td>' . $mejaText . '</td>';
        echo '<td>Rp ' . number_format($order->total_bayar, 0, ',', '.') . '</td>';
        echo '<td>' . $metodeText . '</td>';
        echo '</tr>';
        $no++;
    }
    
    echo '</table>';
    exit;
}

    public function exportOrdersPDF(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        
        $query = Pesanan::with('kasir', 'detailPesanan.menu', 'detailPesanan.toppings')
            ->orderBy('tanggal', 'asc');  // PERBAIKAN: 'desc' diubah jadi 'asc'
        
        if ($month) {
            $query->whereMonth('tanggal', $month);
        }
        if ($year) {
            $query->whereYear('tanggal', $year);
        }
        
        $orders = $query->get();
        
        $bulanNama = '';
        if ($month) {
            $bulanArray = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $bulanNama = $bulanArray[(int)$month];
        }
        
        $periode = '';
        if ($month && $year) {
            $periode = $bulanNama . ' ' . $year;
        } elseif ($month) {
            $periode = $bulanNama;
        } elseif ($year) {
            $periode = 'Tahun ' . $year;
        } else {
            $periode = 'Semua Data';
        }
        
        $data = [
            'orders' => $orders,
            'periode' => $periode,
            'tanggal_cetak' => date('d/m/Y H:i:s')
        ];
        
        $pdf = Pdf::loadView('admin.exports.riwayat-pesanan-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('riwayat_pesanan_' . date('Ymd_His') . '.pdf');
    }
}