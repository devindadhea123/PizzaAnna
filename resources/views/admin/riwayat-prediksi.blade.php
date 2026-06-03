@extends('layouts.app')

@section('title', 'Riwayat Prediksi')

@section('content')
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Riwayat Prediksi</h1>
            <p class="text-gray-500">Riwayat prediksi menu terlaris menggunakan metode Weighted Moving Average (WMA)</p>
        </div>
        
        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Bulan</label>
                    <select id="filterMonth" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                        <option value="all">Semua Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Tahun</label>
                    <select id="filterYear" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                        <option value="all">Semua Tahun</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button onclick="filterPredictions()" class="bg-[#D73535] text-white px-6 py-2 rounded-xl hover:bg-red-700 transition flex items-center gap-2">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <button onclick="resetFilter()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-300 transition">
                        Reset
                    </button>
                </div>
                <div class="flex items-end justify-end gap-2">
                    <button onclick="exportToExcel()" class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition flex items-center gap-2">
                        <i class="bi bi-file-excel"></i> Export Excel
                    </button>
                    <button onclick="exportToPDF()" class="bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 transition flex items-center gap-2">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Predictions Grid -->
        <div id="predictionsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="col-span-full text-center py-12">
                <i class="bi bi-hourglass-split text-4xl animate-spin text-[#D73535]"></i>
                <p class="mt-3 text-gray-500">Loading predictions...</p>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="mt-8 flex justify-between items-center">
            <div class="text-sm text-gray-500" id="paginationInfo"></div>
            <div class="flex gap-2" id="paginationButtons"></div>
        </div>
    </main>
</div>

<!-- MODAL DETAIL PREDIKSI MODERN -->
<div id="detailModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    onclick="closeModal(event)">

    <div
        class="relative w-full max-w-2xl overflow-hidden rounded-[24px] bg-white shadow-[0_25px_80px_rgba(0,0,0,0.25)] animate-modal"
        onclick="event.stopPropagation()">

        <!-- Glow Background -->
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-red-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-[#D73535]/20 rounded-full blur-3xl"></div>

        <!-- HEADER -->
        <div
            class="relative bg-gradient-to-r from-[#D73535] via-red-600 to-red-700 px-5 py-4 text-white overflow-hidden">

            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_top_right,white,transparent_40%)]">
                </div>
            </div>

            <div class="relative flex items-center justify-between">

                <!-- Left -->
                <div class="flex items-center gap-3">

                    <!-- Modern Vector Icon -->
                    <div
                        class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur-md flex items-center justify-center border border-white/10 shadow-lg">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 text-white"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 17l6-6 4 4 7-7" />

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14 8h6v6" />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold tracking-wide">
                            Detail Prediksi
                        </h2>

                        <p class="text-xs text-red-100 mt-1">
                            Analisis hasil prediksi menu terlaris
                        </p>
                    </div>
                </div>

                <!-- Close -->
                <button onclick="closeModal()"
                    class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 transition duration-300 flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

            </div>
        </div>

        <!-- BODY -->
        <div class="relative bg-gray-50/70 backdrop-blur-xl">

            <!-- Scroll -->
            <div class="max-h-[75vh] overflow-y-auto">

                <!-- CONTENT -->
                <div class="p-5" id="modalContent">

                    <!-- Loading -->
                    <div class="flex flex-col items-center justify-center py-14">

                        <div
                            class="w-14 h-14 rounded-full border-4 border-red-100 border-t-[#D73535] animate-spin">
                        </div>

                        <p class="mt-4 text-sm text-gray-500 font-medium">
                            Memuat detail prediksi...
                        </p>

                    </div>

                </div>

                <!-- REKOMENDASI -->
                <div id="modalRekomendasiPromosi" class="px-5 pb-5"></div>

            </div>

            <!-- FOOTER -->
            <div
                class="border-t border-gray-200/70 bg-white/80 backdrop-blur-xl px-5 py-4 flex items-center justify-between">

                <!-- Info -->
                <div class="flex items-center gap-2 text-xs text-gray-500">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 text-[#D73535]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12l2 2 4-4" />

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 22C7 22 3 18 3 13V7l9-4 9 4v6c0 5-4 9-9 9z" />
                    </svg>

                    <span>Data prediksi tersimpan aman</span>
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-3">

                    <!-- Tutup -->
                    <button onclick="closeModal()"
                        class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition-all duration-300">

                        Tutup
                    </button>

                    <!-- Cetak -->
                    <button onclick="printDetail()"
                        class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#D73535] to-red-700 hover:scale-105 text-white text-sm font-medium shadow-lg shadow-red-300/30 transition-all duration-300 flex items-center gap-2">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 9V4h12v5" />

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18h12v2H6z" />

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 14h12" />
                        </svg>

                        Cetak
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes modalFade {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.96);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .animate-modal {
        animation: modalFade 0.35s ease;
    }

    /* Scrollbar */
    #detailModal ::-webkit-scrollbar {
        width: 6px;
    }

    #detailModal ::-webkit-scrollbar-track {
        background: transparent;
    }

    #detailModal ::-webkit-scrollbar-thumb {
        background: rgba(215, 53, 53, 0.4);
        border-radius: 999px;
    }

    #detailModal ::-webkit-scrollbar-thumb:hover {
        background: rgba(215, 53, 53, 0.7);
    }
