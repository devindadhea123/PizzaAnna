<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataDesember2025Seeder extends Seeder
{
    public function run()
    {
        $idKasir = 2;
        
        $this->command->info('Menggunakan ID Kasir: ' . $idKasir);
        
        $data = $this->getDesemberData();
        $this->processData($data, $idKasir);
        
        $this->command->info('Data Desember 2025 berhasil diimport!');
    }
    
    private function processData($data, $idKasir)
    {
        $toppingId = [
            'Keju Pinggir' => 1,
            'Keju Topping' => 2,
            'Keju Bites' => 3,
            'Sosis Bites' => 4,
            'Tambah Keju' => 5,
        ];
        
        $invoices = [];
        foreach ($data as $row) {
            $key = $row[0] . '|' . $row[1];
            if (!isset($invoices[$key])) {
                $invoices[$key] = [
                    'tanggal' => $row[0],
                    'customer' => $row[1],
                    'metode' => $row[8],
                    'items' => [],
                    'total' => 0,
                ];
            }
            $invoices[$key]['items'][] = [
                'id_menu' => $row[2],
                'jumlah' => $row[3],
                'ukuran' => $row[4],
                'harga' => $row[5],
                'subtotal' => $row[6],
                'topping' => $row[7],
            ];
            $invoices[$key]['total'] += $row[6];
        }
        
        $invoiceCounter = [];
        
        foreach ($invoices as $inv) {
            $dateKey = date('Ymd', strtotime($inv['tanggal']));
            if (!isset($invoiceCounter[$dateKey])) {
                $invoiceCounter[$dateKey] = 1;
            }
            
            $noInv = 'INV-' . $dateKey . '-' . str_pad($invoiceCounter[$dateKey], 4, '0', STR_PAD_LEFT);
            $invoiceCounter[$dateKey]++;
            
            $jam = rand(10, 21);
            $menit = str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
            
            $idPesanan = DB::table('pesanan')->insertGetId([
                'no_invoice' => $noInv,
                'tanggal' => $inv['tanggal'] . ' ' . $jam . ':' . $menit . ':00',
                'id_kasir' => $idKasir,
                'nama_customer' => $inv['customer'],
                'no_meja' => rand(0, 8),
                'total_bayar' => $inv['total'],
                'metode_bayar' => $inv['metode'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            foreach ($inv['items'] as $item) {
                $idDetail = DB::table('detail_pesanan')->insertGetId([
                    'id_pesanan' => $idPesanan,
                    'id_menu' => $item['id_menu'],
                    'ukuran' => $item['ukuran'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                if ($item['topping'] != null && isset($toppingId[$item['topping']])) {
                    DB::table('detail_pesanan_topping')->insert([
                        'detail_pesanan_id' => $idDetail,
                        'topping_id' => $toppingId[$item['topping']],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
    
    private function getDesemberData()
    {
        return [
            // ==================== TANGGAL 1 DESEMBER 2025 ====================
            ['2025-12-01', 'Andi', 8, 2, 'L', 75000, 150000, 'Keju Pinggir', 'Tunai'],
            ['2025-12-01', 'Andi', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-12-01', 'Budi', 10, 1, null, 15000, 15000, null, 'QRIS'],
            ['2025-12-01', 'Caca', 15, 2, null, 9000, 18000, null, 'QRIS'],
            ['2025-12-01', 'Dina', 8, 1, 'M', 41000, 41000, 'Keju Topping', 'Tunai'],
            ['2025-12-01', 'Dina', 18, 1, null, 5000, 5000, null, 'Tunai'],
            ['2025-12-01', 'Edo', 22, 1, 'L', 79000, 79000, 'Sosis Bites', 'QRIS'],
            ['2025-12-01', 'Edo', 20, 1, null, 6000, 6000, null, 'QRIS'],
            
            // ==================== TANGGAL 2 DESEMBER 2025 ====================
            ['2025-12-02', 'Fitri', 12, 2, null, 10000, 20000, null, 'Tunai'],
            ['2025-12-02', 'Galih', 8, 1, 'S', 25000, 25000, null, 'QRIS'],
            ['2025-12-02', 'Hana', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-12-02', 'Irfan', 6, 1, 'M', 51000, 51000, 'Keju Bites', 'Tunai'],
            ['2025-12-02', 'Juli', 16, 1, null, 9000, 9000, null, 'QRIS'],
            ['2025-12-02', 'Kiki', 8, 2, 'L', 75000, 150000, 'Keju Pinggir', 'Tunai'],
            ['2025-12-02', 'Kiki', 18, 1, null, 5000, 5000, null, 'Tunai'],
            
            // ==================== TANGGAL 3 DESEMBER 2025 ====================
            ['2025-12-03', 'Lina', 15, 2, null, 9000, 18000, null, 'QRIS'],
            ['2025-12-03', 'Maman', 10, 1, null, 15000, 15000, 'Tambah Keju', 'Tunai'],
            ['2025-12-03', 'Nina', 8, 1, 'M', 41000, 41000, null, 'QRIS'],
            ['2025-12-03', 'Nina', 17, 1, null, 4000, 4000, null, 'QRIS'],
            ['2025-12-03', 'Oki', 14, 2, null, 12000, 24000, null, 'Tunai'],
            
            // ==================== TANGGAL 4 DESEMBER 2025 ====================
            ['2025-12-04', 'Putra', 8, 1, 'L', 75000, 75000, null, 'Tunai'],
            ['2025-12-04', 'Qori', 11, 1, 'L', 95000, 95000, 'Keju Topping', 'QRIS'],
            ['2025-12-04', 'Qori', 21, 1, null, 12000, 12000, null, 'QRIS'],
            ['2025-12-04', 'Rudi', 12, 3, null, 10000, 30000, null, 'Tunai'],
            ['2025-12-04', 'Sari', 17, 2, null, 4000, 8000, null, 'Tunai'],
            
            // ==================== TANGGAL 5 DESEMBER 2025 ====================
            ['2025-12-05', 'Tono', 8, 2, 'L', 75000, 150000, 'Sosis Bites', 'Tunai'],
            ['2025-12-05', 'Tono', 22, 1, null, 12000, 12000, null, 'Tunai'],
            ['2025-12-05', 'Uci', 1, 1, 'S', 25000, 25000, null, 'QRIS'],
            ['2025-12-05', 'Uci', 18, 1, null, 5000, 5000, null, 'QRIS'],
            ['2025-12-05', 'Vino', 8, 1, 'M', 41000, 41000, null, 'Tunai'],
            ['2025-12-05', 'Vino', 15, 1, null, 9000, 9000, null, 'Tunai'],
            
            // ==================== TANGGAL 6 DESEMBER 2025 ====================
            ['2025-12-06', 'Winda', 5, 1, 'S', 35000, 35000, 'Keju Bites', 'QRIS'],
            ['2025-12-06', 'Winda', 19, 1, null, 6000, 6000, null, 'QRIS'],
            ['2025-12-06', 'Yoga', 10, 2, null, 15000, 30000, null, 'Tunai'],
            ['2025-12-06', 'Yoga', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-12-06', 'Zaki', 8, 1, 'L', 75000, 75000, 'Keju Pinggir', 'Tunai'],
            ['2025-12-06', 'Zaki', 21, 1, null, 12000, 12000, null, 'Tunai'],
            
            // ==================== TANGGAL 7 DESEMBER 2025 ====================
            ['2025-12-07', 'Aji', 13, 1, null, 18000, 18000, null, 'QRIS'],
            ['2025-12-07', 'Bella', 17, 2, null, 4000, 8000, null, 'QRIS'],
            ['2025-12-07', 'Ciko', 4, 2, 'M', 30000, 60000, null, 'Tunai'],
            ['2025-12-07', 'Ciko', 16, 2, null, 9000, 18000, null, 'Tunai'],
            ['2025-12-07', 'Dani', 8, 2, 'L', 75000, 150000, 'Keju Topping', 'QRIS'],
            ['2025-12-07', 'Dani', 18, 2, null, 5000, 10000, null, 'QRIS'],
            
            // ==================== TANGGAL 8 DESEMBER 2025 ====================
            ['2025-12-08', 'Erna', 10, 2, null, 15000, 30000, 'Tambah Keju', 'Tunai'],
            ['2025-12-08', 'Erna', 20, 1, null, 6000, 6000, null, 'Tunai'],
            ['2025-12-08', 'Fahmi', 8, 1, 'S', 25000, 25000, 'Sosis Bites', 'QRIS'],
            ['2025-12-08', 'Fahmi', 22, 1, null, 12000, 12000, null, 'QRIS'],
            
            // ==================== TANGGAL 9 DESEMBER 2025 ====================
            ['2025-12-09', 'Gita', 8, 2, 'M', 41000, 82000, null, 'Tunai'],
            ['2025-12-09', 'Gita', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-12-09', 'Hendra', 7, 1, 'M', 51000, 51000, 'Keju Bites', 'QRIS'],
            ['2025-12-09', 'Hendra', 21, 2, null, 12000, 24000, null, 'QRIS'],
            ['2025-12-09', 'Indah', 8, 1, 'L', 75000, 75000, null, 'Tunai'],
            ['2025-12-09', 'Indah', 15, 2, null, 9000, 18000, null, 'Tunai'],
            
            // ==================== TANGGAL 10 DESEMBER 2025 ====================
            ['2025-12-10', 'Jefri', 5, 1, 'M', 61000, 61000, 'Keju Pinggir', 'QRIS'],
            ['2025-12-10', 'Jefri', 17, 2, null, 4000, 8000, null, 'QRIS'],
            ['2025-12-10', 'Kartika', 10, 2, null, 15000, 30000, null, 'Tunai'],
            ['2025-12-10', 'Kartika', 18, 1, null, 5000, 5000, null, 'Tunai'],
            ['2025-12-10', 'Leo', 8, 1, 'L', 75000, 75000, 'Sosis Bites', 'QRIS'],
            ['2025-12-10', 'Leo', 19, 1, null, 6000, 6000, null, 'QRIS'],
            
            // ==================== TANGGAL 11 DESEMBER 2025 ====================
            ['2025-12-11', 'Maya', 12, 2, null, 10000, 20000, null, 'Tunai'],
            ['2025-12-11', 'Nanda', 1, 1, 'M', 42000, 42000, 'Keju Topping', 'QRIS'],
            ['2025-12-11', 'Nanda', 17, 1, null, 4000, 4000, null, 'QRIS'],
            ['2025-12-11', 'Oki', 8, 2, 'L', 75000, 150000, null, 'Tunai'],
            ['2025-12-11', 'Puri', 22, 1, null, 12000, 12000, null, 'Tunai'],
            
            // ==================== TANGGAL 12 DESEMBER 2025 ====================
            ['2025-12-12', 'Qori', 4, 2, 'S', 19000, 38000, null, 'QRIS'],
            ['2025-12-12', 'Qori', 17, 2, null, 4000, 8000, null, 'QRIS'],
            ['2025-12-12', 'Rafly', 8, 1, 'M', 41000, 41000, 'Keju Bites', 'Tunai'],
            ['2025-12-12', 'Rafly', 20, 1, null, 6000, 6000, null, 'QRIS'],
            ['2025-12-12', 'Siska', 15, 2, null, 9000, 18000, null, 'QRIS'],
            
            // ==================== TANGGAL 13 DESEMBER 2025 ====================
            ['2025-12-13', 'Taufik', 1, 1, 'L', 71000, 71000, 'Keju Pinggir', 'Tunai'],
            ['2025-12-13', 'Taufik', 16, 1, null, 9000, 9000, null, 'QRIS'],
            ['2025-12-13', 'Ulya', 8, 2, 'M', 41000, 82000, null, 'Tunai'],
            ['2025-12-13', 'Vita', 21, 1, null, 12000, 12000, null, 'QRIS'],
            
            // ==================== TANGGAL 14 DESEMBER 2025 ====================
            ['2025-12-14', 'Wawan', 6, 1, 'M', 51000, 51000, 'Sosis Bites', 'Tunai'],
            ['2025-12-14', 'Wawan', 18, 1, null, 5000, 5000, null, 'QRIS'],
            ['2025-12-14', 'Xena', 8, 1, 'L', 75000, 75000, 'Keju Topping', 'Tunai'],
            ['2025-12-14', 'Yudi', 10, 1, null, 15000, 15000, null, 'QRIS'],
            ['2025-12-14', 'Yudi', 18, 1, null, 5000, 5000, null, 'QRIS'],
            
            // ==================== TANGGAL 15 DESEMBER 2025 ====================
            ['2025-12-15', 'Zaki', 22, 2, 'M', 47000, 94000, 'Keju Topping', 'Tunai'],
            ['2025-12-15', 'Zaki', 22, 2, null, 12000, 24000, null, 'Tunai'],
            ['2025-12-15', 'Ani', 8, 1, 'L', 75000, 75000, null, 'QRIS'],
            ['2025-12-15', 'Ani', 15, 2, null, 9000, 18000, null, 'QRIS'],
            ['2025-12-15', 'Bagas', 2, 1, 'M', 38000, 38000, 'Keju Pinggir', 'Tunai'],
            ['2025-12-15', 'Bagas', 19, 1, null, 6000, 6000, null, 'Tunai'],
            
            // ==================== TANGGAL 16 DESEMBER 2025 ====================
            ['2025-12-16', 'Citra', 10, 3, null, 15000, 45000, null, 'QRIS'],
            ['2025-12-16', 'Citra', 17, 3, null, 4000, 12000, null, 'QRIS'],
            ['2025-12-16', 'Dewi', 8, 2, 'L', 75000, 150000, 'Sosis Bites', 'Tunai'],
            ['2025-12-16', 'Dewi', 21, 2, null, 12000, 24000, null, 'Tunai'],
            ['2025-12-16', 'Eko', 4, 1, 'M', 30000, 30000, null, 'QRIS'],
            ['2025-12-16', 'Eko', 18, 2, null, 5000, 10000, null, 'QRIS'],
            
            // ==================== TANGGAL 17 DESEMBER 2025 ====================
            ['2025-12-17', 'Fira', 1, 2, 'S', 25000, 50000, 'Keju Topping', 'Tunai'],
            ['2025-12-17', 'Fira', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-12-17', 'Gilang', 14, 2, null, 12000, 24000, null, 'QRIS'],
            ['2025-12-17', 'Hanif', 8, 1, 'S', 25000, 25000, null, 'Tunai'],
            
            // ==================== TANGGAL 18 DESEMBER 2025 ====================
            ['2025-12-18', 'Intan', 13, 1, null, 18000, 18000, null, 'QRIS'],
            ['2025-12-18', 'Intan', 20, 1, null, 6000, 6000, null, 'QRIS'],
            ['2025-12-18', 'Joni', 12, 3, null, 10000, 30000, null, 'Tunai'],
            ['2025-12-18', 'Karin', 8, 2, 'L', 75000, 150000, 'Keju Bites', 'QRIS'],
            ['2025-12-18', 'Karin', 22, 1, null, 12000, 12000, null, 'QRIS'],
            
            // ==================== TANGGAL 19 DESEMBER 2025 ====================
            ['2025-12-19', 'Lani', 8, 1, 'M', 41000, 41000, null, 'Tunai'],
            ['2025-12-19', 'Lani', 17, 1, null, 4000, 4000, null, 'Tunai'],
            ['2025-12-19', 'Miko', 10, 2, null, 15000, 30000, 'Tambah Keju', 'QRIS'],
            ['2025-12-19', 'Miko', 15, 1, null, 9000, 9000, null, 'QRIS'],
            ['2025-12-19', 'Nina', 8, 1, 'L', 75000, 75000, 'Keju Pinggir', 'Tunai'],
            
            // ==================== TANGGAL 20 DESEMBER 2025 ====================
            ['2025-12-20', 'Oca', 11, 1, 'S', 35000, 35000, null, 'QRIS'],
            ['2025-12-20', 'Oca', 18, 1, null, 5000, 5000, null, 'QRIS'],
            ['2025-12-20', 'Prima', 10, 2, null, 15000, 30000, null, 'Tunai'],
            ['2025-12-20', 'Rina', 8, 2, 'L', 75000, 150000, 'Sosis Bites', 'QRIS'],
            ['2025-12-20', 'Rina', 19, 2, null, 6000, 12000, null, 'QRIS'],
            
            // ==================== TANGGAL 21 DESEMBER 2025 ====================
            ['2025-12-21', 'Soni', 9, 1, 'M', 43000, 43000, null, 'Tunai'],
            ['2025-12-21', 'Tari', 8, 2, 'L', 75000, 150000, 'Keju Topping', 'QRIS'],
            ['2025-12-21', 'Tari', 21, 2, null, 12000, 24000, null, 'QRIS'],
            ['2025-12-21', 'Ujang', 4, 2, 'L', 53000, 106000, null, 'Tunai'],
            ['2025-12-21', 'Ujang', 17, 2, null, 4000, 8000, null, 'Tunai'],
            
            // ==================== TANGGAL 22 DESEMBER 2025 ====================
            ['2025-12-22', 'Vina', 8, 1, 'L', 75000, 75000, 'Keju Pinggir', 'QRIS'],
            ['2025-12-22', 'Vina', 16, 2, null, 9000, 18000, null, 'QRIS'],
            ['2025-12-22', 'Wahyu', 3, 2, 'M', 43000, 86000, null, 'Tunai'],
            ['2025-12-22', 'Wahyu', 22, 1, null, 12000, 12000, null, 'Tunai'],
            ['2025-12-22', 'Yasmin', 8, 2, 'L', 75000, 150000, 'Keju Bites', 'QRIS'],
            ['2025-12-22', 'Yasmin', 15, 2, null, 9000, 18000, null, 'QRIS'],
            
            // ==================== TANGGAL 23 DESEMBER 2025 ====================
            ['2025-12-23', 'Adi', 10, 1, null, 15000, 15000, null, 'Tunai'],
            ['2025-12-23', 'Bima', 8, 1, 'M', 41000, 41000, 'Keju Topping', 'QRIS'],
            ['2025-12-23', 'Bima', 18, 1, null, 5000, 5000, null, 'QRIS'],
            ['2025-12-23', 'Cindy', 22, 1, 'L', 79000, 79000, 'Sosis Bites', 'Tunai'],
            ['2025-12-23', 'Cindy', 20, 1, null, 6000, 6000, null, 'Tunai'],
            
            // ==================== TANGGAL 24 DESEMBER 2025 ====================
            ['2025-12-24', 'Doni', 12, 2, null, 10000, 20000, null, 'QRIS'],
            ['2025-12-24', 'Ela', 8, 1, 'S', 25000, 25000, null, 'Tunai'],
            ['2025-12-24', 'Fani', 17, 2, null, 4000, 8000, null, 'QRIS'],
            ['2025-12-24', 'Geri', 6, 1, 'M', 51000, 51000, 'Keju Bites', 'Tunai'],
            ['2025-12-24', 'Geri', 16, 1, null, 9000, 9000, null, 'QRIS'],
            ['2025-12-24', 'Hani', 8, 2, 'L', 75000, 150000, 'Keju Pinggir', 'Tunai'],
            ['2025-12-24', 'Hani', 18, 1, null, 5000, 5000, null, 'Tunai'],
            
            // ==================== TANGGAL 25 DESEMBER 2025 ====================
            ['2025-12-25', 'Iwan', 15, 2, null, 9000, 18000, null, 'QRIS'],
            ['2025-12-25', 'Jihan', 10, 1, null, 15000, 15000, 'Tambah Keju', 'Tunai'],
            ['2025-12-25', 'Kiki', 8, 1, 'M', 41000, 41000, null, 'QRIS'],
            ['2025-12-25', 'Kiki', 17, 1, null, 4000, 4000, null, 'QRIS'],
            ['2025-12-25', 'Lia', 14, 2, null, 12000, 24000, null, 'Tunai'],
            ['2025-12-25', 'Mika', 8, 1, 'L', 75000, 75000, null, 'Tunai'],
            ['2025-12-25', 'Niko', 11, 1, 'L', 95000, 95000, 'Keju Topping', 'QRIS'],
            ['2025-12-25', 'Niko', 21, 1, null, 12000, 12000, null, 'QRIS'],
            
            // ==================== TANGGAL 26 DESEMBER 2025 ====================
            ['2025-12-26', 'Oji', 12, 3, null, 10000, 30000, null, 'Tunai'],
            ['2025-12-26', 'Pandu', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-12-26', 'Qila', 8, 2, 'L', 75000, 150000, 'Sosis Bites', 'Tunai'],
            ['2025-12-26', 'Qila', 22, 1, null, 12000, 12000, null, 'Tunai'],
            ['2025-12-26', 'Rama', 1, 1, 'S', 25000, 25000, null, 'QRIS'],
            ['2025-12-26', 'Rama', 18, 1, null, 5000, 5000, null, 'QRIS'],
            
            // ==================== TANGGAL 27 DESEMBER 2025 ====================
            ['2025-12-27', 'Sinta', 8, 1, 'M', 41000, 41000, null, 'Tunai'],
            ['2025-12-27', 'Sinta', 15, 1, null, 9000, 9000, null, 'Tunai'],
            ['2025-12-27', 'Tama', 5, 1, 'S', 35000, 35000, 'Keju Bites', 'QRIS'],
            ['2025-12-27', 'Tama', 19, 1, null, 6000, 6000, null, 'QRIS'],
            ['2025-12-27', 'Uli', 10, 2, null, 15000, 30000, null, 'Tunai'],
            ['2025-12-27', 'Uli', 17, 2, null, 4000, 8000, null, 'Tunai'],
            
            // ==================== TANGGAL 28 DESEMBER 2025 ====================
            ['2025-12-28', 'Vero', 8, 1, 'L', 75000, 75000, 'Keju Pinggir', 'Tunai'],
            ['2025-12-28', 'Vero', 21, 1, null, 12000, 12000, null, 'Tunai'],
            ['2025-12-28', 'Wandi', 13, 1, null, 18000, 18000, null, 'QRIS'],
            ['2025-12-28', 'Yanti', 17, 2, null, 4000, 8000, null, 'QRIS'],
            
            // ==================== TANGGAL 29 DESEMBER 2025 ====================
            ['2025-12-29', 'Yanti', 4, 2, 'M', 30000, 60000, null, 'Tunai'],
            ['2025-12-29', 'Yanti', 16, 2, null, 9000, 18000, null, 'Tunai'],
            ['2025-12-29', 'Zidan', 8, 2, 'L', 75000, 150000, 'Keju Topping', 'QRIS'],
            ['2025-12-29', 'Zidan', 18, 2, null, 5000, 10000, null, 'QRIS'],
            ['2025-12-29', 'Andi', 10, 2, null, 15000, 30000, 'Tambah Keju', 'Tunai'],
            ['2025-12-29', 'Andi', 20, 1, null, 6000, 6000, null, 'Tunai'],
            
            // ==================== TANGGAL 30 DESEMBER 2025 ====================
            ['2025-12-30', 'Bobi', 8, 1, 'S', 25000, 25000, 'Sosis Bites', 'QRIS'],
            ['2025-12-30', 'Bobi', 22, 1, null, 12000, 12000, null, 'QRIS'],
            ['2025-12-30', 'Cici', 8, 2, 'M', 41000, 82000, null, 'Tunai'],
            ['2025-12-30', 'Cici', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-12-30', 'Didi', 7, 1, 'M', 51000, 51000, 'Keju Bites', 'QRIS'],
            ['2025-12-30', 'Didi', 21, 2, null, 12000, 24000, null, 'QRIS'],
            
            // ==================== TANGGAL 31 DESEMBER 2025 ====================
            ['2025-12-31', 'Eka', 8, 1, 'L', 75000, 75000, null, 'Tunai'],
            ['2025-12-31', 'Eka', 15, 2, null, 9000, 18000, null, 'Tunai'],
            ['2025-12-31', 'Fina', 5, 1, 'M', 61000, 61000, 'Keju Pinggir', 'QRIS'],
            ['2025-12-31', 'Fina', 17, 2, null, 4000, 8000, null, 'QRIS'],
            ['2025-12-31', 'Gani', 10, 2, null, 15000, 30000, null, 'Tunai'],
            ['2025-12-31', 'Gani', 18, 1, null, 5000, 5000, null, 'Tunai'],
            ['2025-12-31', 'Hera', 8, 1, 'L', 75000, 75000, 'Sosis Bites', 'QRIS'],
            ['2025-12-31', 'Hera', 19, 1, null, 6000, 6000, null, 'QRIS'],
        ];
    }
}