<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResepMenu;
use App\Models\Menu;
use App\Models\BahanBaku;

class ResepMenuSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua menu dan bahan baku
        $menus = Menu::all()->keyBy('nama_menu');
        $bahan = BahanBaku::all()->keyBy('nama_bahan');

        // Data resep: [nama_menu, ukuran, nama_bahan, jumlah, satuan]
        $resepData = [
            // ==================== PIZZA (ID 1-9) ====================
            // 1. Spicy Chicken Mushroom
            ['Spicy Chicken Mushroom', null, 'Tepung Terigu', 200, 'gram'],
            ['Spicy Chicken Mushroom', null, 'Keju Mozarella', 50, 'gram'],
            ['Spicy Chicken Mushroom', null, 'Saus Tomat', 30, 'ml'],
            ['Spicy Chicken Mushroom', null, 'Ayam Suwir', 40, 'gram'],
            ['Spicy Chicken Mushroom', null, 'Jamur', 20, 'gram'],
            ['Spicy Chicken Mushroom', null, 'Cabai Bubuk', 5, 'gram'],

            // 2. Pepperoni Mushroom
            ['Pepperoni Mushroom', null, 'Tepung Terigu', 200, 'gram'],
            ['Pepperoni Mushroom', null, 'Keju Mozarella', 50, 'gram'],
            ['Pepperoni Mushroom', null, 'Saus Tomat', 30, 'ml'],
            ['Pepperoni Mushroom', null, 'Pepperoni', 40, 'gram'],
            ['Pepperoni Mushroom', null, 'Jamur', 20, 'gram'],

            // 3. Meat Lover
            ['Meat Lover', null, 'Tepung Terigu', 200, 'gram'],
            ['Meat Lover', null, 'Keju Mozarella', 50, 'gram'],
            ['Meat Lover', null, 'Saus Tomat', 30, 'ml'],
            ['Meat Lover', null, 'Daging Sapi Cincang', 30, 'gram'],
            ['Meat Lover', null, 'Sosis', 20, 'gram'],
            ['Meat Lover', null, 'Pepperoni', 20, 'gram'],

            // 4. Margerita
            ['Margerita', null, 'Tepung Terigu', 200, 'gram'],
            ['Margerita', null, 'Keju Mozarella', 55, 'gram'],
            ['Margerita', null, 'Saus Tomat', 30, 'ml'],

            // 5. Favorite Pizza
            ['Favorite Pizza', null, 'Tepung Terigu', 200, 'gram'],
            ['Favorite Pizza', null, 'Keju Mozarella', 55, 'gram'],
            ['Favorite Pizza', null, 'Saus Tomat', 30, 'ml'],
            ['Favorite Pizza', null, 'Sosis', 20, 'gram'],
            ['Favorite Pizza', null, 'Smoked Beef', 20, 'gram'],
            ['Favorite Pizza', null, 'Pepperoni', 20, 'gram'],

            // 6. Bratwurst Pizza
            ['Bratwurst Pizza', null, 'Tepung Terigu', 200, 'gram'],
            ['Bratwurst Pizza', null, 'Keju Mozarella', 50, 'gram'],
            ['Bratwurst Pizza', null, 'Saus Tomat', 30, 'ml'],
            ['Bratwurst Pizza', null, 'Bratwurst Sosis', 50, 'gram'],
            ['Bratwurst Pizza', null, 'Bawang Bombay', 15, 'gram'],
            ['Bratwurst Pizza', null, 'Paprika', 15, 'gram'],

            // 7. Beef Bolognese Pizza
            ['Beef Bolognese Pizza', null, 'Tepung Terigu', 200, 'gram'],
            ['Beef Bolognese Pizza', null, 'Keju Mozarella', 50, 'gram'],
            ['Beef Bolognese Pizza', null, 'Saus Bolognese', 40, 'ml'],
            ['Beef Bolognese Pizza', null, 'Daging Sapi Cincang', 30, 'gram'],
            ['Beef Bolognese Pizza', null, 'Jamur', 20, 'gram'],
            ['Beef Bolognese Pizza', null, 'Paprika', 15, 'gram'],

            // 8. Smoked Beef & Corn
            ['Smoked Beef & Corn', null, 'Tepung Terigu', 200, 'gram'],
            ['Smoked Beef & Corn', null, 'Keju Mozarella', 50, 'gram'],
            ['Smoked Beef & Corn', null, 'Saus Tomat', 30, 'ml'],
            ['Smoked Beef & Corn', null, 'Smoked Beef', 40, 'gram'],
            ['Smoked Beef & Corn', null, 'Jagung Manis', 20, 'gram'],

            // 9. Tuna Onion Pizza
            ['Tuna Onion Pizza', null, 'Tepung Terigu', 200, 'gram'],
            ['Tuna Onion Pizza', null, 'Keju Mozarella', 50, 'gram'],
            ['Tuna Onion Pizza', null, 'Saus Tomat', 30, 'ml'],
            ['Tuna Onion Pizza', null, 'Tuna Kalengan', 40, 'gram'],
            ['Tuna Onion Pizza', null, 'Bawang Bombay', 15, 'gram'],

            // ==================== BURGER (ID 10-11) ====================
            // 10. Chicken Burger
            ['Chicken Burger', null, 'Roti Burger', 1, 'buah'],
            ['Chicken Burger', null, 'Dada Ayam Fillet', 150, 'gram'],
            ['Chicken Burger', null, 'Selada', 10, 'gram'],
            ['Chicken Burger', null, 'Tomat', 2, 'iris'],
            ['Chicken Burger', null, 'Mayones', 15, 'gram'],
            ['Chicken Burger', null, 'Saus Sambal', 10, 'gram'],

            // 11. Beef Burger
            ['Beef Burger', null, 'Roti Burger', 1, 'buah'],
            ['Beef Burger', null, 'Daging Sapi Giling', 150, 'gram'],
            ['Beef Burger', null, 'Selada', 10, 'gram'],
            ['Beef Burger', null, 'Tomat', 2, 'iris'],
            ['Beef Burger', null, 'Keju Slice', 1, 'lembar'],
            ['Beef Burger', null, 'Mayones', 15, 'gram'],
            ['Beef Burger', null, 'Saus Sambal', 10, 'gram'],

            // ==================== OTHERS (ID 12-15) ====================
            // 12. Donut / Bomboloni
            ['Donut / Bomboloni', null, 'Tepung Terigu', 100, 'gram'],
            ['Donut / Bomboloni', null, 'Telur', 0.5, 'butir'],
            ['Donut / Bomboloni', null, 'Gula', 15, 'gram'],
            ['Donut / Bomboloni', null, 'Mentega', 10, 'gram'],
            ['Donut / Bomboloni', null, 'Minyak Goreng', 30, 'ml'],

            // 13. Spaghetti Bolognese
            ['Spaghetti Bolognese', null, 'Spaghetti', 100, 'gram'],
            ['Spaghetti Bolognese', null, 'Saus Bolognese', 50, 'ml'],
            ['Spaghetti Bolognese', null, 'Daging Sapi Cincang', 30, 'gram'],
            ['Spaghetti Bolognese', null, 'Keju Parmesan', 10, 'gram'],

            // 14. Mozarella Stick
            ['Mozarella Stick', null, 'Keju Mozarella', 100, 'gram'],
            ['Mozarella Stick', null, 'Tepung Roti', 50, 'gram'],
            ['Mozarella Stick', null, 'Telur', 1, 'butir'],
            ['Mozarella Stick', null, 'Minyak Goreng', 30, 'ml'],

            // 15. French Fries
            ['French Fries', null, 'Kentang', 200, 'gram'],
            ['French Fries', null, 'Minyak Goreng', 50, 'ml'],
            ['French Fries', null, 'Garam', 2, 'gram'],

            // ==================== BEVERAGE (ID 16-21) ====================
            // 16. Sundae Ice Cream
            ['Sundae Ice Cream', null, 'Es Krim Vanilla', 1, 'scoop'],
            ['Sundae Ice Cream', null, 'Saus Coklat', 15, 'ml'],
            ['Sundae Ice Cream', null, 'Sprinkles', 5, 'gram'],

            // 17. Teh / Es Teh
            ['Teh / Es Teh', null, 'Teh Celup', 1, 'sachet'],
            ['Teh / Es Teh', null, 'Gula', 15, 'gram'],
            ['Teh / Es Teh', null, 'Air', 200, 'ml'],
            ['Teh / Es Teh', null, 'Es Batu', 100, 'gram'],

            // 18. Jeruk / Es Jeruk
            ['Jeruk / Es Jeruk', null, 'Sirup Jeruk', 30, 'ml'],
            ['Jeruk / Es Jeruk', null, 'Air', 200, 'ml'],
            ['Jeruk / Es Jeruk', null, 'Es Batu', 100, 'gram'],

            // 19. Cappucino
            ['Cappucino', null, 'Kopi Bubuk', 10, 'gram'],
            ['Cappucino', null, 'Susu UHT', 50, 'ml'],
            ['Cappucino', null, 'Air', 150, 'ml'],
            ['Cappucino', null, 'Gula', 10, 'gram'],

            // 20. Choco Blend
            ['Choco Blend', null, 'Susu UHT', 100, 'ml'],
            ['Choco Blend', null, 'Choco Blend Bubuk', 50, 'gram'],
            ['Choco Blend', null, 'Es Batu', 50, 'gram'],

            // 21. Strawberry Blend
            ['Strawberry Blend', null, 'Susu UHT', 100, 'ml'],
            ['Strawberry Blend', null, 'Strawberry Sirup', 30, 'ml'],
            ['Strawberry Blend', null, 'Es Batu', 50, 'gram'],

            // 22. Completo (Extra)
            ['Completo', null, 'Tepung Terigu', 200, 'gram'],
            ['Completo', null, 'Keju Mozarella', 55, 'gram'],
            ['Completo', null, 'Saus Tomat', 30, 'ml'],
            ['Completo', null, 'Sosis', 20, 'gram'],
            ['Completo', null, 'Smoked Beef', 20, 'gram'],
            ['Completo', null, 'Pepperoni', 20, 'gram'],
            ['Completo', null, 'Jamur', 20, 'gram'],
            ['Completo', null, 'Paprika', 15, 'gram'],
        ];

        foreach ($resepData as $data) {
            $menu = $menus[$data[0]] ?? null;
            $bahanItem = $bahan[$data[2]] ?? null;

            if ($menu && $bahanItem) {
                ResepMenu::create([
                    'id_menu' => $menu->id_menu,
                    'id_bahan' => $bahanItem->id_bahan,
                    'ukuran' => $data[1],
                    'jumlah' => $data[3],
                    'satuan' => $data[4],
                ]);
            }
        }

        $this->command->info('✅ Resep menu berhasil diisi!');
        $this->command->info('📊 Total resep: ' . count($resepData) . ' bahan');
    }
}