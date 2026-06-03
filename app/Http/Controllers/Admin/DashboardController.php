<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        return view('admin.dashboard', compact(
            'totalKaryawan', 'pendapatanHariIni', 'pendapatanBulanIni',
            'pendapatanTunai', 'pendapatanQris', 'topMenus'
        ));
    }

    public function getDashboardData(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $totalKaryawan = User::where('role', 'kasir')->count();
        $hadirHariIni = 8;
        
        $pendapatanHariIni = Pesanan::whereDate('tanggal', today())->sum('total_bayar');
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
        
        $pendapatanBulanIni = Pesanan::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->sum('total_bayar');
        
        $bulanLalu = $month == 1 ? 12 : $month - 1;
        $tahunBulanLalu = $month == 1 ? $year - 1 : $year;
        $pendapatanBulanLalu = Pesanan::whereMonth('tanggal', $bulanLalu)->whereYear('tanggal', $tahunBulanLalu)->sum('total_bayar');
        
        $persentaseBulanan = 0;
        $trendBulanan = 'sama';
        if ($pendapatanBulanLalu > 0) {
            $persentaseBulanan = (($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100;
            $trendBulanan = $persentaseBulanan > 0 ? 'naik' : ($persentaseBulanan < 0 ? 'turun' : 'sama');
        } elseif ($pendapatanBulanIni > 0 && $pendapatanBulanLalu == 0) {
            $persentaseBulanan = 100;
            $trendBulanan = 'naik';
        }
        
        $tunaiAmount = Pesanan::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->where('metode_bayar', 'tunai')->sum('total_bayar');
        $qrisAmount = Pesanan::whereMonth('tanggal', $month)->whereYear('tanggal', $year)->where('metode_bayar', 'qris')->sum('total_bayar');
        
        return response()->json([
            'totalKaryawan' => $totalKaryawan,
            'hadirHariIni' => $hadirHariIni,
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
        $days = $request->get('days', 7);
        $dates = [];
        $values = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates[] = $date->format('d/m');
            $total = Pesanan::whereDate('tanggal', $date)->sum('total_bayar');
            $values[] = $total;
        }
        
        return response()->json(['labels' => $dates, 'values' => $values]);
    }

    public function getPaymentChart()
    {
        $tunai = Pesanan::whereDate('tanggal', today())->where('metode_bayar', 'tunai')->sum('total_bayar');
        $qris = Pesanan::whereDate('tanggal', today())->where('metode_bayar', 'qris')->sum('total_bayar');
        
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
            $months = DB::table('pesanan')
                ->select(DB::raw('DISTINCT MONTH(tanggal) as month_num'), DB::raw('MONTHNAME(tanggal) as month_name'))
                ->orderBy('month_num', 'asc')
                ->get()
                ->map(function($item) {
                    return [
                        'value' => $item->month_num,
                        'name' => $item->month_name
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