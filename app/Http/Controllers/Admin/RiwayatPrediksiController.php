<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\RiwayatPrediksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function getPredictionStatus()
    {
        try {
            $bulanTarget = now()->addMonth()->format('Y-m');
            $existingPrediksi = RiwayatPrediksi::where('bulan_target', $bulanTarget)->first();
            
            $today = now();
            $HARI_PREDIKSI = 4;      
            $JAM_DEADLINE =23;     
            $MENIT_DEADLINE = 40;
            
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
            
            $canPredictManual = !$existingPrediksi && $isPredictionDay && $isBeforeDeadline;
            
            $needAutoPrediction = $isPredictionDay && $isAtOrAfterDeadline && !$existingPrediksi;
            
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
                'message' => $canPredictManual ? 'Silakan lakukan prediksi manual' : ($needAutoPrediction ? 'Menjalankan prediksi otomatis...' : 'Menunggu jadwal prediksi')
            ]);
            
        } catch (\Exception $e) {
            Log::error('getPredictionStatus error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLatestPrediction()
    {
        try {
            $bulanTarget = now()->addMonth()->format('Y-m');
            
            $prediksi = RiwayatPrediksi::where('bulan_target', $bulanTarget)
                ->latest('tanggal_prediksi')
                ->first();
            
            if ($prediksi) {
                return response()->json([
                    'success' => true,
                    'has_prediction' => true,
                    'data' => $prediksi->hasil_prediksi,
                    'rekomendasi' => $prediksi->rekomendasi_promosi,
                    'periode' => $prediksi->data_yang_dipakai,
                    'bulan_target' => now()->addMonth()->format('F Y'),
                ]);
            }
            
            $latestAny = RiwayatPrediksi::latest('tanggal_prediksi')->first();
            
            if ($latestAny) {
                return response()->json([
                    'success' => true,
                    'has_prediction' => true,
                    'data' => $latestAny->hasil_prediksi,
                    'rekomendasi' => $latestAny->rekomendasi_promosi,
                    'periode' => $latestAny->data_yang_dipakai,
                    'bulan_target' => $latestAny->bulan_target,
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

    public function exportPredictionsExcel(Request $request)
    {
        return response()->json(['message' => 'Export Excel feature coming soon']);
    }

    public function exportPredictionsPDF(Request $request)
    {
        return response()->json(['message' => 'Export PDF feature coming soon']);
    }

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
            
            $sixMonthsAgo = now()->subMonths(3);
            
            $penjualan = DetailPesanan::select(
                'menu.id_menu',
                'menu.nama_menu',
                'menu.harga',
                DB::raw('SUM(detail_pesanan.jumlah) as total_terjual'),
                DB::raw('SUM(detail_pesanan.subtotal) as total_pendapatan'),
                DB::raw('COUNT(DISTINCT pesanan.id_pesanan) as jumlah_transaksi')
            )
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id_menu')
            ->where('pesanan.tanggal', '>=', $sixMonthsAgo)
            ->groupBy('menu.id_menu', 'menu.nama_menu', 'menu.harga')
            ->orderBy('total_terjual', 'desc')
            ->get();
            
            if ($penjualan->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada data penjualan. Silakan input transaksi terlebih dahulu.'
                ], 422);
            }
            
            $topMenus = $penjualan->take(3);
            $totalTerjualSemua = $penjualan->sum('total_terjual');
            
            $hasilPrediksi = [];
            $rekomendasiPromosi = [];
            
            $kenaikanPersen = [15, 12, 10];
            
            foreach ($topMenus as $index => $item) {
                $persentase = $totalTerjualSemua > 0 ? round(($item->total_terjual / $totalTerjualSemua) * 100, 1) : 0;
                $kenaikan = $kenaikanPersen[$index] ?? 5;
                $prediksiJumlah = round($item->total_terjual * (1 + ($kenaikan / 100)));
                
                $hasilPrediksi[] = [
                    'nama_menu' => $item->nama_menu,
                    'harga' => $item->harga,
                    'total_terjual' => $item->total_terjual,
                    'prediksi' => $prediksiJumlah,
                    'persentase' => $persentase,
                    'kenaikan' => $kenaikan,
                ];
            }
            
            if (count($hasilPrediksi) >= 1) {
                $menu1 = $hasilPrediksi[0];
                $rekomendasiPromosi[] = [
                    'menu' => $menu1['nama_menu'],
                    'judul' => 'Buy 1 Get 1',
                    'deskripsi' => "{$menu1['nama_menu']} diprediksi naik {$menu1['kenaikan']}%. Berikan promo Buy 1 Get 1!",
                    'icon' => '🎁'
                ];
            }
            
            if (count($hasilPrediksi) >= 2) {
                $menu1 = $hasilPrediksi[0];
                $menu2 = $hasilPrediksi[1];
                $rekomendasiPromosi[] = [
                    'menu' => "{$menu1['nama_menu']} + {$menu2['nama_menu']}",
                    'judul' => 'Bundling Hemat',
                    'deskripsi' => "Beli {$menu1['nama_menu']} dan {$menu2['nama_menu']} hemat Rp 10.000!",
                    'icon' => '📦'
                ];
            }
            
            $menuMinuman = $penjualan->filter(function($item) {
                $nama = strtolower($item->nama_menu);
                return strpos($nama, 'teh') !== false || 
                       strpos($nama, 'jeruk') !== false || 
                       strpos($nama, 'kopi') !== false ||
                       strpos($nama, 'cola') !== false ||
                       strpos($nama, 'mineral') !== false;
            })->first();
            
            if ($menuMinuman) {
                $rekomendasiPromosi[] = [
                    'menu' => $menuMinuman->nama_menu,
                    'judul' => 'Gratis Ongkir',
                    'deskripsi' => "Promosikan {$menuMinuman->nama_menu} gratis ongkir untuk pembelian minimal Rp 50.000!",
                    'icon' => '🚚'
                ];
            }
            
            if (count($hasilPrediksi) >= 3) {
                $menu3 = $hasilPrediksi[2];
                $rekomendasiPromosi[] = [
                    'menu' => $menu3['nama_menu'],
                    'judul' => 'Promo Spesial',
                    'deskripsi' => "{$menu3['nama_menu']} diprediksi menjadi menu populer. Berikan promo spesial!",
                    'icon' => '⚠️'
                ];
            }
            
            $periodeData = $sixMonthsAgo->format('F Y') . ' - ' . now()->format('F Y');
            
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
                'message' => 'Prediksi berhasil dilakukan',
                'data' => $hasilPrediksi,
                'rekomendasi' => $rekomendasiPromosi,
                'periode' => $periodeData,
                'bulan_target' => now()->addMonth()->format('F Y'),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Prediksi error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAkurasi($id)
{
    $prediksi = RiwayatPrediksi::findOrFail($id);
    $hasilPrediksi = $prediksi->hasil_prediksi;
    $bulanTarget = $prediksi->bulan_target;
    
    // Ambil data aktual penjualan untuk bulan target
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
        
        $akurasi = RiwayatPrediksi::hitungAkurasi($prediksiJumlah, $aktualJumlah);
        $label = RiwayatPrediksi::getLabelAkurasi($akurasi);
        
        $detailAkurasi[] = [
            'nama_menu' => $namaMenu,
            'prediksi' => $prediksiJumlah,
            'aktual' => $aktualJumlah,
            'akurasi' => $akurasi,
            'kategori' => $label['text']
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
        'detail' => $detailAkurasi
    ]);
}
}