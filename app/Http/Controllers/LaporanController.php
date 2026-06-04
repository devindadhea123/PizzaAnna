<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Helper function untuk mendapatkan nama bulan
     */
    private function getBulanNama($bulan)
    {
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        
        return $bulanList[$bulan] ?? '';
    }

    /**
     * Export Excel (HTML format, bisa dibuka di Excel)
     */
    public function exportDetailExcel(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');
        
        // Query dasar
        $query = DetailPesanan::with(['pesanan', 'menu', 'toppings'])
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->orderBy('pesanan.tanggal', 'asc')
            ->select('detail_pesanan.*');
        
        // Filter berdasarkan bulan
        if ($bulan && $bulan != '') {
            $query->whereMonth('pesanan.tanggal', $bulan);
        }
        
        // Filter berdasarkan tahun
        if ($tahun && $tahun != '') {
            $query->whereYear('pesanan.tanggal', $tahun);
        }
        
        $details = $query->get();
        
        // Hitung total keseluruhan
        $grandTotal = $details->sum('subtotal');
        
        // Format periode
        $bulanNama = $this->getBulanNama($bulan);
        $tahunText = $tahun ?: 'Semua Tahun';
        $periode = ($bulan ? $bulanNama . ' ' : 'Semua Bulan ') . $tahunText;
        $tanggalCetak = date('d/m/Y H:i:s');
        $namaUser = auth()->user()->nama_lengkap ?? 'Admin';
        
        // Buat HTML untuk Excel
        $html = $this->generateExcelHTML($details, $grandTotal, $periode, $tanggalCetak, $namaUser);
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="laporan_detail_pesanan_' . ($bulan ?: 'semua') . '_' . ($tahun ?: 'semua') . '.xls"');
        header('Cache-Control: max-age=0');
        
        echo $html;
        exit;
    }
    
    /**
     * Export PDF
     */
    public function exportDetailPDF(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');
        
        // Query dasar
        $query = DetailPesanan::with(['pesanan', 'menu', 'toppings'])
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->orderBy('pesanan.tanggal', 'asc')
            ->select('detail_pesanan.*');
        
        // Filter berdasarkan bulan
        if ($bulan && $bulan != '') {
            $query->whereMonth('pesanan.tanggal', $bulan);
        }
        
        // Filter berdasarkan tahun
        if ($tahun && $tahun != '') {
            $query->whereYear('pesanan.tanggal', $tahun);
        }
        
        $details = $query->get();
        
        // Hitung total keseluruhan
        $grandTotal = $details->sum('subtotal');
        
        // Format periode
        $bulanNama = $this->getBulanNama($bulan);
        $tahunText = $tahun ?: 'Semua Tahun';
        $periode = ($bulan ? $bulanNama . ' ' : 'Semua Bulan ') . $tahunText;
        $tanggalCetak = date('d/m/Y H:i:s');
        $namaUser = auth()->user()->nama_lengkap ?? 'Admin';
        
        // Siapkan data untuk view
        $data = [
            'details' => $details,
            'grandTotal' => $grandTotal,
            'periode' => $periode,
            'tanggalCetak' => $tanggalCetak,
            'namaUser' => $namaUser,
        ];
        
        // Load view untuk PDF
        $pdf = Pdf::loadView('exports.detail-pesanan-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan_detail_pesanan_' . ($bulan ?: 'semua') . '_' . ($tahun ?: 'semua') . '.pdf');
    }
    
    /**
     * Generate HTML untuk Excel
     */
    private function generateExcelHTML($details, $grandTotal, $periode, $tanggalCetak, $namaUser)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Laporan Detail Pesanan - PIZZAANNA</title>
            <style>
                body { font-family: "Arial", sans-serif; font-size: 11px; padding: 20px; }
                .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #D73535; padding-bottom: 10px; }
                .header h1 { color: #D73535; margin: 0; font-size: 18px; }
                .header p { margin: 5px 0; font-size: 11px; }
                .periode { margin-bottom: 15px; font-size: 11px; font-weight: bold; }
                table { width: 100%; border-collapse: collapse; }
                th { background-color: #D73535; color: white; padding: 8px; text-align: center; font-size: 10px; border: 1px solid #000; }
                td { border: 1px solid #000; padding: 6px; font-size: 10px; }
                .text-left { text-align: left; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
                .total-row { font-weight: bold; background-color: #f9f9f9; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>PIZZAANNA</h1>
                <p>Jl. Pengging, Boyolali</p>
                <p>LAPORAN DETAIL PESANAN</p>
            </div>
            <div class="periode">
                Periode: ' . $periode . '<br>
                Tanggal Cetak: ' . $tanggalCetak . '
            </div>
            <table border="1">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Tanggal & Jam</th>
                        <th class="text-center">No. Invoice</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Pesanan</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Ukuran</th>
                        <th class="text-center">Harga Satuan</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Topping</th>
                        <th class="text-center">Metode Bayar</th>
                    </tr>
                </thead>
                <tbody>';
        
        $no = 1;
        foreach ($details as $detail) {
            // Ambil nama topping
            $toppingNames = '';
            if ($detail->toppings && count($detail->toppings) > 0) {
                $toppingList = [];
                foreach ($detail->toppings as $topping) {
                    $toppingList[] = $topping->nama_topping;
                }
                $toppingNames = implode(', ', $toppingList);
            }
            
            $tanggal = date('d/m/Y H.i', strtotime($detail->pesanan->tanggal));
            $hargaSatuan = 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.');
            $total = 'Rp ' . number_format($detail->subtotal, 0, ',', '.');
            $metodeBayar = $detail->pesanan->metode_bayar == 'tunai' ? 'Tunai' : 'QRIS';
            $ukuran = $detail->ukuran ?: '-';
            
            $html .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td class="text-center">' . $tanggal . '</td>
                    <td class="text-center">' . ($detail->pesanan->no_invoice ?? '-') . '</td>
                    <td class="text-left">' . ($detail->pesanan->nama_customer ?? '-') . '</td>
                    <td class="text-left">' . ($detail->menu->nama_menu ?? '-') . '</td>
                    <td class="text-center">' . $detail->jumlah . '</td>
                    <td class="text-center">' . $ukuran . '</td>
                    <td class="text-right">' . $hargaSatuan . '</td>
                    <td class="text-right">' . $total . '</td>
                    <td class="text-left">' . ($toppingNames ?: '-') . '</td>
                    <td class="text-center">' . $metodeBayar . '</td>
                </tr>';
        }
        
        $html .= '
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="9" class="text-right"><strong>GRAND TOTAL:</strong></td>
                        <td class="text-right"><strong>Rp ' . number_format($grandTotal, 0, ',', '.') . '</strong></td>
                        <td class="text-center"></td>
                    </tr>
                </tfoot>
            </table>
            <div class="footer">
                <p>Dicetak oleh: ' . $namaUser . '</p>
                <p>&copy; ' . date('Y') . ' PIZZAANNA - Sistem Informasi Restoran Terintegrasi</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}