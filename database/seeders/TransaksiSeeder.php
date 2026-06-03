<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        // HAPUS DATA LAMA (Opsional, hati-hati)
        // DB::table('detail_pesanan_topping')->delete();
        // DB::table('detail_pesanan')->delete();
        // DB::table('pesanan')->delete();

        $menuIds = DB::table('menu')->pluck('id_menu')->toArray();
        $kasirIds = DB::table('users')->where('role', 'kasir')->pluck('id_user')->toArray();
        
        if (empty($kasirIds)) {
            // Jika tidak ada kasir, ambil user admin
            $kasirIds = DB::table('users')->where('role', 'admin')->pluck('id_user')->toArray();
        }

        if (empty($menuIds) || empty($kasirIds)) {
            $this->command->error('Tidak ada menu atau kasir! Jalankan MenuSeeder dan UserSeeder dulu.');
            return;
        }

        // Data penjualan per bulan (6 bulan terakhir)
        $dataPenjualan = [
            // Bulan 6 (terlama) - Desember 2025
            [
                'bulan' => 12,
                'tahun' => 2025,
                'transaksi' => [
                    ['menu' => 'Teh / Es Teh', 'qty' => 1200],
                    ['menu' => 'Donut / Bomboloni', 'qty' => 800],
                    ['menu' => 'Spicy Chicken Mushroom', 'qty' => 300],
                    ['menu' => 'French Fries', 'qty' => 400],
                    ['menu' => 'Nasi Goreng', 'qty' => 200],
                ]
            ],
            // Bulan 5 - Januari 2026
            [
                'bulan' => 1,
                'tahun' => 2026,
                'transaksi' => [
                    ['menu' => 'Teh / Es Teh', 'qty' => 1350],
                    ['menu' => 'Donut / Bomboloni', 'qty' => 900],
                    ['menu' => 'Spicy Chicken Mushroom', 'qty' => 350],
                    ['menu' => 'French Fries', 'qty' => 450],
                    ['menu' => 'Nasi Goreng', 'qty' => 250],
                ]
            ],
            // Bulan 4 - Februari 2026
            [
                'bulan' => 2,
                'tahun' => 2026,
                'transaksi' => [
                    ['menu' => 'Teh / Es Teh', 'qty' => 1500],
                    ['menu' => 'Donut / Bomboloni', 'qty' => 1000],
                    ['menu' => 'Spicy Chicken Mushroom', 'qty' => 400],
                    ['menu' => 'French Fries', 'qty' => 500],
                    ['menu' => 'Nasi Goreng', 'qty' => 300],
                ]
            ],
            // Bulan 3 - Maret 2026
            [
                'bulan' => 3,
                'tahun' => 2026,
                'transaksi' => [
                    ['menu' => 'Teh / Es Teh', 'qty' => 1650],
                    ['menu' => 'Donut / Bomboloni', 'qty' => 1100],
                    ['menu' => 'Spicy Chicken Mushroom', 'qty' => 450],
                    ['menu' => 'French Fries', 'qty' => 550],
                    ['menu' => 'Nasi Goreng', 'qty' => 350],
                ]
            ],
            // Bulan 2 - April 2026
            [
                'bulan' => 4,
                'tahun' => 2026,
                'transaksi' => [
                    ['menu' => 'Teh / Es Teh', 'qty' => 1800],
                    ['menu' => 'Donut / Bomboloni', 'qty' => 1200],
                    ['menu' => 'Spicy Chicken Mushroom', 'qty' => 500],
                    ['menu' => 'French Fries', 'qty' => 600],
                    ['menu' => 'Nasi Goreng', 'qty' => 400],
                ]
            ],
            // Bulan 1 (terbaru) - Mei 2026
            [
                'bulan' => 5,
                'tahun' => 2026,
                'transaksi' => [
                    ['menu' => 'Teh / Es Teh', 'qty' => 2000],
                    ['menu' => 'Donut / Bomboloni', 'qty' => 1350],
                    ['menu' => 'Spicy Chicken Mushroom', 'qty' => 550],
                    ['menu' => 'French Fries', 'qty' => 650],
                    ['menu' => 'Nasi Goreng', 'qty' => 450],
                ]
            ],
        ];

        $customerNames = ['Budi', 'Ani', 'Candra', 'Dewi', 'Eko', 'Fany', 'Gunawan', 'Hani', 'Iwan', 'Joko'];

        foreach ($dataPenjualan as $bulanData) {
            $tanggalMulai = Carbon::create($bulanData['tahun'], $bulanData['bulan'], 1);
            $tanggalAkhir = Carbon::create($bulanData['tahun'], $bulanData['bulan'], 28);
            
            // Buat 20-30 transaksi per bulan
            $jumlahTransaksi = rand(20, 30);
            
            for ($i = 0; $i < $jumlahTransaksi; $i++) {
                $tanggal = Carbon::createFromTimestamp(rand($tanggalMulai->timestamp, $tanggalAkhir->timestamp));
                $customerName = $customerNames[array_rand($customerNames)];
                $kasirId = $kasirIds[array_rand($kasirIds)];
                $metodeBayar = rand(0, 1) ? 'tunai' : 'qris';
                $noMeja = rand(1, 10);
                $noInvoice = 'INV-' . $tanggal->format('Ymd') . '-' . strtoupper(Str::random(5));
                
                // Pilih 1-3 menu untuk transaksi ini
                $selectedItems = [];
                $jumlahItem = rand(1, 3);
                $availableItems = $bulanData['transaksi'];
                shuffle($availableItems);
                
                $totalBayar = 0;
                
                for ($j = 0; $j < $jumlahItem; $j++) {
                    $item = $availableItems[$j];
                    $menu = DB::table('menu')->where('nama_menu', $item['menu'])->first();
                    
                    if (!$menu) continue;
                    
                    $qty = rand(1, 3);
                    $hargaSatuan = $menu->harga;
                    $subtotal = $qty * $hargaSatuan;
                    $totalBayar += $subtotal;
                    
                    $selectedItems[] = [
                        'id_menu' => $menu->id_menu,
                        'nama_menu' => $menu->nama_menu,
                        'qty' => $qty,
                        'harga_satuan' => $hargaSatuan,
                        'subtotal' => $subtotal,
                        'total_terjual' => $item['qty']
                    ];
                }
                
                if (empty($selectedItems)) continue;
                
                // Simpan pesanan
                $pesananId = DB::table('pesanan')->insertGetId([
                    'no_invoice' => $noInvoice,
                    'tanggal' => $tanggal,
                    'id_kasir' => $kasirId,
                    'nama_customer' => $customerName,
                    'no_meja' => $noMeja,
                    'total_bayar' => $totalBayar,
                    'metode_bayar' => $metodeBayar,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);
                
                // Simpan detail pesanan
                foreach ($selectedItems as $item) {
                    DB::table('detail_pesanan')->insert([
                        'id_pesanan' => $pesananId,
                        'id_menu' => $item['id_menu'],
                        'jumlah' => $item['qty'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $item['subtotal'],
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);
                }
            }
        }
        
        $this->command->info('✅ Transaksi 6 bulan berhasil di-generate!');
    }
}