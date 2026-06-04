<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class November2025Seeder extends Seeder
{
    public function run()
    {
        // Data transaksi November 2025
        $data = [
            // [tanggal, customer, menu, jumlah, ukuran, harga, total, topping, metode]
            ['2025-11-01', 'Andi', 'Spicy Chicken Mushroom', 4, 'L', 71000, 284000, 'Keju Pinggir', 'Tunai'],
            ['2025-11-01', 'Andi', 'Teh / Es Teh', 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-11-01', 'Sinta', 'Chicken Burger', 2, null, 15000, 30000, 'Tambah Keju', 'QRIS'],
            ['2025-11-01', 'Riki', 'French Fries', 1, null, 9000, 9000, null, 'QRIS'],
            ['2025-11-01', 'Dewi', 'Favorite Pizza', 1, 'L', 95000, 95000, 'Keju Topping', 'Tunai'],
            ['2025-11-01', 'Dewi', 'Jeruk / Es Jeruk', 1, null, 5000, 5000, null, 'Tunai'],
            ['2025-11-02', 'Bayu', 'Pepperoni Mushroom', 2, 'M', 38000, 76000, 'Sosis Bites', 'QRIS'],
            ['2025-11-02', 'Bayu', 'Lemon Tea', 2, null, 6000, 12000, null, 'QRIS'],
            ['2025-11-02', 'Tia', 'Donut / Bomboloni', 2, null, 10000, 20000, null, 'Tunai'],
            ['2025-11-02', 'Dodi', 'Beef Burger', 1, null, 16000, 16000, null, 'QRIS'],
            ['2025-11-03', 'Lina', 'Teh / Es Teh', 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-11-03', 'Raka', 'Meat Lover', 2, 'L', 75000, 150000, 'Keju Bites', 'Tunai'],
            ['2025-11-03', 'Dina', 'Sundae Ice Cream', 1, null, 9000, 9000, null, 'QRIS'],
            ['2025-11-04', 'Gilang', 'Bratwurst Pizza', 1, 'L', 86000, 86000, 'Keju Pinggir', 'Tunai'],
            ['2025-11-04', 'Gilang', 'Jeruk / Es Jeruk', 1, null, 5000, 5000, null, 'Tunai'],
            ['2025-11-04', 'Maya', 'French Fries', 2, null, 9000, 18000, null, 'QRIS'],
            ['2025-11-05', 'Raka', 'Chicken Burger', 2, null, 15000, 30000, 'Tambah Keju', 'Tunai'],
            ['2025-11-05', 'Tari', 'Spicy Chicken Mushroom', 1, 'M', 42000, 42000, null, 'QRIS'],
            ['2025-11-05', 'Tari', 'Teh / Es Teh', 1, null, 4000, 4000, null, 'QRIS'],
            ['2025-11-06', 'Beni', 'Beef Burger', 2, null, 16000, 32000, null, 'Tunai'],
            ['2025-11-06', 'Vina', 'Favorite Pizza', 1, 'M', 61000, 61000, null, 'Tunai'],
            ['2025-11-06', 'Eka', 'Smoked Beef & Corn', 1, 'L', 75000, 75000, 'Keju Topping', 'QRIS'],
            ['2025-11-06', 'Eka', 'Choco Blend', 1, null, 12000, 12000, null, 'QRIS'],
            ['2025-11-07', 'Yoga', 'Donut / Bomboloni', 3, null, 10000, 30000, null, 'Tunai'],
            ['2025-11-07', 'Rina', 'Teh / Es Teh', 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-11-07', 'Ari', 'Completo', 1, 'L', 79000, 79000, 'Sosis Bites', 'Tunai'],
            ['2025-11-07', 'Ari', 'Strawberry Blend', 1, null, 12000, 12000, null, 'Tunai'],
            ['2025-11-08', 'Nia', 'Chicken Burger', 1, null, 15000, 15000, null, 'QRIS'],
            ['2025-11-08', 'Nia', 'Jeruk / Es Jeruk', 1, null, 5000, 5000, null, 'QRIS'],
            ['2025-11-08', 'Danu', 'Pepperoni Mushroom', 2, 'S', 23000, 46000, null, 'Tunai'],
            ['2025-11-08', 'Danu', 'French Fries', 1, null, 9000, 9000, null, 'Tunai'],
            ['2025-11-09', 'Sari', 'Meat Lover', 1, 'M', 43000, 43000, 'Keju Bites', 'QRIS'],
            ['2025-11-09', 'Sari', 'Cappucino', 1, null, 6000, 6000, null, 'QRIS'],
            ['2025-11-09', 'Bagas', 'Beef Burger', 2, null, 16000, 32000, null, 'Tunai'],
            ['2025-11-09', 'Bagas', 'Teh / Es Teh', 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-11-10', 'Putri', 'Bratwurst Pizza', 1, 'L', 86000, 86000, 'Keju Pinggir', 'Tunai'],
            ['2025-11-10', 'Putri', 'Choco Blend', 1, null, 12000, 12000, null, 'Tunai'],
            ['2025-11-10', 'Irfan', 'Spaghetti Bolognese', 1, null, 18000, 18000, null, 'QRIS'],
            ['2025-11-10', 'Irfan', 'Teh / Es Teh', 2, null, 4000, 8000, null, 'QRIS'],
            ['2025-11-11', 'Wulan', 'Favorite Pizza', 2, 'L', 95000, 190000, 'Sosis Bites', 'Tunai'],
            ['2025-11-11', 'Wulan', 'Sundae Ice Cream', 2, null, 9000, 18000, null, 'Tunai'],
            ['2025-11-11', 'Rizki', 'Spicy Chicken Mushroom', 1, 'M', 42000, 42000, 'Keju Topping', 'QRIS'],
            ['2025-11-11', 'Rizki', 'Jeruk / Es Jeruk', 2, null, 5000, 10000, null, 'QRIS'],
            ['2025-11-12', 'Citra', 'Chicken Burger', 2, null, 15000, 30000, 'Tambah Keju', 'Tunai'],
            ['2025-11-12', 'Citra', 'Lemon Tea', 1, null, 6000, 6000, null, 'Tunai'],
            ['2025-11-12', 'Dedi', 'Beef Bolognese Pizza', 1, 'L', 80000, 80000, 'Sosis Bites', 'QRIS'],
            ['2025-11-12', 'Dedi', 'Strawberry Blend', 1, null, 12000, 12000, null, 'QRIS'],
            ['2025-11-13', 'Ana', 'Smoked Beef & Corn', 1, 'S', 25000, 25000, null, 'Tunai'],
            ['2025-11-13', 'Ana', 'Teh / Es Teh', 1, null, 4000, 4000, null, 'Tunai'],
            ['2025-11-13', 'Budi', 'Pepperoni Mushroom', 2, 'M', 38000, 76000, 'Keju Bites', 'QRIS'],
            ['2025-11-13', 'Budi', 'Choco Blend', 2, null, 12000, 24000, null, 'QRIS'],
            ['2025-11-14', 'Leni', 'Tuna Onion Pizza', 1, 'L', 76000, 76000, null, 'Tunai'],
            ['2025-11-14', 'Leni', 'French Fries', 2, null, 9000, 18000, null, 'Tunai'],
            ['2025-11-14', 'Toni', 'Meat Lover', 2, 'M', 43000, 86000, 'Keju Pinggir', 'QRIS'],
            ['2025-11-14', 'Toni', 'Teh / Es Teh', 2, null, 4000, 8000, null, 'QRIS'],
            ['2025-11-15', 'Uli', 'Chicken Burger', 2, null, 15000, 30000, null, 'Tunai'],
            ['2025-11-15', 'Uli', 'Jeruk / Es Jeruk', 1, null, 5000, 5000, null, 'Tunai'],
            ['2025-11-15', 'Adit', 'Favorite Pizza', 1, 'L', 95000, 95000, 'Sosis Bites', 'QRIS'],
            ['2025-11-15', 'Adit', 'Cappucino', 1, null, 6000, 6000, null, 'QRIS'],
            ['2025-11-16', 'Dita', 'Donut / Bomboloni', 2, null, 10000, 20000, null, 'Tunai'],
            ['2025-11-16', 'Heri', 'Bratwurst Pizza', 1, 'M', 51000, 51000, 'Keju Topping', 'QRIS'],
            ['2025-11-16', 'Heri', 'Teh / Es Teh', 1, null, 4000, 4000, null, 'QRIS'],
            ['2025-11-17', 'Ria', 'Pepperoni Mushroom', 2, 'S', 23000, 46000, null, 'Tunai'],
            ['2025-11-17', 'Ria', 'Strawberry Blend', 1, null, 12000, 12000, null, 'Tunai'],
            ['2025-11-17', 'Ardi', 'Meat Lover', 1, 'L', 75000, 75000, null, 'QRIS'],
            ['2025-11-17', 'Ardi', 'Jeruk / Es Jeruk', 1, null, 5000, 5000, null, 'QRIS'],
            ['2025-11-18', 'Fina', 'Margarita', 2, 'S', 19000, 38000, null, 'Tunai'],
            ['2025-11-18', 'Fina', 'Teh / Es Teh', 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-11-18', 'Galih', 'French Fries', 2, null, 9000, 18000, null, 'QRIS'],
            ['2025-11-19', 'Ovi', 'Spicy Chicken Mushroom', 1, 'L', 71000, 71000, 'Keju Pinggir', 'Tunai'],
            ['2025-11-19', 'Ovi', 'Lemon Tea', 1, null, 6000, 6000, null, 'Tunai'],
            ['2025-11-20', 'Bima', 'Favorite Pizza', 1, 'S', 35000, 35000, null, 'QRIS'],
            ['2025-11-20', 'Bima', 'Sundae Ice Cream', 1, null, 9000, 9000, null, 'QRIS'],
            ['2025-11-20', 'Nisa', 'Bratwurst Pizza', 1, 'M', 51000, 51000, 'Sosis Bites', 'Tunai'],
            ['2025-11-20', 'Nisa', 'Choco Blend', 1, null, 12000, 12000, null, 'Tunai'],
            ['2025-11-20', 'Roni', 'Smoked Beef & Corn', 2, 'L', 75000, 150000, 'Keju Bites', 'QRIS'],
            ['2025-11-20', 'Roni', 'Teh / Es Teh', 2, null, 4000, 8000, null, 'QRIS'],
            ['2025-11-21', 'Tia', 'Paket Burger', 1, null, 22000, 22000, null, 'Tunai'],
            ['2025-11-21', 'Luki', 'Beef Bolognese Pizza', 1, 'S', 30000, 30000, null, 'QRIS'],
            ['2025-11-21', 'Luki', 'Jeruk / Es Jeruk', 1, null, 5000, 5000, null, 'QRIS'],
            ['2025-11-22', 'Citra', 'Completo', 2, 'M', 47000, 94000, 'Keju Topping', 'Tunai'],
            ['2025-11-22', 'Citra', 'Strawberry Blend', 2, null, 12000, 24000, null, 'Tunai'],
            ['2025-11-22', 'Edo', 'Tuna Onion Pizza', 1, 'L', 76000, 76000, null, 'QRIS'],
            ['2025-11-22', 'Edo', 'French Fries', 2, null, 9000, 18000, null, 'QRIS'],
            ['2025-11-23', 'Siska', 'Pepperoni Mushroom', 1, 'M', 38000, 38000, 'Keju Pinggir', 'Tunai'],
            ['2025-11-23', 'Siska', 'Cappucino', 1, null, 6000, 6000, null, 'Tunai'],
            ['2025-11-23', 'Doni', 'Chicken Burger', 3, null, 15000, 45000, null, 'QRIS'],
            ['2025-11-23', 'Doni', 'Teh / Es Teh', 2, null, 4000, 8000, null, 'QRIS'],
            ['2025-11-24', 'Vira', 'Favorite Pizza', 2, 'L', 95000, 190000, 'Sosis Bites', 'Tunai'],
            ['2025-11-24', 'Vira', 'Choco Blend', 2, null, 12000, 24000, null, 'Tunai'],
            ['2025-11-24', 'Alfi', 'Margarita', 1, 'M', 30000, 30000, null, 'QRIS'],
            ['2025-11-24', 'Alfi', 'Jeruk / Es Jeruk', 2, null, 5000, 10000, null, 'QRIS'],
            ['2025-11-25', 'Bima', 'Spicy Chicken Mushroom', 3, 'S', 25000, 75000, 'Keju Topping', 'Tunai'],
            ['2025-11-25', 'Bima', 'Teh / Es Teh', 2, null, 4000, 8000, null, 'Tunai'],
            ['2025-11-25', 'Caca', 'Mozarella Stick', 2, null, 12000, 24000, null, 'QRIS'],
            ['2025-11-25', 'Dani', 'Meat Lover', 1, 'S', 26000, 26000, null, 'Tunai'],
            ['2025-11-26', 'Eka', 'Spaghetti Bolognese', 2, null, 18000, 36000, null, 'QRIS'],
            ['2025-11-26', 'Eka', 'Lemon Tea', 1, null, 6000, 6000, null, 'QRIS'],
            ['2025-11-26', 'Gita', 'Donut / Bomboloni', 2, null, 10000, 20000, null, 'Tunai'],
            ['2025-11-27', 'Hadi', 'Bratwurst Pizza', 1, 'L', 86000, 86000, 'Keju Bites', 'QRIS'],
            ['2025-11-27', 'Hadi', 'Strawberry Blend', 1, null, 12000, 12000, null, 'QRIS'],
            ['2025-11-27', 'Ica', 'Smoked Beef & Corn', 1, 'M', 41000, 41000, null, 'Tunai'],
            ['2025-11-27', 'Ica', 'Teh / Es Teh', 1, null, 4000, 4000, null, 'Tunai'],
            ['2025-11-28', 'Jaka', 'Chicken Burger', 2, null, 15000, 30000, 'Tambah Keju', 'QRIS'],
            ['2025-11-28', 'Jaka', 'French Fries', 1, null, 9000, 9000, null, 'QRIS'],
            ['2025-11-28', 'Kiki', 'Favorite Pizza', 1, 'M', 61000, 61000, 'Keju Pinggir', 'Tunai'],
            ['2025-11-29', 'Lala', 'Kombinasi 2 Rasa', 1, 'S', 35000, 35000, null, 'QRIS'],
            ['2025-11-29', 'Lala', 'Jeruk / Es Jeruk', 1, null, 5000, 5000, null, 'QRIS'],
            ['2025-11-29', 'Miko', 'Paket Burger', 2, null, 22000, 44000, null, 'Tunai'],
            ['2025-11-30', 'Nana', 'Spicy Chicken Mushroom', 4, 'L', 71000, 284000, 'Sosis Bites', 'QRIS'],
            ['2025-11-30', 'Nana', 'Cappucino', 2, null, 6000, 12000, null, 'QRIS'],
            ['2025-11-30', 'Oki', 'Tuna Onion Pizza', 1, 'M', 43000, 43000, null, 'Tunai'],
            ['2025-11-30', 'Pita', 'Beef Bolognese Pizza', 2, 'L', 80000, 160000, 'Keju Topping', 'QRIS'],
            ['2025-11-30', 'Pita', 'Choco Blend', 2, null, 12000, 24000, null, 'QRIS'],
            ['2025-11-30', 'Rian', 'Margarita', 2, 'L', 53000, 106000, null, 'Tunai'],
            ['2025-11-30', 'Rian', 'Teh / Es Teh', 1, null, 4000, 4000, null, 'Tunai'],
            ['2025-11-30', 'Sasa', 'Pepperoni Mushroom', 1, 'L', 63000, 63000, 'Keju Pinggir', 'QRIS'],
            ['2025-11-30', 'Sasa', 'Sundae Ice Cream', 2, null, 9000, 18000, null, 'QRIS'],
            ['2025-11-30', 'Tata', 'Meat Lover', 2, 'M', 43000, 86000, null, 'Tunai'],
            ['2025-11-30', 'Tata', 'Strawberry Blend', 5, null, 12000, 60000, null, 'Tunai'],
            ['2025-11-30', 'Udin', 'Completo', 3, 'L', 79000, 237000, 'Keju Bites', 'QRIS'],
            ['2025-11-30', 'Udin', 'French Fries', 2, null, 9000, 18000, null, 'QRIS'],
            ['2025-11-30', 'Wati', 'Favorite Pizza', 1, 'L', 95000, 95000, null, 'Tunai'],
            ['2025-11-30', 'Wati', 'Lemon Tea', 1, null, 6000, 6000, null, 'Tunai'],
            ['2025-11-30', 'Xena', 'Bratwurst Pizza', 2, 'S', 30000, 60000, 'Sosis Bites', 'QRIS'],
            ['2025-11-30', 'Xena', 'Jeruk / Es Jeruk', 1, null, 5000, 5000, null, 'QRIS'],
            ['2025-11-30', 'Yogi', 'Donut / Bomboloni', 3, null, 10000, 30000, null, 'Tunai'],
            ['2025-11-30', 'Zaki', 'Spicy Chicken Mushroom', 1, 'M', 42000, 42000, null, 'QRIS'],
            ['2025-11-30', 'Zaki', 'Teh / Es Teh', 2, null, 4000, 8000, null, 'QRIS'],
            ['2025-11-30', 'Alya', 'Chicken Burger', 1, null, 15000, 15000, 'Tambah Keju', 'Tunai'],
            ['2025-11-30', 'Bani', 'French Fries', 2, null, 9000, 18000, null, 'QRIS'],
            ['2025-11-30', 'Ciko', 'Spicy Chicken Mushroom', 2, 'L', 71000, 142000, 'Keju Pinggir', 'Tunai'],
            ['2025-11-30', 'Dara', 'Favorite Pizza', 1, 'M', 61000, 61000, null, 'QRIS'],
            ['2025-11-30', 'Eri', 'Pepperoni Mushroom', 1, 'L', 63000, 63000, 'Mushroom', 'Tunai'],
            ['2025-11-30', 'Fani', 'Meat Lover', 1, 'L', 75000, 75000, null, 'QRIS'],
            ['2025-11-30', 'Gani', 'Teh / Es Teh', 1, null, 4000, 4000, null, 'Tunai'],
            ['2025-11-30', 'Hani', 'Donut / Bomboloni', 2, null, 10000, 20000, null, 'QRIS'],
            ['2025-11-30', 'Ica', 'Choco Blend', 1, null, 12000, 12000, null, 'Tunai'],
            ['2025-11-30', 'Joni', 'Jeruk / Es Jeruk', 2, null, 5000, 10000, null, 'QRIS'],
            ['2025-11-30', 'Kiki', 'Sundae Ice Cream', 1, null, 9000, 9000, null, 'Tunai'],
            ['2025-11-30', 'Lia', 'Spaghetti Bolognese', 1, null, 18000, 18000, null, 'QRIS'],
            ['2025-11-30', 'Manda', 'Mozarella Stick', 2, null, 12000, 24000, null, 'Tunai'],
            ['2025-11-30', 'Nino', 'Paket Burger', 3, null, 22000, 66000, 'Tambah Keju', 'QRIS'],
            ['2025-11-30', 'Oca', 'Beef Burger', 1, null, 16000, 16000, null, 'Tunai'],
            ['2025-11-30', 'Putu', 'Chicken Burger', 2, null, 15000, 30000, null, 'QRIS'],
            ['2025-11-30', 'Qori', 'French Fries', 2, null, 9000, 18000, null, 'Tunai'],
            ['2025-11-30', 'Rian', 'Teh / Es Teh', 1, null, 4000, 4000, null, 'QRIS'],
            ['2025-11-30', 'Sari', 'Spicy Chicken Mushroom', 1, 'M', 42000, 42000, 'Keju Topping', 'Tunai'],
            ['2025-11-30', 'Tono', 'Favorite Pizza', 1, 'L', 95000, 95000, null, 'QRIS'],
            ['2025-11-30', 'Uci', 'Bratwurst Pizza', 1, 'M', 51000, 51000, 'Sosis Bites', 'Tunai'],
        ];

        // Mapping menu ke ID (sesuai dengan MenuSeeder Anda)
        $menuId = [
            'Spicy Chicken Mushroom' => 1,
            'Pepperoni Mushroom' => 2,
            'Meat Lover' => 3,
            'Margarita' => 4,
            'Favorite Pizza' => 5,
            'Bratwurst Pizza' => 6,
            'Beef Bolognese Pizza' => 7,
            'Smoked Beef & Corn' => 8,
            'Tuna Onion Pizza' => 9,
            'Chicken Burger' => 10,
            'Beef Burger' => 11,
            'Donut / Bomboloni' => 12,
            'Spaghetti Bolognese' => 13,
            'Mozarella Stick' => 14,
            'French Fries' => 15,
            'Sundae Ice Cream' => 16,
            'Teh / Es Teh' => 17,
            'Jeruk / Es Jeruk' => 18,
            'Cappucino' => 19,
            'Choco Blend' => 20,
            'Strawberry Blend' => 21,
            'Completo' => 22,
            'Paket Burger' => 23,
            'Kombinasi 2 Rasa' => 24,
            'Lemon Tea' => 25,
        ];

        // Mapping topping ke ID (sesuaikan dengan database Anda)
        $toppingId = [
            'Keju Topping' => 1,
            'Keju Pinggir' => 2,
            'Keju Bites' => 3,
            'Sosis Bites' => 4,
            'Mushroom' => 5,
            'Tambah Keju' => 6,
        ];

        $counter = 0;
        $currentDate = '';
        $invoicePerDate = [];

        foreach ($data as $row) {
            $tanggal = $row[0];
            $customer = $row[1];
            $menuName = $row[2];
            $jumlah = $row[3];
            $ukuran = $row[4];
            $harga = $row[5];
            $total = $row[6];
            $topping = $row[7];
            $metode = $row[8];

            // Generate no_invoice per tanggal
            if ($currentDate != $tanggal) {
                $currentDate = $tanggal;
                $invoicePerDate[$tanggal] = 1;
            }

            $noInv = 'INV-' . date('Ymd', strtotime($tanggal)) . '-' . str_pad($invoicePerDate[$tanggal], 4, '0', STR_PAD_LEFT);
            $invoicePerDate[$tanggal]++;

            // Insert ke tabel pesanan
            $idPesanan = DB::table('pesanan')->insertGetId([
                'no_invoice' => $noInv,
                'tanggal' => $tanggal . ' ' . rand(10, 21) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) . ':00',
                'id_kasir' => 2,
                'nama_customer' => $customer,
                'no_meja' => rand(0, 8),
                'total_bayar' => $total,
                'metode_bayar' => $metode,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert ke tabel detail_pesanan (gunakan 'jumlah' sesuai struktur database Anda)
            $idDetail = DB::table('detail_pesanan')->insertGetId([
                'id_pesanan' => $idPesanan,
                'id_menu' => $menuId[$menuName],
                'ukuran' => $ukuran,
                'jumlah' => $jumlah,
                'harga_satuan' => $harga,
                'subtotal' => $total,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert topping jika ada
            if ($topping != null && isset($toppingId[$topping])) {
                DB::table('detail_pesanan_topping')->insert([
                    'id_detail_pesanan' => $idDetail,
                    'id_topping' => $toppingId[$topping],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $counter++;
        }

        $this->command->info('Berhasil mengimpor ' . $counter . ' transaksi untuk bulan November 2025');
    }
}