<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\RiwayatPrediksi;
use App\Models\Menu;
use App\Models\Setting; // ✅ TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatPrediksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $prediksi = RiwayatPrediksi::orderBy('tanggal_prediksi', 'desc')->get();
        return view('admin.riwayat-prediksi', compact('prediksi'));
    }

    public function getPredictions(Request $request)
    {
        $query = RiwayatPrediksi::query();
        
        if ($request->month && $request->month != 'all') {
            $query->whereMonth('bulan_target', $request->month);
        }
        if ($request->year && $request->year != 'all') {
            $query->whereYear('bulan_target', $request->year);
        }
        
        $predictions = $query->orderBy('tanggal_prediksi', 'desc')->paginate(6);
        
        return response()->json($predictions);
    }

    public function getPredictionDetail($id)
    {
        $prediction = RiwayatPrediksi::findOrFail($id);
        
        return response()->json([
            'id_prediksi' => $prediction->id_prediksi,
            'tanggal_prediksi' => $prediction->tanggal_prediksi,
            'bulan_target' => $prediction->bulan_target,
            'data_yang_dipakai' => $prediction->data_yang_dipakai,
            'hasil_prediksi' => $prediction->hasil_prediksi,
            'rekomendasi_promosi' => $prediction->rekomendasi_promosi,
            'rata_rata_akurasi' => $prediction->rata_rata_akurasi,
            'detail_akurasi' => $prediction->detail_akurasi,
            'created_at' => $prediction->created_at,
            'updated_at' => $prediction->updated_at,
        ]);
    }

    public function getLatestPrediction()
    {
        try {
            $prediksi = RiwayatPrediksi::orderBy('id_prediksi', 'desc')->first();
            
            if ($prediksi) {
                $hasilPrediksi = $prediksi->hasil_prediksi;
                if (is_string($hasilPrediksi)) {
                    $hasilPrediksi = json_decode($hasilPrediksi, true);
                }
                
                $rekomendasi = $prediksi->rekomendasi_promosi;
                if (is_string($rekomendasi)) {
                    $rekomendasi = json_decode($rekomendasi, true);
                }
                
                return response()->json([
                    'success' => true,
                    'has_prediction' => true,
                    'data' => $hasilPrediksi,
                    'rekomendasi' => $rekomendasi,
                    'periode' => $prediksi->data_yang_dipakai,
                    'bulan_target' => $prediksi->bulan_target,
                ]);
            }
            
            return response()->json([
                'success' => true,
                'has_prediction' => false,
            ]);
            
        } catch (\Exception $e) {
            Log::error('getLatestPrediction error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'has_prediction' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ GET PREDIKSI STATUS - SUDAH DINAMIS (MENGAMBIL DARI DATABASE)
     */
    public function getPredictionStatus()
    {
        try {
            $bulanTarget = now()->addMonth()->format('Y-m');
            $existingPrediksi = RiwayatPrediksi::where('bulan_target', $bulanTarget)->first();
            
            $today = now();
            
            // ✅ AMBIL DARI DATABASE (bukan hardcoded)
            $HARI_PREDIKSI = (int) Setting::get('prediction_day', 27);
            $JAM_DEADLINE = (int) Setting::get('prediction_hour', 12);
            $MENIT_DEADLINE = (int) Setting::get('prediction_minute', 0);
            $predictionEnabled = (int) Setting::get('prediction_enabled', 1);
            
            $isPredictionDay = ($today->day == $HARI_PREDIKSI);
            $currentHour = (int)$today->format('H');
            $currentMinute = (int)$today->format('i');
            
            $isBeforeDeadline = (
                $currentHour < $JAM_DEADLINE || 
                ($currentHour == $JAM_DEADLINE && $currentMinute < $MENIT_DEADLINE)
            );
            
            $isAtOrAfterDeadline = (
                $currentHour > $JAM_DEADLINE || 
                ($currentHour == $JAM_DEADLINE && $currentMinute >= $MENIT_DEADLINE)
            );
            
            // ✅ Cek apakah prediksi otomatis diaktifkan
            $canPredictManual = !$existingPrediksi && $isPredictionDay && $isBeforeDeadline && $predictionEnabled;
            
            $needAutoPrediction = $isPredictionDay && $isAtOrAfterDeadline && !$existingPrediksi && $predictionEnabled;
            
            $deadlineFormatted = sprintf('%02d:%02d', $JAM_DEADLINE, $MENIT_DEADLINE);
            
            return response()->json([
                'success' => true,
                'can_predict' => $canPredictManual,
                'has_prediction' => !is_null($existingPrediksi),
                'bulan_target' => $bulanTarget,
                'formatted_bulan' => now()->addMonth()->format('F Y'),
                'is_prediction_day' => $isPredictionDay,
                'is_before_deadline' => $isBeforeDeadline,
                'is_at_or_after_deadline' => $isAtOrAfterDeadline,
                'need_auto_prediction' => $needAutoPrediction,
                'current_time' => $currentHour . ':' . str_pad($currentMinute, 2, '0', STR_PAD_LEFT),
                'deadline_time' => $deadlineFormatted,
                'prediction_date' => $HARI_PREDIKSI,
                'prediction_enabled' => (bool)$predictionEnabled,
                'message' => !$predictionEnabled ? 'Prediksi otomatis sedang dinonaktifkan oleh admin' : 
                            ($canPredictManual ? 'Silakan lakukan prediksi manual' : 
                            ($needAutoPrediction ? 'Menjalankan prediksi otomatis...' : 'Menunggu jadwal prediksi'))
            ]);
            
        } catch (\Exception $e) {
            Log::error('getPredictionStatus error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== METHOD WMA ====================

    private function hitungWMA($data)
    {
        if (empty($data) || count($data) < 6) {
            $data = array_pad($data, 6, 0);
        }
        
        $bobot = [1, 2, 3, 4, 5, 6];
        $totalBobot = array_sum($bobot);
        
        $totalPerkalian = 0;
        for ($i = 0; $i < 6; $i++) {
            $totalPerkalian += ($data[$i] ?? 0) * $bobot[$i];
        }
        
        return $totalBobot > 0 ? round($totalPerkalian / $totalBobot, 2) : 0;
    }

    private function generateRekomendasiPromosi($hasilPrediksi)
    {
        $rekomendasi = [];
        
        $menuMakanan = [
            'Spicy Chicken Mushroom' => 71000,
            'Pepperoni Mushroom' => 63000,
            'Meat Lover' => 75000,
            'Favorite Pizza' => 95000,
            'Smoked Beef & Corn' => 75000,
            'Chicken Burger' => 15000,
            'Beef Burger' => 16000,
            'French Fries' => 9000,
            'Donut / Bomboloni' => 10000,
        ];
        
        $menuMinuman = [
            'Teh / Es Teh' => 4000,
            'Jeruk / Es Jeruk' => 5000,
            'Choco Blend' => 12000,
            'Strawberry Blend' => 12000,
        ];
        
        $menuSnack = [
            'French Fries' => 9000,
            'Donut / Bomboloni' => 10000,
            'Mozarella Stick' => 12000,
        ];
        
        $top1 = $hasilPrediksi[0] ?? null;
        $top2 = $hasilPrediksi[1] ?? null;
        $top3 = $hasilPrediksi[2] ?? null;
        
        $topMakanan = null;
        foreach ($hasilPrediksi as $item) {
            if (array_key_exists($item['nama_menu'], $menuMakanan)) {
                $topMakanan = $item;
                break;
            }
        }
        
        $topSnack = null;
        foreach ($hasilPrediksi as $item) {
            if (array_key_exists($item['nama_menu'], $menuSnack)) {
                $topSnack = $item;
                break;
            }
        }
        
        // REKOMENDASI 1: BUY 1 GET 1
        if ($topMakanan) {
            $rekomendasi[] = [
                'menu' => $topMakanan['nama_menu'],
                'judul' => 'BUY 1 GET 1',
                'deskripsi' => "Beli 1 {$topMakanan['nama_menu']} dapatkan 1 FREE French Fries! Promo terbatas!",
                'icon' => '',
                'target' => $topMakanan['nama_menu']
            ];
        } elseif ($top1 && !array_key_exists($top1['nama_menu'], $menuMinuman)) {
            $rekomendasi[] = [
                'menu' => $top1['nama_menu'],
                'judul' => 'BUY 1 GET 1',
                'deskripsi' => "Beli 1 {$top1['nama_menu']} dapatkan 1 FREE! Promo terbatas untuk menu terlaris!",
                'icon' => '',
                'target' => $top1['nama_menu']
            ];
        }
        
        // REKOMENDASI 2: BUNDLING MAKANAN + MINUMAN
        if ($topMakanan) {
            $minumanPilihan = 'Teh / Es Teh';
            if ($topMakanan['nama_menu'] == 'Teh / Es Teh') {
                $minumanPilihan = 'Jeruk / Es Jeruk';
            }
            
            $hargaMakanan = $menuMakanan[$topMakanan['nama_menu']] ?? 50000;
            $hargaMinuman = $menuMinuman[$minumanPilihan] ?? 5000;
            $hargaBundling = $hargaMakanan + $hargaMinuman - 5000;
            
            $rekomendasi[] = [
                'menu' => $topMakanan['nama_menu'] . ' + ' . $minumanPilihan,
                'judul' => 'BUNDLING HEMAT',
                'deskripsi' => "{$topMakanan['nama_menu']} + {$minumanPilihan} hanya Rp " . number_format($hargaBundling, 0, ',', '.') . "! Hemat Rp 5.000!",
                'icon' => '',
                'target' => $topMakanan['nama_menu'] . ' + Minuman'
            ];
        }
        
        // REKOMENDASI 3: BUNDLING MAKANAN + SNACK
        if ($topMakanan && $topSnack && $topMakanan['nama_menu'] != $topSnack['nama_menu']) {
            $rekomendasi[] = [
                'menu' => $topMakanan['nama_menu'] . ' + ' . $topSnack['nama_menu'],
                'judul' => 'PAKET SPESIAL',
                'deskripsi' => "{$topMakanan['nama_menu']} + {$topSnack['nama_menu']} hemat Rp 10.000! Cocok untuk ngemil!",
                'icon' => '',
                'target' => $topMakanan['nama_menu'] . ' & ' . $topSnack['nama_menu']
            ];
        } elseif ($top1 && $top3 && $top1['nama_menu'] != $top3['nama_menu']) {
            $rekomendasi[] = [
                'menu' => $top1['nama_menu'] . ' + ' . $top3['nama_menu'],
                'judul' => 'PAKET SPESIAL',
                'deskripsi' => "{$top1['nama_menu']} + {$top3['nama_menu']} hemat Rp 10.000! Beli sekarang sebelum kehabisan!",
                'icon' => '',
                'target' => $top1['nama_menu'] . ' & ' . $top3['nama_menu']
            ];
        }
        
        // REKOMENDASI 4: PAKET KELUARGA
        if ($topMakanan) {
            $minumanAcak = array_rand($menuMinuman);
            $snackAcak = array_rand($menuSnack);
            
            $rekomendasi[] = [
                'menu' => 'Paket Keluarga',
                'judul' => 'PAKET KELUARGA',
                'deskripsi' => "2 {$topMakanan['nama_menu']} + 2 {$minumanAcak} + 2 {$snackAcak} hanya Rp 149.000! Hemat 25%!",
                'icon' => '',
                'target' => 'Paket Keluarga'
            ];
        }
        
        // REKOMENDASI 5: DISKON UNTUK MENU TURUN
        foreach ($hasilPrediksi as $item) {
            if ($item['kenaikan'] < 0 && !array_key_exists($item['nama_menu'], $menuMinuman)) {
                $rekomendasi[] = [
                    'menu' => $item['nama_menu'],
                    'judul' => 'DISKON 20%',
                    'deskripsi' => "{$item['nama_menu']} diskon 20%! Hanya untuk bulan ini! Jangan sampai kehabisan!",
                    'icon' => '',
                    'target' => $item['nama_menu']
                ];
                break;
            }
        }
        
        // REKOMENDASI 6: FLASH SALE
        if ($topMakanan) {
            $rekomendasi[] = [
                'menu' => $topMakanan['nama_menu'],
                'judul' => 'FLASH SALE',
                'deskripsi' => "{$topMakanan['nama_menu']} flash sale setiap jam 12-14 siang! Diskon spesial 15%!",
                'icon' => '',
                'target' => $topMakanan['nama_menu']
            ];
        }
        
        return $rekomendasi;
    }

    // ==================== EXPORT EXCEL ====================
    public function exportPredictionsExcel(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        
        $query = RiwayatPrediksi::query();
        
        if ($month && $month != 'all') {
            $query->whereMonth('bulan_target', $month);
        }
        if ($year && $year != 'all') {
            $query->whereYear('bulan_target', $year);
        }
        
        $predictions = $query->orderBy('tanggal_prediksi', 'desc')->get();
        
        $fileName = 'riwayat_prediksi_' . date('Ymd_His') . '.xls';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        
        echo '<table border="1">';
        echo '<tr style="background-color: #D73535; color: white;">';
        echo '<th>No</th>';
        echo '<th>Tanggal Prediksi</th>';
        echo '<th>Bulan Target</th>';
        echo '<th>Data yang Dipakai</th>';
        echo '<th>Top 1 Menu</th>';
        echo '<th>Top 1 Prediksi</th>';
        echo '<th>Top 2 Menu</th>';
        echo '<th>Top 2 Prediksi</th>';
        echo '<th>Top 3 Menu</th>';
        echo '<th>Top 3 Prediksi</th>';
        echo '<th>Rata-rata Akurasi</th>';
        echo '</tr>';
        
        $no = 1;
        foreach ($predictions as $pred) {
            $hasil = $pred->hasil_prediksi;
            if (is_string($hasil)) {
                $hasil = json_decode($hasil, true);
            }
            if (!is_array($hasil)) {
                $hasil = [];
            }
            
            $top1 = $hasil[0] ?? ['nama_menu' => '-', 'prediksi' => '-'];
            $top2 = $hasil[1] ?? ['nama_menu' => '-', 'prediksi' => '-'];
            $top3 = $hasil[2] ?? ['nama_menu' => '-', 'prediksi' => '-'];
            
            $akurasi = $pred->rata_rata_akurasi ?? 0;
            $akurasiText = $akurasi > 0 ? $akurasi . '%' : '-';
            
            $tanggal = date('d/m/Y H:i:s', strtotime($pred->tanggal_prediksi));
            
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . $tanggal . '</td>';
            echo '<td>' . $pred->bulan_target . '</td>';
            echo '<td>' . $pred->data_yang_dipakai . '</td>';
            echo '<td>' . $top1['nama_menu'] . '</td>';
            echo '<td>' . ($top1['prediksi'] ?? '-') . '</td>';
            echo '<td>' . $top2['nama_menu'] . '</td>';
            echo '<td>' . ($top2['prediksi'] ?? '-') . '</td>';
            echo '<td>' . $top3['nama_menu'] . '</td>';
            echo '<td>' . ($top3['prediksi'] ?? '-') . '</td>';
            echo '<td>' . $akurasiText . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
        exit;
    }

    // ==================== EXPORT PDF ====================
    public function exportPredictionsPDF(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');
        
        $query = RiwayatPrediksi::query();
        
        if ($month && $month != 'all') {
            $query->whereMonth('bulan_target', $month);
        }
        if ($year && $year != 'all') {
            $query->whereYear('bulan_target', $year);
        }
        
        $predictions = $query->orderBy('tanggal_prediksi', 'desc')->get();
        
        $bulanNama = '';
        if ($month && $month != 'all') {
            $bulanArray = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $bulanNama = $bulanArray[(int)$month];
        }
        
        $periode = '';
        if ($month && $month != 'all' && $year && $year != 'all') {
            $periode = $bulanNama . ' ' . $year;
        } elseif ($month && $month != 'all') {
            $periode = $bulanNama;
        } elseif ($year && $year != 'all') {
            $periode = 'Tahun ' . $year;
        } else {
            $periode = 'Semua Data';
        }
        
        $data = [
            'predictions' => $predictions,
            'periode' => $periode,
            'tanggal_cetak' => date('d/m/Y H:i:s')
        ];
        
        $pdf = Pdf::loadView('exports.riwayat-prediksi-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('riwayat_prediksi_' . date('Ymd_His') . '.pdf');
    }

    // ==================== PREDIKSI MANUAL ====================
    public function lakukanPrediksi(Request $request)
    {
        try {
            $bulanTarget = now()->addMonth()->format('Y-m');
            $existingPrediksi = RiwayatPrediksi::where('bulan_target', $bulanTarget)->first();
            
            if ($existingPrediksi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Prediksi untuk bulan ' . now()->addMonth()->format('F Y') . ' sudah ada.'
                ], 422);
            }
            
            $dataPenjualan = $this->getDataPenjualan6Bulan();
            
            if (empty($dataPenjualan)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data penjualan tidak cukup untuk prediksi. Minimal 6 bulan data diperlukan.'
                ], 422);
            }
            
            uasort($dataPenjualan, function($a, $b) {
                return $b['prediksi_wma'] <=> $a['prediksi_wma'];
            });
            
            $top3 = array_slice($dataPenjualan, 0, 3, true);
            
            $hasilPrediksi = [];
            $ranking = 1;
            foreach ($top3 as $item) {
                $dataTerbaru = end($item['data_6_bulan']);
                $persentaseKenaikan = $this->hitungPersentaseKenaikan($item['prediksi_wma'], $dataTerbaru);
                
                $hasilPrediksi[] = [
                    'ranking' => $ranking++,
                    'nama_menu' => $item['nama_menu'],
                    'harga' => $item['harga'],
                    'prediksi' => round($item['prediksi_wma']),
                    'kenaikan' => $persentaseKenaikan,
                ];
            }
            
            $rekomendasiPromosi = $this->generateRekomendasiPromosi($hasilPrediksi);
            
            $periodeData = now()->subMonths(5)->startOfMonth()->format('F Y') . ' - ' . now()->format('F Y');
            
            RiwayatPrediksi::create([
                'tanggal_prediksi' => now(),
                'bulan_target' => $bulanTarget,
                'data_yang_dipakai' => $periodeData,
                'hasil_prediksi' => $hasilPrediksi,
                'rekomendasi_promosi' => $rekomendasiPromosi,
                'rata_rata_akurasi' => null,
                'detail_akurasi' => null,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Prediksi berhasil dilakukan dengan metode Weighted Moving Average (WMA)',
                'data' => $hasilPrediksi,
                'rekomendasi' => $rekomendasiPromosi,
                'periode' => $periodeData,
                'bulan_target' => now()->addMonth()->format('F Y'),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Prediksi error: ' . $e->getMessage() . ' - Line: ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== DATA PENJUALAN 6 BULAN ====================
    private function getDataPenjualan6Bulan()
    {
        $totalPenjualan = DetailPesanan::count();
        if ($totalPenjualan == 0) {
            return [];
        }
        
        $menus = Menu::all();
        
        if ($menus->isEmpty()) {
            return [];
        }
        
        $dataPenjualan = [];
        
        foreach ($menus as $menu) {
            $penjualanPerBulan = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $bulan = now()->subMonths($i);
                $bulanAwal = $bulan->copy()->startOfMonth();
                $bulanAkhir = $bulan->copy()->endOfMonth();
                
                $total = DetailPesanan::where('detail_pesanan.id_menu', $menu->id_menu)
                    ->whereHas('pesanan', function($q) use ($bulanAwal, $bulanAkhir) {
                        $q->whereBetween('tanggal', [$bulanAwal, $bulanAkhir]);
                    })
                    ->sum('detail_pesanan.jumlah');
                
                $penjualanPerBulan[] = $total;
            }
            
            if (array_sum($penjualanPerBulan) > 0) {
                $dataPenjualan[$menu->id_menu] = [
                    'id_menu' => $menu->id_menu,
                    'nama_menu' => $menu->nama_menu,
                    'harga' => $menu->harga,
                    'data_6_bulan' => $penjualanPerBulan,
                    'total_6_bulan' => array_sum($penjualanPerBulan),
                    'prediksi_wma' => $this->hitungWMA($penjualanPerBulan),
                ];
            }
        }
        
        return $dataPenjualan;
    }

    private function hitungPersentaseKenaikan($prediksi, $dataTerbaru)
    {
        if ($dataTerbaru == 0) {
            return 100;
        }
        return round((($prediksi - $dataTerbaru) / $dataTerbaru) * 100, 1);
    }

    // ==================== HITUNG AKURASI ====================
    private function hitungAkurasi($prediksi, $aktual)
    {
        if ($aktual == 0) {
            return 0;
        }
        $error = abs($prediksi - $aktual);
        $akurasi = 100 - (($error / $aktual) * 100);
        return round($akurasi, 2);
    }

    private function getKategoriAkurasi($akurasi)
    {
        if ($akurasi >= 90) {
            return 'Sangat Akurat';
        } elseif ($akurasi >= 80) {
            return 'Akurat';
        } elseif ($akurasi >= 70) {
            return 'Cukup Akurat';
        } else {
            return 'Kurang Akurat';
        }
    }

    public function updateAkurasi($id)
    {
        $prediksi = RiwayatPrediksi::findOrFail($id);
        $hasilPrediksi = $prediksi->hasil_prediksi;
        $bulanTarget = $prediksi->bulan_target;
        
        if (is_string($hasilPrediksi)) {
            $hasilPrediksi = json_decode($hasilPrediksi, true);
        }
        
        $aktualPenjualan = DetailPesanan::select(
            'menu.id_menu',
            'menu.nama_menu',
            DB::raw('SUM(detail_pesanan.jumlah) as total_terjual')
        )
        ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
        ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id_menu')
        ->whereYear('pesanan.tanggal', substr($bulanTarget, 0, 4))
        ->whereMonth('pesanan.tanggal', substr($bulanTarget, 5, 2))
        ->groupBy('menu.id_menu', 'menu.nama_menu')
        ->get()
        ->keyBy('nama_menu');
        
        $detailAkurasi = [];
        $totalAkurasi = 0;
        $count = 0;
        
        foreach ($hasilPrediksi as $item) {
            $namaMenu = $item['nama_menu'];
            $prediksiJumlah = $item['prediksi'];
            $aktualJumlah = isset($aktualPenjualan[$namaMenu]) ? $aktualPenjualan[$namaMenu]->total_terjual : 0;
            
            $akurasi = $this->hitungAkurasi($prediksiJumlah, $aktualJumlah);
            $kategori = $this->getKategoriAkurasi($akurasi);
            
            $detailAkurasi[] = [
                'nama_menu' => $namaMenu,
                'prediksi' => $prediksiJumlah,
                'aktual' => $aktualJumlah,
                'akurasi' => $akurasi,
                'kategori' => $kategori
            ];
            
            $totalAkurasi += $akurasi;
            $count++;
        }
        
        $rataRataAkurasi = $count > 0 ? round($totalAkurasi / $count, 1) : 0;
        
        $prediksi->update([
            'rata_rata_akurasi' => $rataRataAkurasi,
            'detail_akurasi' => $detailAkurasi
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Akurasi berhasil diupdate',
            'rata_rata' => $rataRataAkurasi,
            'detail' => $detailAkurasi,
            'kategori' => $this->getKategoriAkurasi($rataRataAkurasi)
        ]);
    }

    public function updateAllAkurasi()
    {
        $predictions = RiwayatPrediksi::whereNull('rata_rata_akurasi')->get();
        $updated = 0;
        
        foreach ($predictions as $prediction) {
            $bulanTarget = $prediction->bulan_target;
            $tahun = substr($bulanTarget, 0, 4);
            $bulan = substr($bulanTarget, 5, 2);
            
            if (now()->startOfMonth() > \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth()) {
                $this->updateAkurasi($prediction->id_prediksi);
                $updated++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Updated {$updated} predictions"
        ]);
    }
}