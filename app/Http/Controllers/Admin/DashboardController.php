<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $totalKaryawan = User::where('role', 'kasir')->count();
        $pendapatanHariIni = Pesanan::whereDate('tanggal', today())->sum('total_bayar');
        $pendapatanBulanIni = Pesanan::whereMonth('tanggal', now()->month)->sum('total_bayar');
        $pendapatanTunai = Pesanan::whereDate('tanggal', today())->where('metode_bayar', 'tunai')->sum('total_bayar');
        $pendapatanQris = Pesanan::whereDate('tanggal', today())->where('metode_bayar', 'qris')->sum('total_bayar');
        
        $topMenus = DetailPesanan::select('id_menu', DB::raw('SUM(jumlah) as total_terjual'))
            ->with('menu')
            ->groupBy('id_menu')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();

        // ✅ AMBIL BULAN DAN TAHUN YANG ADA DI TABEL PESANAN
        $bulanTersedia = Pesanan::selectRaw('DISTINCT YEAR(tanggal) as tahun, MONTH(tanggal) as bulan')
            ->whereNotNull('tanggal')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        // ✅ FORMAT UNTUK DROPDOWN
        $daftarBulan = [];
        foreach ($bulanTersedia as $item) {
            $bulanNama = Carbon::create($item->tahun, $item->bulan, 1)->translatedFormat('F');
            $daftarBulan[] = [
                'bulan' => $item->bulan,
                'tahun' => $item->tahun,
                'label' => $bulanNama . ' ' . $item->tahun,
                'value' => $item->tahun . '-' . str_pad($item->bulan, 2, '0', STR_PAD_LEFT)
            ];
        }

        // ✅ KIRIM KE VIEW
        return view('admin.dashboard', compact(
            'totalKaryawan', 
            'pendapatanHariIni', 
            'pendapatanBulanIni',
            'pendapatanTunai', 
            'pendapatanQris', 
            'topMenus',
            'daftarBulan' 
        ));
    }



