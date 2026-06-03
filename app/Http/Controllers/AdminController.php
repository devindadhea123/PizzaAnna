<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\RiwayatPrediksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['getKategoriForPublic']);
        $this->middleware('role:admin')->except(['getKategoriForPublic']);
    }

    public function dashboard()
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

    public function riwayatPesanan()
    {
        $pesanan = Pesanan::with('kasir', 'detailPesanan.menu')
            ->orderBy('tanggal', 'desc')
            ->paginate(15);
        return view('admin.riwayat-pesanan', compact('pesanan'));
    }

    public function riwayatPrediksi()
    {
        $prediksi = RiwayatPrediksi::orderBy('tanggal_prediksi', 'desc')->get();
        return view('admin.riwayat-prediksi', compact('prediksi'));
    }

    // ==================== MANAJEMEN MENU (VIEW) ====================
    public function manajemenMenu()
    {
        $kategoris = Kategori::all();
        return view('admin.manajemen-menu', compact('kategoris'));
    }

    public function menuCreate()
    {
        $kategoris = Kategori::all();
        return view('admin.menu.create', compact('kategoris'));
    }

    public function menuEdit($id)
    {
        $menu = Menu::findOrFail($id);
        $kategoris = Kategori::all();
        return view('admin.menu.edit', compact('menu', 'kategoris'));
    }

    public function menuStore(Request $request)
    {
        try {
            $request->validate([
                'nama_menu' => 'required|string|max:100',
                'harga' => 'required|numeric|min:0',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
                $gambarPath = $file->storeAs('menu', $fileName, 'public');
            }

            Menu::create([
                'nama_menu' => $request->nama_menu,
                'harga' => $request->harga,
                'id_kategori' => $request->id_kategori,
                'gambar' => $gambarPath,
                'deskripsi' => $request->deskripsi,
            ]);

            return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

   public function updateMenu(Request $request, $id)
{
    try {
        $menu = Menu::findOrFail($id);
        
        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'diskon_jenis' => 'required|in:none,persen',
            'diskon_nilai' => 'required_if:diskon_jenis,persen|numeric|min:0|max:100',
        ]);

        $data = [
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'id_kategori' => $request->id_kategori,
            'diskon_jenis' => $request->diskon_jenis ?? 'none',
            'diskon_nilai' => $request->diskon_nilai ?? 0,
            'deskripsi' => $request->deskripsi,
        ];

        // Proses upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                Storage::disk('public')->delete($menu->gambar);
            }
            
            // Upload gambar baru
            $file = $request->file('gambar');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            $data['gambar'] = $file->storeAs('menu', $fileName, 'public');
        }

        $menu->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diupdate'
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Update menu error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    public function menuDestroy($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            
            if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                Storage::disk('public')->delete($menu->gambar);
            }
            
            $menu->delete();

            return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ==================== KATEGORI ====================
    public function kategori()
    {
        $kategoris = Kategori::withCount('menu')->get();
        return view('admin.kategori', compact('kategoris'));
    }

    // ==================== API UNTUK DASHBOARD ====================
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

    // ==================== ORDERS API ====================
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

    public function exportOrdersExcel(Request $request)
    {
        return response()->json(['message' => 'Export Excel feature coming soon']);
    }

    public function exportOrdersPDF(Request $request)
    {
        return response()->json(['message' => 'Export PDF feature coming soon']);
    }

    // ==================== PREDIKSI API ====================
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
        return response()->json($prediction);
    }

    public function exportPredictionsExcel(Request $request)
    {
        return response()->json(['message' => 'Export Excel feature coming soon']);
    }

    public function exportPredictionsPDF(Request $request)
    {
        return response()->json(['message' => 'Export PDF feature coming soon']);
    }

    // ==================== FILTER OPTIONS ====================
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

    public function getPredictionStatus()
    {
        $bulanIni = now()->format('Y-m');
        $existingPrediksi = RiwayatPrediksi::where('bulan_target', $bulanIni)->first();
        
        if ($existingPrediksi) {
            $nextPrediction = now()->addMonth()->setDay(27);
            return response()->json([
                'can_predict' => false,
                'next_date' => $nextPrediction->format('d F Y'),
                'next_target' => $nextPrediction->format('F Y'),
            ]);
        }
        
        return response()->json([
            'can_predict' => true,
            'next_target' => now()->addMonth()->format('F Y'),
        ]);
    }

    // ==================== MENU MANAGEMENT API ====================
    public function getMenu(Request $request)
    {
        $query = Menu::with('kategori');
        
        if ($request->kategori && $request->kategori != 'all') {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->search) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }
        
        $menus = $query->orderBy('nama_menu', 'asc')->paginate(15);
        
        return response()->json($menus);
    }

    public function getMenuById($id)
    {
        $menu = Menu::with('kategori')->findOrFail($id);
        return response()->json($menu);
    }

    public function storeMenu(Request $request)
    {
        try {
            $request->validate([
                'nama_menu' => 'required|string|max:100',
                'harga' => 'required|numeric|min:0',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'diskon_jenis' => 'required|in:none,persen',
                'diskon_nilai' => 'required_if:diskon_jenis,persen|numeric|min:0|max:100',
            ]);

            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
                $gambarPath = $file->storeAs('menu', $fileName, 'public');
            }

            Menu::create([
                'nama_menu' => $request->nama_menu,
                'harga' => $request->harga,
                'id_kategori' => $request->id_kategori,
                'diskon_jenis' => $request->diskon_jenis ?? 'none',
                'diskon_nilai' => $request->diskon_nilai ?? 0,
                'gambar' => $gambarPath,
                'deskripsi' => $request->deskripsi,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMenu($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            
            if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                Storage::disk('public')->delete($menu->gambar);
            }
            
            $menu->delete();

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== KATEGORI API ====================
    public function getKategori()
    {
        $kategoris = Kategori::withCount('menu')->get();
        return response()->json($kategoris);
    }

    public function getKategoriById($id)
    {
        $kategori = Kategori::findOrFail($id);
        return response()->json($kategori);
    }

    public function storeKategori(Request $request)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:50|unique:kategori,nama_kategori',
            ]);

            $kategori = Kategori::create([
                'nama_kategori' => $request->nama_kategori,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateKategori(Request $request, $id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            
            $request->validate([
                'nama_kategori' => 'required|string|max:50|unique:kategori,nama_kategori,' . $id . ',id_kategori',
            ]);

            $kategori->update([
                'nama_kategori' => $request->nama_kategori,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteKategori($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            
            if ($kategori->menu()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak dapat dihapus karena masih memiliki menu'
                ], 422);
            }
            
            $kategori->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== PUBLIC API (KASIR) ====================
    public function getKategoriForPublic()
    {
        try {
            $kategoris = Kategori::all();
            return response()->json($kategoris);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== PREDIKSI UTAMA ====================
    public function lakukanPrediksi(Request $request)
    {
        try {
            $sixMonthsAgo = now()->subMonths(6);
            
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
            
            foreach ($topMenus as $index => $item) {
                $persentase = $totalTerjualSemua > 0 ? round(($item->total_terjual / $totalTerjualSemua) * 100, 1) : 0;
                $kenaikanPersen = [15, 12, 10, 8, 5];
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
            
            // Rekomendasi Promosi
            if (count($hasilPrediksi) >= 1) {
                $menu1 = $hasilPrediksi[0];
                $rekomendasiPromosi[] = [
                    'menu' => $menu1['nama_menu'],
                    'judul' => ' Buy 1 Get 1',
                    'deskripsi' => "{$menu1['nama_menu']} diprediksi naik {$menu1['kenaikan']}%. Berikan promo Buy 1 Get 1!",
                    'icon' => '🎁'
                ];
            }
            
            if (count($hasilPrediksi) >= 2) {
                $menu1 = $hasilPrediksi[0];
                $menu2 = $hasilPrediksi[1];
                $rekomendasiPromosi[] = [
                    'menu' => "{$menu1['nama_menu']} + {$menu2['nama_menu']}",
                    'judul' => ' Bundling Hemat',
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
                    'judul' => ' Promo Spesial',
                    'deskripsi' => "{$menu3['nama_menu']} diprediksi menjadi menu populer. Berikan promo spesial!",
                    'icon' => '⚠️'
                ];
            }
            
            $periodeData = $sixMonthsAgo->format('F Y') . ' - ' . now()->format('F Y');
            $bulanTarget = now()->addMonth()->format('Y-m');
            
            $existing = RiwayatPrediksi::where('bulan_target', $bulanTarget)->first();
            
            if ($existing) {
                $existing->update([
                    'tanggal_prediksi' => now(),
                    'data_yang_dipakai' => $periodeData,
                    'hasil_prediksi' => json_encode($hasilPrediksi),
                    'rekomendasi_promosi' => json_encode($rekomendasiPromosi),
                ]);
            } else {
                RiwayatPrediksi::create([
                    'tanggal_prediksi' => now(),
                    'bulan_target' => $bulanTarget,
                    'data_yang_dipakai' => $periodeData,
                    'hasil_prediksi' => json_encode($hasilPrediksi),
                    'rekomendasi_promosi' => json_encode($rekomendasiPromosi),
                ]);
            }
            
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
}