<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataMei2026Seeder extends Seeder
{
    public function run()
    {
        $idKasir = 2;
        
        $this->command->info('Menggunakan ID Kasir: ' . $idKasir);
        
        $data = $this->getMeiData();
        $this->processData($data, $idKasir);
        
        $this->command->info(' Data Mei 2026 berhasil diimport!');
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
    
    
    private function getMeiData()
    {
        return [
            ['2026-05-01', 'Rian', 8, 2, 'L', 75000, 150000, 'Keju Pinggir', 'Tunai'],
            ['2026-05-01', 'Rian', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2026-05-01', 'Sari', 10, 1, null, 15000, 15000, null, 'QRIS'],
            ['2026-05-01', 'Dodi', 15, 2, null, 9000, 18000, null, 'QRIS'],
            ['2026-05-01', 'Maya', 8, 1, 'M', 41000, 41000, 'Keju Topping', 'Tunai'],
            ['2026-05-01', 'Maya', 18, 1, null, 5000, 5000, null, 'Tunai'],
            ['2026-05-01', 'Bagas', 22, 1, 'L', 79000, 79000, 'Sosis Bites', 'QRIS'],
            ['2026-05-01', 'Bagas', 20, 1, null, 6000, 6000, null, 'QRIS'],
            ['2026-05-02', 'Tia', 12, 2, null, 10000, 20000, null, 'Tunai'],
            ['2026-05-02', 'Raka', 8, 1, 'S', 25000, 25000, null, 'QRIS'],
            ['2026-05-02', 'Lina', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2026-05-02', 'Gilang', 6, 1, 'M', 51000, 51000, 'Keju Bites', 'Tunai'],
            ['2026-05-02', 'Dina', 16, 1, null, 9000, 9000, null, 'QRIS'],
            ['2026-05-03', 'Bayu', 8, 2, 'L', 75000, 150000, 'Keju Pinggir', 'Tunai'],
            ['2026-05-03', 'Bayu', 18, 1, null, 5000, 5000, null, 'Tunai'],
            ['2026-05-03', 'Maya', 15, 2, null, 9000, 18000, null, 'QRIS'],
            ['2026-05-04', 'Rizki', 10, 1, null, 15000, 15000, 'Tambah Keju', 'Tunai'],
            ['2026-05-04', 'Wulan', 8, 1, 'M', 41000, 41000, null, 'QRIS'],
            ['2026-05-04', 'Wulan', 17, 1, null, 4000, 4000, null, 'QRIS'],
            ['2026-05-04', 'Beni', 14, 2, null, 12000, 24000, null, 'Tunai'],
            ['2026-05-05', 'Vina', 8, 1, 'L', 75000, 75000, null, 'Tunai'],
            ['2026-05-05', 'Eka', 11, 1, 'L', 95000, 95000, 'Keju Topping', 'QRIS'],
            ['2026-05-05', 'Eka', 21, 1, null, 12000, 12000, null, 'QRIS'],
            ['2026-05-06', 'Yoga', 12, 3, null, 10000, 30000, null, 'Tunai'],
            ['2026-05-06', 'Rina', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2026-05-06', 'Ari', 8, 2, 'L', 75000, 150000, 'Sosis Bites', 'Tunai'],
            ['2026-05-06', 'Ari', 22, 1, null, 12000, 12000, null, 'Tunai'],
            ['2026-05-07', 'Nia', 1, 1, 'S', 25000, 25000, null, 'QRIS'],
            ['2026-05-07', 'Nia', 18, 1, null, 5000, 5000, null, 'QRIS'],
            ['2026-05-07', 'Danu', 8, 1, 'M', 41000, 41000, null, 'Tunai'],
            ['2026-05-07', 'Danu', 15, 1, null, 9000, 9000, null, 'Tunai'],
            ['2026-05-08', 'Sari', 5, 1, 'S', 35000, 35000, 'Keju Bites', 'QRIS'],
            ['2026-05-08', 'Sari', 19, 1, null, 6000, 6000, null, 'QRIS'],
            ['2026-05-08', 'Bagas', 10, 2, null, 15000, 30000, null, 'Tunai'],
            ['2026-05-08', 'Bagas', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2026-05-09', 'Putri', 8, 1, 'L', 75000, 75000, 'Keju Pinggir', 'Tunai'],
            ['2026-05-09', 'Putri', 21, 1, null, 12000, 12000, null, 'Tunai'],
            ['2026-05-09', 'Irfan', 13, 1, null, 18000, 18000, null, 'QRIS'],
            ['2026-05-10', 'Irfan', 17, 2, null, 4000, 8000, null, 'QRIS'],
            ['2026-05-10', 'Wulan', 4, 2, 'M', 30000, 60000, null, 'Tunai'],
            ['2026-05-10', 'Wulan', 16, 2, null, 9000, 18000, null, 'Tunai'],
            ['2026-05-10', 'Citra', 8, 2, 'L', 75000, 150000, 'Keju Topping', 'QRIS'],
            ['2026-05-10', 'Citra', 18, 2, null, 5000, 10000, null, 'QRIS'],
            ['2026-05-11', 'Dedi', 10, 2, null, 15000, 30000, 'Tambah Keju', 'Tunai'],
            ['2026-05-11', 'Dedi', 20, 1, null, 6000, 6000, null, 'Tunai'],
            ['2026-05-11', 'Ana', 8, 1, 'S', 25000, 25000, 'Sosis Bites', 'QRIS'],
            ['2026-05-11', 'Ana', 22, 1, null, 12000, 12000, null, 'QRIS'],
            ['2026-05-12', 'Budi', 8, 2, 'M', 41000, 82000, null, 'Tunai'],
            ['2026-05-12', 'Budi', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2026-05-12', 'Leni', 7, 1, 'M', 51000, 51000, 'Keju Bites', 'QRIS'],
            ['2026-05-12', 'Leni', 21, 2, null, 12000, 24000, null, 'QRIS'],
            ['2026-05-13', 'Toni', 8, 1, 'L', 75000, 75000, null, 'Tunai'],
            ['2026-05-13', 'Toni', 15, 2, null, 9000, 18000, null, 'Tunai'],
            ['2026-05-13', 'Uli', 5, 1, 'M', 61000, 61000, 'Keju Pinggir', 'QRIS'],
            ['2026-05-13', 'Uli', 17, 2, null, 4000, 8000, null, 'QRIS'],
            ['2026-05-14', 'Adit', 10, 2, null, 15000, 30000, null, 'Tunai'],
            ['2026-05-14', 'Adit', 18, 1, null, 5000, 5000, null, 'Tunai'],
            ['2026-05-14', 'Dita', 8, 1, 'L', 75000, 75000, 'Sosis Bites', 'QRIS'],
            ['2026-05-14', 'Dita', 19, 1, null, 6000, 6000, null, 'QRIS'],
            ['2026-05-15', 'Heri', 12, 2, null, 10000, 20000, null, 'Tunai'],
            ['2026-05-15', 'Ria', 1, 1, 'M', 42000, 42000, 'Keju Topping', 'QRIS'],
            ['2026-05-15', 'Ria', 17, 1, null, 4000, 4000, null, 'QRIS'],
            ['2026-05-15', 'Ardi', 8, 2, 'L', 75000, 150000, null, 'Tunai'],
            ['2026-05-16', 'Ardi', 22, 1, null, 12000, 12000, null, 'Tunai'],
            ['2026-05-16', 'Fina', 4, 2, 'S', 19000, 38000, null, 'QRIS'],
            ['2026-05-16', 'Fina', 17, 2, null, 4000, 8000, null, 'QRIS'],
            ['2026-05-16', 'Galih', 8, 1, 'M', 41000, 41000, 'Keju Bites', 'Tunai'],
            ['2026-05-16', 'Galih', 20, 1, null, 6000, 6000, null, 'QRIS'],
            ['2026-05-17', 'Ovi', 15, 2, null, 9000, 18000, null, 'QRIS'],
            ['2026-05-17', 'Bima', 1, 1, 'L', 71000, 71000, 'Keju Pinggir', 'Tunai'],
            ['2026-05-17', 'Bima', 16, 1, null, 9000, 9000, null, 'QRIS'],
            ['2026-05-17', 'Nisa', 8, 2, 'M', 41000, 82000, null, 'Tunai'],
            ['2026-05-18', 'Nisa', 21, 1, null, 12000, 12000, null, 'QRIS'],
            ['2026-05-18', 'Roni', 6, 1, 'M', 51000, 51000, 'Sosis Bites', 'Tunai'],
            ['2026-05-18', 'Roni', 18, 1, null, 5000, 5000, null, 'QRIS'],
            ['2026-05-18', 'Tia', 8, 1, 'L', 75000, 75000, 'Keju Topping', 'Tunai'],
            ['2026-05-19', 'Luki', 10, 1, null, 15000, 15000, null, 'QRIS'],
            ['2026-05-19', 'Luki', 18, 1, null, 5000, 5000, null, 'QRIS'],
            ['2026-05-19', 'Citra', 22, 2, 'M', 47000, 94000, 'Keju Topping', 'Tunai'],
            ['2026-05-19', 'Citra', 22, 2, null, 12000, 24000, null, 'Tunai'],
            ['2026-05-20', 'Edo', 8, 1, 'L', 75000, 75000, null, 'QRIS'],
            ['2026-05-20', 'Edo', 15, 2, null, 9000, 18000, null, 'QRIS'],
            ['2026-05-20', 'Siska', 2, 1, 'M', 38000, 38000, 'Keju Pinggir', 'Tunai'],
            ['2026-05-20', 'Siska', 19, 1, null, 6000, 6000, null, 'Tunai'],
            ['2026-05-21', 'Doni', 10, 3, null, 15000, 45000, null, 'QRIS'],
            ['2026-05-21', 'Doni', 17, 3, null, 4000, 12000, null, 'QRIS'],
            ['2026-05-21', 'Vira', 8, 2, 'L', 75000, 150000, 'Sosis Bites', 'Tunai'],
            ['2026-05-21', 'Vira', 21, 2, null, 12000, 24000, null, 'Tunai'],
            ['2026-05-22', 'Alfi', 4, 1, 'M', 30000, 30000, null, 'QRIS'],
            ['2026-05-22', 'Alfi', 18, 2, null, 5000, 10000, null, 'QRIS'],
            ['2026-05-22', 'Bima', 1, 2, 'S', 25000, 50000, 'Keju Topping', 'Tunai'],
            ['2026-05-22', 'Bima', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2026-05-22', 'Caca', 14, 2, null, 12000, 24000, null, 'QRIS'],
            ['2026-05-22', 'Dani', 8, 1, 'S', 25000, 25000, null, 'Tunai'],
            ['2026-05-22', 'Eka', 13, 1, null, 18000, 18000, null, 'QRIS'],
            ['2026-05-22', 'Eka', 20, 1, null, 6000, 6000, null, 'QRIS'],
            ['2026-05-24', 'Gita', 12, 3, null, 10000, 30000, null, 'Tunai'],
            ['2026-05-24', 'Hadi', 8, 2, 'L', 75000, 150000, 'Keju Bites', 'QRIS'],
            ['2026-05-24', 'Hadi', 22, 1, null, 12000, 12000, null, 'QRIS'],
            ['2026-05-25', 'Ica', 8, 1, 'M', 41000, 41000, null, 'Tunai'],
            ['2026-05-25', 'Ica', 17, 1, null, 4000, 4000, null, 'Tunai'],
            ['2026-05-25', 'Jaka', 10, 2, null, 15000, 30000, 'Tambah Keju', 'QRIS'],
            ['2026-05-25', 'Jaka', 15, 1, null, 9000, 9000, null, 'QRIS'],
            ['2026-05-26', 'Kiki', 8, 1, 'L', 75000, 75000, 'Keju Pinggir', 'Tunai'],
            ['2026-05-26', 'Lala', 11, 1, 'S', 35000, 35000, null, 'QRIS'],
            ['2026-05-26', 'Lala', 18, 1, null, 5000, 5000, null, 'QRIS'],
            ['2026-05-27', 'Miko', 10, 2, null, 15000, 30000, null, 'Tunai'],
            ['2026-05-27', 'Nana', 8, 2, 'L', 75000, 150000, 'Sosis Bites', 'QRIS'],
            ['2026-05-27', 'Nana', 19, 2, null, 6000, 12000, null, 'QRIS'],
            ['2026-05-28', 'Oki', 9, 1, 'M', 43000, 43000, null, 'Tunai'],
            ['2026-05-28', 'Pita', 8, 2, 'L', 75000, 150000, 'Keju Topping', 'QRIS'],
            ['2026-05-28', 'Pita', 21, 2, null, 12000, 24000, null, 'QRIS'],
            ['2026-05-29', 'Rian', 4, 2, 'L', 53000, 106000, null, 'Tunai'],
            ['2026-05-29', 'Rian', 17, 2, null, 4000, 8000, null, 'Tunai'],
            ['2026-05-29', 'Sasa', 8, 1, 'L', 75000, 75000, 'Keju Pinggir', 'QRIS'],
            ['2026-05-29', 'Sasa', 16, 2, null, 9000, 18000, null, 'QRIS'],
            ['2026-05-30', 'Tata', 3, 2, 'M', 43000, 86000, null, 'Tunai'],
            ['2026-05-30', 'Tata', 22, 1, null, 12000, 12000, null, 'Tunai'],
            ['2026-05-30', 'Udin', 8, 2, 'L', 75000, 150000, 'Keju Bites', 'QRIS'],
            ['2026-05-30', 'Udin', 15, 2, null, 9000, 18000, null, 'QRIS'],
            ['2026-05-31', 'Wati', 5, 1, 'L', 95000, 95000, null, 'Tunai'],
            ['2026-05-31', 'Wati', 20, 1, null, 6000, 6000, null, 'Tunai'],
            ['2026-05-31', 'Xena', 8, 2, 'S', 25000, 50000, 'Sosis Bites', 'QRIS'],
            ['2026-05-31', 'Xena', 18, 1, null, 5000, 5000, null, 'QRIS'],
        ];
    }
}