public function getDashboardData(Request $request)
{
    $month = $request->get('month', now()->month);
    $year = $request->get('year', now()->year);
    
    // Pendapatan hari ini (tidak berubah)
    $pendapatanHariIni = Pesanan::whereDate('tanggal', today())->sum('total_bayar');
    
    // Pendapatan kemarin untuk trend
    $pendapatanKemarin = Pesanan::whereDate('tanggal', today()->subDay())->sum('total_bayar');
    
    $persentaseHarian = 0;
    $trendHarian = 'sama';
    if ($pendapatanKemarin > 0) {
        $persentaseHarian = (($pendapatanHariIni - $pendapatanKemarin) / $pendapatanKemarin) * 100;
        $trendHarian = $persentaseHarian > 0 ? 'naik' : ($persentaseHarian < 0 ? 'turun' : 'sama');
    } elseif ($pendapatanHariIni > 0 && $pendapatanKemarin == 0) {
        $persentaseHarian = 100;
        $trendHarian = 'naik';
    }
    
    // ✅ Pendapatan bulan ini (sesuai filter)
    $pendapatanBulanIni = Pesanan::whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->sum('total_bayar');
    
    // Bulan lalu untuk trend
    $bulanLalu = $month == 1 ? 12 : $month - 1;
    $tahunBulanLalu = $month == 1 ? $year - 1 : $year;
    $pendapatanBulanLalu = Pesanan::whereMonth('tanggal', $bulanLalu)
        ->whereYear('tanggal', $tahunBulanLalu)
        ->sum('total_bayar');
    
    $persentaseBulanan = 0;
    $trendBulanan = 'sama';
    if ($pendapatanBulanLalu > 0) {
        $persentaseBulanan = (($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100;
        $trendBulanan = $persentaseBulanan > 0 ? 'naik' : ($persentaseBulanan < 0 ? 'turun' : 'sama');
    } elseif ($pendapatanBulanIni > 0 && $pendapatanBulanLalu == 0) {
        $persentaseBulanan = 100;
        $trendBulanan = 'naik';
    }
    
    $tunaiAmount = Pesanan::whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->where('metode_bayar', 'tunai')
        ->sum('total_bayar');
    
    $qrisAmount = Pesanan::whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->where('metode_bayar', 'qris')
        ->sum('total_bayar');
    
    return response()->json([
        'pendapatanHariIni' => $pendapatanHariIni,
        'pendapatanBulanIni' => $pendapatanBulanIni,
        'persentaseHarian' => round($persentaseHarian, 1),
        'trendHarian' => $trendHarian,
        'persentaseBulanan' => round($persentaseBulanan, 1),
        'trendBulanan' => $trendBulanan,
        'tunaiAmount' => $tunaiAmount,
        'qrisAmount' => $qrisAmount,
    ]);
}

public function getOmzetChart(Request $request)
{
    $month = $request->get('month', now()->month);
    $year = $request->get('year', now()->year);
    $days = $request->get('days', 7);
    
    $dates = [];
    $values = [];
    
    // Ambil tanggal awal dan akhir berdasarkan filter
    $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
    $endDate = $startDate->copy()->endOfMonth();
    
    // Jika days < 31, tampilkan beberapa hari terakhir dari bulan tersebut
    if ($days < 31) {
        $startDate = $endDate->copy()->subDays($days - 1);
    }
    
    for ($i = 0; $i < $days; $i++) {
        $date = $startDate->copy()->addDays($i);
        if ($date > $endDate) break;
        
        $dates[] = $date->format('d/m');
        $total = Pesanan::whereDate('tanggal', $date)->sum('total_bayar');
        $values[] = $total;
    }
    
    return response()->json(['labels' => $dates, 'values' => $values]);
}



public function getPaymentChart(Request $request)
{
    $month = $request->get('month', now()->month);
    $year = $request->get('year', now()->year);
    
    $tunai = Pesanan::whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->where('metode_bayar', 'tunai')
        ->sum('total_bayar');
    
    $qris = Pesanan::whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->where('metode_bayar', 'qris')
        ->sum('total_bayar');
    
    return response()->json(['tunai' => $tunai, 'qris' => $qris]);
}

    public function getTopMenu()
    {
        $topMenus = DetailPesanan::select('id_menu', DB::raw('SUM(jumlah) as total_terjual'), DB::raw('SUM(subtotal) as total_pendapatan'))
            ->with('menu')
            ->groupBy('id_menu')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'nama_menu' => $item->menu->nama_menu ?? 'Menu',
                    'total_terjual' => $item->total_terjual,
                    'total_pendapatan' => $item->total_pendapatan,
                ];
            });
        
        return response()->json($topMenus);
    }

    public function getRecentOrders()
    {
        $orders = Pesanan::with('kasir')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json($orders);
    }

   public function getFilterOptions(Request $request)
{
    try {
        // Ambil bulan yang tersedia dari database
        $availableMonths = DB::table('pesanan')
            ->select(DB::raw('DISTINCT MONTH(tanggal) as month_num'))
            ->orderBy('month_num', 'asc')
            ->get();
        
        // Array nama bulan dalam Bahasa Indonesia
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $months = $availableMonths->map(function($item) use ($bulanList) {
            return [
                'value' => $item->month_num,
                'name' => $bulanList[$item->month_num]  // ✅ PAKAI BAHASA INDONESIA
            ];
        });
        
        $years = DB::table('pesanan')
            ->select(DB::raw('DISTINCT YEAR(tanggal) as year'))
            ->orderBy('year', 'asc')
            ->pluck('year');
        
        $lastOrder = DB::table('pesanan')
            ->select(DB::raw('MONTH(tanggal) as month'), DB::raw('YEAR(tanggal) as year'))
            ->orderBy('tanggal', 'desc')
            ->first();
        
        $defaultMonth = $lastOrder ? $lastOrder->month : date('n');
        $defaultYear = $lastOrder ? $lastOrder->year : date('Y');
        
        return response()->json([
            'success' => true,
            'months' => $months,
            'years' => $years,
            'defaultMonth' => $defaultMonth,
            'defaultYear' => $defaultYear
        ]);
        
    } catch (\Exception $e) {
        Log::error('getFilterOptions error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'months' => [],
            'years' => [],
            'defaultMonth' => date('n'),
            'defaultYear' => date('Y')
        ], 500);
    }
}
        
}
