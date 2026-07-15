<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ManajemenMenuController;
use App\Http\Controllers\Admin\RiwayatPesananController;
use App\Http\Controllers\Admin\RiwayatPrediksiController;
use App\Http\Controllers\Admin\BahanBakuController;
use App\Http\Controllers\Admin\ResepMenuController;
use App\Http\Controllers\Kasir\KasirController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Api\MenuApiController;
use App\Http\Controllers\Admin\ToppingController;
use App\Http\Controllers\Admin\KelolaAkunController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\SettingController;


// ==================== GUEST ROUTES ====================
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Home route
Route::get('/', [HomeController::class, 'index']);

// ==================== PUBLIC API ROUTES ====================
Route::get('/api/menu', [MenuApiController::class, 'menu']);
Route::get('/api/admin/menu/cek-dipesan/{id}', [ManajemenMenuController::class, 'cekDipesan']);
Route::get('/api/menu/category/{id}', [MenuApiController::class, 'menuByKategori']); 
Route::get('/api/kategori', [MenuApiController::class, 'kategori']);
 Route::get('/api/menu/{id}', [MenuApiController::class, 'show']);

// ==================== ADMIN ROUTES (WEB) ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/riwaycat-pesanan', [RiwayatPesananController::class, 'index'])->name('riwayat-pesanan');
    Route::get('/riwayat-prediksi', [RiwayatPrediksiController::class, 'index'])->name('riwayat-prediksi');
    Route::get('/manajemen-menu', [ManajemenMenuController::class, 'index'])->name('manajemen-menu');
    Route::get('/menu/create', [ManajemenMenuController::class, 'create'])->name('menu.create');
    Route::get('/menu/edit/{id}', [ManajemenMenuController::class, 'edit'])->name('menu.edit');
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
    Route::get('/topping', [ToppingController::class, 'index'])->name('topping');
    Route::post('/topping/store', [ToppingController::class, 'store'])->name('topping.store');
    Route::put('/topping/update/{id}', [ToppingController::class, 'update'])->name('topping.update');
    Route::delete('/topping/delete/{id}', [ToppingController::class, 'destroy'])->name('topping.delete');
    Route::get('/riwayat-pesanan/export/excel', [RiwayatPesananController::class, 'exportOrdersExcel']);
    Route::get('/riwayat-pesanan/export/pdf', [RiwayatPesananController::class, 'exportOrdersPDF']);
    Route::get('/kelola-akun', [KelolaAkunController::class, 'index'])->name('kelola-akun');

    
    // ==================== BAHAN BAKU ====================
Route::get('/bahan-baku', [BahanBakuController::class, 'index'])->name('bahan-baku');
Route::post('/bahan-baku/store', [BahanBakuController::class, 'store'])->name('bahan-baku.store');
Route::put('/bahan-baku/update/{id}', [BahanBakuController::class, 'update'])->name('bahan-baku.update');
Route::delete('/bahan-baku/delete/{id}', [BahanBakuController::class, 'destroy'])->name('bahan-baku.delete');
Route::post('/bahan-baku/tambah-stok/{id}', [BahanBakuController::class, 'tambahStok'])->name('bahan-baku.tambah-stok');
Route::post('/bahan-baku/kurangi-stok/{id}', [BahanBakuController::class, 'kurangiStok'])->name('bahan-baku.kurangi-stok');

// ==================== RESEP MENU ====================
Route::get('/resep-menu', [ResepMenuController::class, 'index'])->name('resep-menu');
Route::post('/resep-menu/store', [ResepMenuController::class, 'store'])->name('resep-menu.store');
Route::put('/resep-menu/update/{id}', [ResepMenuController::class, 'update'])->name('resep-menu.update');
Route::delete('/resep-menu/delete/{id}', [ResepMenuController::class, 'destroy'])->name('resep-menu.delete');
Route::post('/admin/resep-menu/store-bulk', [ResepMenuController::class, 'storeBulk'])->name('admin.resep-menu.store-bulk');
// Resep Menu
Route::get('/resep-menu', [ResepMenuController::class, 'index'])->name('resep-menu');
Route::get('/resep-menu/create', [ResepMenuController::class, 'create'])->name('resep-menu.create');
Route::get('/resep-menu/edit/{id}', [ResepMenuController::class, 'edit'])->name('resep-menu.edit');
Route::post('/resep-menu/store-bulk', [ResepMenuController::class, 'storeBulk'])->name('resep-menu.store-bulk');
Route::post('/resep-menu/update-bulk/{id}', [ResepMenuController::class, 'updateBulk'])->name('resep-menu.update-bulk');
Route::delete('/resep-menu/delete/{id}', [ResepMenuController::class, 'destroy'])->name('resep-menu.delete');
}); 

// ==================== KASIR ROUTES (WEB) ====================
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/menu-pesanan', [KasirController::class, 'menuPesanan'])->name('menu-pesanan');
    Route::post('/store-pesanan', [KasirController::class, 'storePesanan'])->name('store-pesanan');
    Route::get('/riwayat-pesanan', [KasirController::class, 'riwayatPesanan'])->name('riwayat-pesanan');
    Route::get('/harga-by-ukuran', [KasirController::class, 'getHargaByUkuran']);
    Route::get('/toppings', [KasirController::class, 'getToppings']);
    Route::post('/cetak-pdf', [KasirController::class, 'cetakPDF'])->name('cetak-pdf'); 
    
    // ============================================
    // ROUTE PREDIKSI FLEXIBLE (BARU)
    // ============================================
    Route::post('/prediksi/flexible', [RiwayatPrediksiController::class, 'lakukanPrediksiFlexible'])
        ->name('admin.prediksi.flexible');
    
    // Update Aktual (BARU)
    Route::post('/prediksi/update-aktual/{id}', [RiwayatPrediksiController::class, 'updateAktual'])
        ->name('admin.prediksi.update-aktual');
    
    Route::post('/prediksi/update-all-aktual', [RiwayatPrediksiController::class, 'updateAllAktual'])
        ->name('admin.prediksi.update-all-aktual');

});