</style>
<script>
    let currentPage = 1;
    let totalPages = 1;
    
    function formatRupiah(angka) {
        if (!angka && angka !== 0) return '0';
        return Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function getMonthName(month) {
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return months[month];
    }
    
    // Load predictions
    function loadPredictions() {
        const month = document.getElementById('filterMonth').value;
        const year = document.getElementById('filterYear').value;
        
        fetch(`/api/admin/predictions?page=${currentPage}&month=${month}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                totalPages = data.last_page;
                renderPredictions(data.data);
                renderPagination(data.current_page, data.last_page, data.total);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('predictionsGrid').innerHTML = `
                    <div class="col-span-full text-center py-12 text-red-500">
                        <i class="bi bi-wifi-off text-4xl"></i>
                        <p class="mt-3">Error loading data. Please refresh.</p>
                    </div>
                `;
            });
    }
    
    // Render predictions cards
function renderPredictions(predictions) {
    const container = document.getElementById('predictionsGrid');
    
    if (predictions.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12 text-gray-400">
                <i class="bi bi-inbox text-5xl"></i>
                <p class="mt-3">Belum ada data prediksi</p>
                <p class="text-sm mt-1">Lakukan prediksi pertama dari halaman dashboard</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    predictions.forEach(prediction => {
        const date = new Date(prediction.tanggal_prediksi);
        const formattedDate = `${date.getDate()} ${getMonthName(date.getMonth())} ${date.getFullYear()}`;
        const formattedTime = `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}:${date.getSeconds().toString().padStart(2, '0')}`;
        
        // Ambil hasil prediksi (support string JSON atau array)
        let hasilPrediksi = prediction.hasil_prediksi;
        if (typeof hasilPrediksi === 'string') {
            hasilPrediksi = JSON.parse(hasilPrediksi);
        }
        if (!Array.isArray(hasilPrediksi)) {
            hasilPrediksi = [];
        }
        
        // Preview TOP 3 di card
        let previewHtml = '';
        hasilPrediksi.slice(0, 3).forEach((item, idx) => {
            let medal = '';
            if (idx === 0) medal = '🥇';
            else if (idx === 1) medal = '🥈';
            else medal = '🥉';
            
            previewHtml += `
                <div class="flex justify-between items-center p-2 ${idx === 0 ? 'bg-yellow-50 rounded-lg' : ''}">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">${medal}</span>
                        <span class="font-medium">${item.nama_menu}</span>
                    </div>
                    <div class="text-right">
                        <span class="font-bold text-[#D73535]">${item.prediksi} porsi</span>
                        <span class="text-xs text-gray-500 ml-2">${item.kenaikan > 0 ? '+' : ''}${item.kenaikan}%</span>
                    </div>
                </div>
            `;
        });
        
        // ⭐ HITUNG AKURASI ⭐
        let rataAkurasi = prediction.rata_rata_akurasi || 0;
        let akurasiIcon = '';
        let akurasiText = '';
        let akurasiClass = '';
        
        if (rataAkurasi >= 90) {
            akurasiIcon = '🟢';
            akurasiText = 'Sangat Akurat';
            akurasiClass = 'bg-green-100 text-green-700';
        } else if (rataAkurasi >= 80) {
            akurasiIcon = '🟡';
            akurasiText = 'Akurat';
            akurasiClass = 'bg-yellow-100 text-yellow-700';
        } else if (rataAkurasi >= 70) {
            akurasiIcon = '🟠';
            akurasiText = 'Cukup Akurat';
            akurasiClass = 'bg-orange-100 text-orange-700';
        } else if (rataAkurasi > 0) {
            akurasiIcon = '🔴';
            akurasiText = 'Kurang Akurat';
            akurasiClass = 'bg-red-100 text-red-700';
        } else {
            akurasiIcon = '⏳';
            akurasiText = 'Menunggu Data';
            akurasiClass = 'bg-gray-100 text-gray-500';
        }
        
        html += `
            <div class="prediction-card bg-white rounded-2xl shadow-sm overflow-hidden fade-in">
                <div class="bg-gradient-to-r from-[#D73535] to-red-700 p-4 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm opacity-80">Target Bulan</p>
                            <p class="text-xl font-bold">${prediction.bulan_target}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-80">Tanggal Prediksi</p>
                            <p class="text-sm font-semibold">${formattedDate}</p>
                            <p class="text-xs opacity-70">${formattedTime}</p>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-2">Data yang digunakan:</p>
                        <p class="text-sm font-medium text-gray-700 bg-gray-100 p-2 rounded-lg">${prediction.data_yang_dipakai}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-2">Hasil Prediksi Top 3:</p>
                        <div class="space-y-2">
                            ${previewHtml}
                        </div>
                    </div>
                    
                    <!-- ⭐ TAMPILAN AKURASI ⭐ -->
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">📊 Akurasi:</span>
                            <span class="text-xs font-bold px-2 py-1 rounded-full ${akurasiClass}">
                                ${akurasiIcon} ${rataAkurasi > 0 ? rataAkurasi + '%' : '-'} ${akurasiText}
                            </span>
                        </div>
                    </div>
                    
                    <button onclick="showDetail(${prediction.id_prediksi})" class="w-full mt-3 bg-gray-100 text-gray-700 py-2 rounded-xl hover:bg-gray-200 transition flex items-center justify-center gap-2">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </button>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}
    
 // ==================== SHOW DETAIL MODAL ====================
function showDetail(predictionId) {
    console.log("🔍 showDetail dipanggil untuk ID:", predictionId);
    
    const modal = document.getElementById('detailModal');
    const modalContent = document.getElementById('modalContent');
    const modalRekomendasi = document.getElementById('modalRekomendasiPromosi');
    
    if (!modal) {
        console.error("Modal tidak ditemukan!");
        return;
    }
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    modalContent.innerHTML = `
        <div class="text-center py-8">
            <i class="bi bi-hourglass-split text-3xl animate-spin text-gray-400"></i>
            <p class="mt-2">Loading detail...</p>
        </div>
    `;
    if (modalRekomendasi) modalRekomendasi.innerHTML = '';
    
    fetch(`/api/admin/predictions/${predictionId}`)
        .then(response => response.json())
        .then(prediction => {
            console.log("✅ Response API:", prediction);
            
            const date = new Date(prediction.tanggal_prediksi);
            const formattedDate = `${date.getDate()} ${getMonthName(date.getMonth())} ${date.getFullYear()}`;
            const formattedTime = `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}:${date.getSeconds().toString().padStart(2, '0')}`;
            
            // Ambil hasil prediksi (TOP 3)
            let hasilPrediksi = prediction.hasil_prediksi;
            if (typeof hasilPrediksi === 'string') {
                hasilPrediksi = JSON.parse(hasilPrediksi);
            }
            if (!Array.isArray(hasilPrediksi)) {
                hasilPrediksi = [];
            }
            
            // Ambil rekomendasi
            let rekomendasiPromosi = prediction.rekomendasi_promosi;
            if (typeof rekomendasiPromosi === 'string') {
                rekomendasiPromosi = JSON.parse(rekomendasiPromosi);
            }
            if (!Array.isArray(rekomendasiPromosi)) {
                rekomendasiPromosi = [];
            }
            
            // ⭐ HITUNG AKURASI UNTUK MODAL ⭐
            let rataAkurasi = prediction.rata_rata_akurasi || 0;
            let akurasiIcon = '';
            let akurasiText = '';
            let akurasiClass = '';
            
            if (rataAkurasi >= 90) {
                akurasiIcon = '🟢';
                akurasiText = 'Sangat Akurat';
                akurasiClass = 'bg-green-100 text-green-700';
            } else if (rataAkurasi >= 80) {
                akurasiIcon = '🟡';
                akurasiText = 'Akurat';
                akurasiClass = 'bg-yellow-100 text-yellow-700';
            } else if (rataAkurasi >= 70) {
                akurasiIcon = '🟠';
                akurasiText = 'Cukup Akurat';
                akurasiClass = 'bg-orange-100 text-orange-700';
            } else if (rataAkurasi > 0) {
                akurasiIcon = '🔴';
                akurasiText = 'Kurang Akurat';
                akurasiClass = 'bg-red-100 text-red-700';
            } else {
                akurasiIcon = '⏳';
                akurasiText = 'Menunggu Data';
                akurasiClass = 'bg-gray-100 text-gray-500';
            }
            
            console.log("📊 Jumlah menu prediksi (TOP 3):", hasilPrediksi.length);
            console.log("📦 Jumlah rekomendasi:", rekomendasiPromosi.length);
            
            // Build items HTML (TOP 3 menu)
            let itemsHtml = '';
            hasilPrediksi.forEach((item, idx) => {
                let medal = '';
                if (idx === 0) medal = '🥇';
                else if (idx === 1) medal = '🥈';
                else medal = '🥉';
                
                itemsHtml += `
                    <div class="flex justify-between items-center py-3 border-b">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl">${medal}</span>
                            <div>
                                <p class="font-semibold text-gray-800 text-lg">${item.nama_menu}</p>
                                <p class="text-sm text-gray-500">${item.kenaikan > 0 ? 'Naik' : 'Turun'} ${Math.abs(item.kenaikan)}% dari periode sebelumnya</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-[#D73535] text-xl">${item.prediksi} porsi</p>
                            <p class="text-xs text-gray-500">prediksi</p>
                        </div>
                    </div>
                `;
            });
            
            // Build rekomendasi HTML
            let rekomendasiHtml = '';
            if (rekomendasiPromosi.length > 0) {
                rekomendasiHtml = `
                    <div class="mt-4 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-4 border border-orange-200">
                        <h3 class="font-bold mb-3 flex items-center gap-2 text-base text-orange-800">
                            <i class="bi bi-megaphone text-orange-600"></i> Rekomendasi Promosi Bulan Depan
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            ${rekomendasiPromosi.map(promo => `
                                <div class="bg-white rounded-lg p-3 hover:shadow-md transition border border-orange-100">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-2xl">${promo.icon || '🎁'}</span>
                                        <span class="font-semibold text-sm text-gray-800">${promo.judul || 'Promo'}</span>
                                    </div>
                                    <p class="text-xs text-gray-600">${promo.deskripsi || ''}</p>
                                    <div class="text-xs text-orange-600 mt-2 flex items-center gap-1">
                                        <i class="bi bi-tag"></i> Target: ${promo.menu || 'Menu'}
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else {
                rekomendasiHtml = `
                    <div class="mt-4 bg-gray-50 rounded-xl p-4 text-center border border-gray-200">
                        <p class="text-sm text-gray-500">
                            <i class="bi bi-info-circle"></i> Tidak ada rekomendasi promosi untuk periode ini.
                        </p>
                    </div>
                `;
            }
            
            modalContent.innerHTML = `
                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl">
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Prediksi</p>
                            <p class="font-semibold">${formattedDate}</p>
                            <p class="text-sm text-gray-500">Jam: ${formattedTime}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Bulan Target</p>
                            <p class="font-semibold text-[#D73535] text-lg">${prediction.bulan_target}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Data yang digunakan</p>
                            <p class="text-sm font-medium text-gray-700 bg-white p-2 rounded-lg border">${prediction.data_yang_dipakai}</p>
                        </div>
                    </div>
                    
                    <!-- ⭐ TAMPILAN AKURASI DI MODAL ⭐ -->
                    <div class="bg-white rounded-xl border p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-700">
                                <i class="bi bi-graph-up"></i> Rata-rata Akurasi:
                            </span>
                            <span class="text-sm font-bold px-3 py-1 rounded-full ${akurasiClass}">
                                ${akurasiIcon} ${rataAkurasi > 0 ? rataAkurasi + '%' : '-'} ${akurasiText}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <p class="font-semibold mb-3 flex items-center gap-2">
                            <i class="bi bi-trophy text-[#D73535]"></i> Hasil Prediksi Detail (Top 3)
                        </p>
                        <div class="bg-white rounded-xl border overflow-hidden">
                            ${itemsHtml}
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-[#D73535] to-red-700 rounded-xl p-4 text-white">
                        <p class="text-sm opacity-90">Metode yang digunakan:</p>
                        <p class="font-semibold">Weighted Moving Average (WMA)</p>
                        <p class="text-xs opacity-80 mt-1">Bobot: 1,2,3 untuk 3 bulan terakhir</p>
                    </div>
                </div>
            `;
            
            if (modalRekomendasi) {
                modalRekomendasi.innerHTML = rekomendasiHtml;
            }
        })
        .catch(error => {
            console.error("❌ Error:", error);
            modalContent.innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <i class="bi bi-exclamation-triangle text-3xl"></i>
                    <p class="mt-2">Error loading detail: ${error.message}</p>
                </div>
            `;
        });
}

function closeModal(event) {
        if (event && event.target !== document.getElementById('detailModal')) return;
        const modal = document.getElementById('detailModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
    
    function printDetail() {
        const modalContent = document.getElementById('modalContent').innerHTML;
        const modalRekomendasi = document.getElementById('modalRekomendasiPromosi');
        const rekomendasiHtml = modalRekomendasi ? modalRekomendasi.innerHTML : '';
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Detail Prediksi</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    @media print {
                        body { margin: 0; padding: 15px; }
                    }
                </style>
            </head>
            <body>
                ${modalContent}
                ${rekomendasiHtml}
            </body>
            </html>
        `);
        printWindow.print();
        printWindow.close();
    }
    
    function renderPagination(current, last, total) {
        const info = document.getElementById('paginationInfo');
        const buttons = document.getElementById('paginationButtons');
        
        if (!info || !buttons) return;
        
        if (total === 0) {
            info.innerHTML = 'Tidak ada data';
            buttons.innerHTML = '';
            return;
        }
        
        const start = (current - 1) * 6 + 1;
        const end = Math.min(current * 6, total);
        info.innerHTML = `Menampilkan ${start} - ${end} dari ${total} prediksi`;
        
        let html = '';
        if (current > 1) {
            html += `<button onclick="goToPage(${current - 1})" class="px-3 py-1 border rounded-lg hover:bg-gray-100">← Prev</button>`;
        }
        for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
            html += `<button onclick="goToPage(${i})" class="px-3 py-1 border rounded-lg ${i === current ? 'bg-[#D73535] text-white' : 'hover:bg-gray-100'}">${i}</button>`;
        }
        if (current < last) {
            html += `<button onclick="goToPage(${current + 1})" class="px-3 py-1 border rounded-lg hover:bg-gray-100">Next →</button>`;
        }
        buttons.innerHTML = html;
    }
    
    function goToPage(page) {
        currentPage = page;
        loadPredictions();
    }
    
    function filterPredictions() {
        currentPage = 1;
        loadPredictions();
    }
    
    function resetFilter() {
        document.getElementById('filterMonth').value = 'all';
        document.getElementById('filterYear').value = 'all';
        currentPage = 1;
        loadPredictions();
    }
    
function exportToExcel() {
    const month = document.getElementById('filterMonth').value;
    const year = document.getElementById('filterYear').value;
    
    let url = '/api/admin/predictions/export/excel';
    let params = [];
    
    if (month && month !== 'all') params.push(`month=${month}`);
    if (year && year !== 'all') params.push(`year=${year}`);
    
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    
    window.location.href = url;
}

function exportToPDF() {
    const month = document.getElementById('filterMonth').value;
    const year = document.getElementById('filterYear').value;
    
    let url = '/api/admin/predictions/export/pdf';
    let params = [];
    
    if (month && month !== 'all') params.push(`month=${month}`);
    if (year && year !== 'all') params.push(`year=${year}`);
    
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    
    window.location.href = url;
}
    
    // Initial load
    document.addEventListener('DOMContentLoaded', function() {
        console.log("🚀 Halaman riwayat prediksi dimuat");
        loadPredictions();
    });
</script>
@endsection