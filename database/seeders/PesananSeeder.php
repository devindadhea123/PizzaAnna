<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data menu dan kasir
        $menuIds = DB::table('menu')->pluck('id_menu')->toArray();
        $kasirIds = DB::table('users')->where('role', 'kasir')->pluck('id_user')->toArray();
        
        if (empty($kasirIds)) {
            $kasirIds = DB::table('users')->pluck('id_user')->toArray();
        }

        if (empty($menuIds)) {
            $this->command->error('Tidak ada menu! Jalankan MenuSeeder dulu.');
            return;
        }

        $customerNames = ['Budi', 'Ani', 'Candra', 'Dewi', 'Eko', 'Fany', 'Gunawan', 'Hani', 'Iwan', 'Joko'];
        $metodeBayar = ['tunai', 'qris'];
        
        // Data penjualan 6 bulan terakhir (Desember 2025 - Mei 2026)
        $bulanData = [
            ['bulan' => 12, 'tahun' => 2025, 'tgl_mulai' => 1, 'tgl_akhir' => 28],
            ['bulan' => 1, 'tahun' => 2026, 'tgl_mulai' => 1, 'tgl_akhir' => 28],
            ['bulan' => 2, 'tahun' => 2026, 'tgl_mulai' => 1, 'tgl_akhir' => 28],
            ['bulan' => 3, 'tahun' => 2026, 'tgl_mulai' => 1, 'tgl_akhir' => 28],
            ['bulan' => 4, 'tahun' => 2026, 'tgl_mulai' => 1, 'tgl_akhir' => 28],
            ['bulan' => 5, 'tahun' => 2026, 'tgl_mulai' => 1, 'tgl_akhir' => 28],
        ];
        
        foreach ($bulanData as $bulan) {
            $tanggalMulai = Carbon::create($bulan['tahun'], $bulan['bulan'], $bulan['tgl_mulai']);
            $tanggalAkhir = Carbon::create($bulan['tahun'], $bulan['bulan'], $bulan['tgl_akhir']);
            
            // Buat 20-30 transaksi per bulan
            $jumlahTransaksi = rand(20, 30);
            
            for ($i = 0; $i < $jumlahTransaksi; $i++) {
                $tanggal = Carbon::createFromTimestamp(rand($tanggalMulai->timestamp, $tanggalAkhir->timestamp));
                $customerName = $customerNames[array_rand($customerNames)];
                $kasirId = $kasirIds[array_rand($kasirIds)];
                $metode = $metodeBayar[array_rand($metodeBayar)];
                $noMeja = rand(1, 10);
                $noInvoice = 'INV-' . $tanggal->format('Ymd') . '-' . strtoupper(Str::random(5));
                
                // Pilih 1-3 menu random
                $selectedMenus = (array) array_rand($menuIds, rand(1, 3));
                $totalBayar = 0;
                $details = [];
                
                foreach ($selectedMenus as $menuId) {
                    $menu = DB::table('menu')->where('id_menu', $menuId)->first();
                    if (!$menu) continue;
                    
                    $qty = rand(1, 4);
                    $subtotal = $qty * $menu->harga;
                    $totalBayar += $subtotal;
                    
                    $details[] = [
                        'id_menu' => $menu->id_menu,
                        'qty' => $qty,
                        'harga_satuan' => $menu->harga,
                        'subtotal' => $subtotal,
                    ];
                }
                
                if (empty($details)) continue;
                
                // Simpan pesanan
                $pesananId = DB::table('pesanan')->insertGetId([
                    'no_invoice' => $noInvoice,
                    'tanggal' => $tanggal,
                    'id_kasir' => $kasirId,
                    'nama_customer' => $customerName,
                    'no_meja' => $noMeja,
                    'total_bayar' => $totalBayar,
                    'metode_bayar' => $metode,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);
                
                // Simpan detail pesanan
                foreach ($details as $detail) {
                    DB::table('detail_pesanan')->insert([
                        'id_pesanan' => $pesananId,
                        'id_menu' => $detail['id_menu'],
                        'jumlah' => $detail['qty'],
                        'harga_satuan' => $detail['harga_satuan'],
                        'subtotal' => $detail['subtotal'],
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ]);
                }
            }
        }
        
        $this->command->info('✅ Transaksi pesanan 6 bulan berhasil di-generate!');
    }
}