// ==================== ADMIN API ROUTES ====================
Route::middleware(['auth', 'role:admin'])->prefix('api/admin')->group(function () {
    // Dashboard API
    Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);
    Route::get('/omzet-chart', [DashboardController::class, 'getOmzetChart']);
    Route::get('/payment-chart', [DashboardController::class, 'getPaymentChart']);
    Route::get('/top-menu', [DashboardController::class, 'getTopMenu']);
    Route::get('/recent-orders', [DashboardController::class, 'getRecentOrders']);
    Route::get('/filter-options', [DashboardController::class, 'getFilterOptions']);
    
    // Menu Management API
    Route::post('/menu', [ManajemenMenuController::class, 'storeMenu']);
    Route::put('/menu/{id}', [ManajemenMenuController::class, 'updateMenu']);
    Route::delete('/menu/{id}', [ManajemenMenuController::class, 'deleteMenu']);
    Route::get('/menu', [ManajemenMenuController::class, 'getMenu']);
     Route::get('/menu/{id}', [ManajemenMenuController::class, 'getMenuById']);

    // Kategori Management API
    Route::get('/kategori', [KategoriController::class, 'getKategori']);
    Route::get('/kategori/{id}', [KategoriController::class, 'getKategoriById']);
    Route::post('/kategori', [KategoriController::class, 'storeKategori']);
    Route::put('/kategori/{id}', [KategoriController::class, 'updateKategori']);
    Route::delete('/kategori/{id}', [KategoriController::class, 'deleteKategori']);
    
    // Orders API
    Route::get('/orders', [RiwayatPesananController::class, 'getOrders']);
    Route::get('/orders/{id}', [RiwayatPesananController::class, 'getOrderDetail']);
    Route::get('/orders/export/excel', [RiwayatPesananController::class, 'exportOrdersExcel']);
    Route::get('/orders/export/pdf', [RiwayatPesananController::class, 'exportOrdersPDF']);
    
    // Predictions API
    Route::get('/predictions', [RiwayatPrediksiController::class, 'getPredictions']);
    Route::get('/predictions/{id}', [RiwayatPrediksiController::class, 'getPredictionDetail']);
    Route::get('/predictions/export/excel', [RiwayatPrediksiController::class, 'exportPredictionsExcel']);
    Route::get('/predictions/export/pdf', [RiwayatPrediksiController::class, 'exportPredictionsPDF']);
    Route::get('/prediction-status', [RiwayatPrediksiController::class, 'getPredictionStatus']);
    Route::get('/prediction-latest', [RiwayatPrediksiController::class, 'getLatestPrediction']);
    Route::post('/lakukan-prediksi', [RiwayatPrediksiController::class, 'lakukanPrediksi']);
        
    // Topping API
    Route::get('/topping', [ToppingController::class, 'index']);
    Route::post('/topping', [ToppingController::class, 'store']);
    Route::put('/topping/{id}', [ToppingController::class, 'update']);
    Route::delete('/topping/{id}', [ToppingController::class, 'destroy']);
    Route::get('/topping/{id}', [ToppingController::class, 'getToppingById']);
    // Kelola Akun API
    Route::get('/kelola-akun', [KelolaAkunController::class, 'getData']);
    Route::get('/kelola-akun/{id}', [KelolaAkunController::class, 'show']);
    Route::post('/kelola-akun', [KelolaAkunController::class, 'store']);
    Route::put('/kelola-akun/{id}', [KelolaAkunController::class, 'update']);
    Route::post('/kelola-akun/{id}/reset-password', [KelolaAkunController::class, 'resetPassword']);
    Route::delete('/kelola-akun/{id}', [KelolaAkunController::class, 'destroy']);
    Route::post('/kelola-akun/change-password', [KelolaAkunController::class, 'changeSelfPassword']);

    //akurasi
    Route::post('/update-akurasi/{id}', [RiwayatPrediksiController::class, 'updateAkurasi']);

   // ==================== BAHAN BAKU API ====================
Route::get('/bahan-baku', [BahanBakuController::class, 'getAll']);
Route::get('/bahan-baku/{id}', [BahanBakuController::class, 'show']);

// ==================== RESEP MENU API ====================
Route::get('/resep-menu', [ResepMenuController::class, 'getAll']);
Route::get('/resep-menu/{id}', [ResepMenuController::class, 'show']);
Route::get('/resep-menu/by-menu/{id}', [ResepMenuController::class, 'getByMenu']);
});

// ==================== KASIR API ROUTES ====================
Route::middleware(['auth', 'role:kasir'])->prefix('api/kasir')->group(function () {
    Route::get('/orders', [KasirController::class, 'getOrders']);
    Route::get('/orders/{id}', [KasirController::class, 'getOrderDetail']);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/pengaturan-jadwal', [SettingController::class, 'index'])->name('admin.pengaturan-jadwal');
    Route::post('/admin/pengaturan-jadwal', [SettingController::class, 'update'])->name('admin.pengaturan-jadwal.update');
});

Route::get('/export/detail-excel', [LaporanController::class, 'exportDetailExcel'])->name('export.detail-excel')->middleware('auth');
Route::get('/export/detail-pdf', [LaporanController::class, 'exportDetailPDF'])->name('export.detail-pdf')->middleware('auth');