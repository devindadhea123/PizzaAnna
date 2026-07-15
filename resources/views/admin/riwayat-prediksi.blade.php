@extends('layouts.app')

@section('title', 'Riwayat Prediksi')

@section('content')
<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Prediksi</h1>
        <p class="text-gray-500 mt-1">Riwayat prediksi menu terlaris menggunakan metode Weighted Moving Average (WMA) 4 Minggu</p>
    </div>
    
    <!-- Export Buttons -->
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800"> Export Laporan</h3>
                <p class="text-sm text-gray-500 mt-0.5">Download data prediksi dalam format Excel atau PDF</p>
            </div>
            <div class="flex gap-3">
                <button onclick="exportToExcel()" 
                    class="group relative bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2.5 rounded-xl transition-all duration-300 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="bi bi-file-earmark-excel text-lg"></i>
                    <span class="font-medium">Export Excel</span>
                </button>
                <button onclick="exportToPDF()" 
                    class="group relative bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-2.5 rounded-xl transition-all duration-300 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="bi bi-file-earmark-pdf text-lg"></i>
                    <span class="font-medium">Export PDF</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Predictions Grid -->
    <div id="predictionsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
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
</div>

<!-- MODAL DETAIL PREDIKSI -->
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4" onclick="closeModal(event)">
    <div class="relative w-full max-w-3xl max-h-[90vh] overflow-hidden rounded-2xl bg-white shadow-2xl" onclick="event.stopPropagation()">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 px-6 py-4 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="bi bi-graph-up text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Detail Prediksi</h2>
                        <p class="text-xs text-red-100">Perbandingan Prediksi vs Data Aktual</p>
                    </div>
                </div>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 transition flex items-center justify-center">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Body - Scrollable -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)]">
            <div class="p-6" id="modalContent">
                <div class="text-center py-8">
                    <div class="inline-block w-10 h-10 border-4 border-gray-200 border-t-[#D73535] rounded-full animate-spin"></div>
                    <p class="mt-3 text-gray-500">Memuat detail...</p>
                </div>
            </div>
            <div id="modalRekomendasiPromosi" class="px-6 pb-6"></div>
        </div>
        
        <!-- Footer -->
        <div class="border-t border-gray-100 px-6 py-4 bg-gray-50">
            <div class="flex justify-between items-center">
                <div class="text-xs text-gray-400">
                    <span class="font-medium">Metode:</span> WMA 4 Minggu (Bobot 0.1, 0.2, 0.3, 0.4) × 4.35
                </div>
                <div class="flex gap-3">
                    <button onclick="closeModal()" class="px-5 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 transition">
                        Tutup
                    </button>
                    <button onclick="printDetail()" class="px-5 py-2 rounded-xl bg-[#D73535] hover:bg-red-700 text-white transition flex items-center gap-2">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- MODAL DETAIL STOK BAHAN BAKU -->
<div id="stokModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4" onclick="closeStokModal(event)">
    <div class="relative w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-2xl bg-white shadow-2xl" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="bi bi-box-seam text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold" id="stokModalTitle">Detail Stok Bahan Baku</h2>
                        <p class="text-xs text-orange-100">Kebutuhan Bahan Baku untuk Menu Terprediksi</p>
                    </div>
                </div>
                <button onclick="closeStokModal()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 transition flex items-center justify-center">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            <div class="bg-gray-50 p-4 rounded-xl mb-4 flex justify-between items-center">
                <div>
                    <p class="text-xs text-gray-500">Menu Terprediksi</p>
                    <p class="font-bold text-gray-800 text-lg" id="stokModalMenuName">-</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Prediksi Penjualan</p>
                    <p class="font-bold text-[#D73535] text-lg" id="stokModalMenuPrediksi">-</p>
                </div>
            </div>

            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-4 py-3">Bahan Baku</th>
                        <th scope="col" class="px-4 py-3">Kebutuhan</th>
                        <th scope="col" class="px-4 py-3">Stok Saat Ini</th>
                        <th scope="col" class="px-4 py-3">Status</th>
                        <th scope="col" class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="stokModalTableBody">
                    <!-- Filled dynamically via JS -->
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="border-t border-gray-100 px-6 py-4 bg-gray-50 flex justify-end">
            <button onclick="closeStokModal()" class="px-5 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- POP-UP FORM TAMBAH STOK -->
<div id="tambahStokModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4" onclick="closeTambahStokModal(event)">
    <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl p-6" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4 border-b pb-3">
            <h3 class="text-lg font-bold text-gray-800"><i class="bi bi-plus-circle text-red-600 mr-1"></i> Tambah Stok Bahan</h3>
            <button onclick="closeTambahStokModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form id="tambahStokForm" onsubmit="submitTambahStok(event)">
            <input type="hidden" id="tambahStokBahanId">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Bahan</label>
                    <input type="text" id="tambahStokBahanNama" readonly class="w-full bg-gray-100 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Stok Saat Ini</label>
                        <div class="flex items-center">
                            <input type="text" id="tambahStokSaatIni" readonly class="w-full bg-gray-100 border border-gray-200 rounded-l-xl px-3 py-2.5 text-sm text-gray-700 focus:outline-none">
                            <span class="bg-gray-200 border-y border-r border-gray-200 text-xs text-gray-600 rounded-r-xl px-2.5 py-3 border-l-0 text-center font-medium" id="tambahStokSatuanLabel1">satuan</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-red-500 uppercase mb-1">Kekurangan</label>
                        <div class="flex items-center">
                            <input type="text" id="tambahStokKekurangan" readonly class="w-full bg-red-50 border border-red-200 text-red-700 rounded-l-xl px-3 py-2.5 text-sm focus:outline-none">
                            <span class="bg-red-100 border-y border-r border-red-200 text-xs text-red-600 rounded-r-xl px-2.5 py-3 border-l-0 text-center font-medium" id="tambahStokSatuanLabel2">satuan</span>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Jumlah Tambahan</label>
                    <input type="number" step="any" id="tambahStokJumlah" required class="w-full border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded-xl px-3 py-2.5 text-sm text-gray-800 focus:outline-none">
                </div>

            </div>
            
            <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                <button type="button" onclick="closeTambahStokModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-red-600 hover:bg-orange-700 text-white text-sm font-semibold transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentPage = 1;
    let totalPages = 1;
    let activePredictionId = null;
    let activeMenuIndex = null;
    let activeMenuName = '';
    
    function formatRupiah(angka) {
        if (!angka && angka !== 0) return '0';
        return Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function getMonthName(month) {
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return months[month - 1];
    }
    
    function getStatusBadge(selisih) {
        if (selisih > 0) {
            return { icon: '', text: 'Naik ' + selisih, class: 'bg-green-100 text-green-700' };
        } else if (selisih < 0) {
            return { icon: '', text: 'Turun ' + Math.abs(selisih), class: 'bg-red-100 text-red-700' };
        } else {
            return { icon: '', text: 'Sama', class: 'bg-gray-100 text-gray-500' };
        }
    }
    
    function loadPredictions() {
        const month = document.getElementById('filterMonth')?.value || 'all';
        const year = document.getElementById('filterYear')?.value || 'all';
        
        let url = `/api/admin/predictions?page=${currentPage}`;
        if (month !== 'all') url += `&month=${month}`;
        if (year !== 'all') url += `&year=${year}`;
        
        fetch(url)
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
        predictions.forEach((prediction, index) => {
            const date = new Date(prediction.tanggal_prediksi);
            const formattedDate = `${date.getDate()} ${getMonthName(date.getMonth() + 1)} ${date.getFullYear()}`;
            const formattedTime = `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
            
            const targetParts = prediction.bulan_target.split('-');
            const targetBulanFormat = `${getMonthName(parseInt(targetParts[1]))} ${targetParts[0]}`;
            
            let hasilPrediksi = prediction.hasil_prediksi;
            if (typeof hasilPrediksi === 'string') hasilPrediksi = JSON.parse(hasilPrediksi);
            if (!Array.isArray(hasilPrediksi)) hasilPrediksi = [];
            
            // Cek data aktual dari detail_akurasi (tanpa akurasi)
            let detailAktual = prediction.detail_akurasi;
            if (typeof detailAktual === 'string') detailAktual = JSON.parse(detailAktual);
            if (!Array.isArray(detailAktual)) detailAktual = [];
            
            const hasAktual = detailAktual.length > 0 && detailAktual.some(item => item.aktual > 0);
            
            let previewHtml = '';
            hasilPrediksi.slice(0, 3).forEach((item, idx) => {
                const medals = ['🥇', '🥈', '🥉'];
                const aktualItem = detailAktual.find(a => a.nama_menu === item.nama_menu);
                const aktual = aktualItem ? aktualItem.aktual : null;
                const selisih = aktual !== null ? aktual - item.prediksi : null;
                
                const isStokAman = item.is_stok_aman !== false; // default true
                const stockBadge = isStokAman 
                    ? `<span class="inline-flex items-center gap-1 text-[10px] bg-green-50 text-green-700 px-2 py-0.5 rounded-full font-semibold border border-green-200 mt-1"><i class="bi bi-check-circle-fill"></i> Stok Aman</span>`
                    : `<span class="inline-flex items-center gap-1 text-[10px] bg-red-50 text-red-700 px-2 py-0.5 rounded-full font-semibold border border-red-200 mt-1"><i class="bi bi-exclamation-triangle-fill"></i> Stok Kurang</span>`;

                previewHtml += `
                    <div onclick="bukaStokModal(${prediction.id_prediksi}, ${idx}, '${item.nama_menu.replace(/'/g, "\\'")}')" 
                        class="flex justify-between items-center py-2.5 ${idx !== 2 ? 'border-b border-gray-100' : ''} cursor-pointer hover:bg-gray-50 p-2 rounded-xl transition duration-150 group/item">
                        <div class="flex items-center gap-2">
                            <span class="text-xl transition transform group-hover/item:scale-110">${medals[idx]}</span>
                            <div>
                                <span class="font-semibold text-gray-800 group-hover/item:text-orange-600 transition">${item.nama_menu}</span>
                                <br>
                                ${stockBadge}
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-[#D73535]">${item.prediksi} porsi</span>
                            ${aktual !== null ? `
                                <span class="text-xs text-gray-400 ml-1">| Akt: ${aktual}</span>
                                <br>
                                <span class="text-[10px] font-medium px-2 py-0.5 rounded-full border ${selisih > 0 ? 'bg-green-50 text-green-700 border-green-200' : selisih < 0 ? 'bg-red-50 text-red-700 border-red-200' : 'bg-gray-50 text-gray-500 border-gray-200'}">
                                    ${selisih > 0 ? '+' : ''}${selisih}
                                </span>
                            ` : `
                                <br>
                                <span class="text-[10px] text-gray-400 bg-gray-50 border border-gray-100 px-2 py-0.5 rounded-full">⏳ Menunggu</span>
                            `}
                        </div>
                    </div>
                `;
            });
            
            const delay = index * 50;
            
            html += `
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100" style="animation: fadeInUp 0.5s ease ${delay}ms both">
                    <div class="bg-gradient-to-r from-[#D73535] to-red-700 px-5 py-3 text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs opacity-80">Target Bulan</p>
                                <p class="text-lg font-bold">${targetBulanFormat}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs opacity-80">Dibuat Pada</p>
                                <p class="text-sm font-medium">${formattedDate}</p>
                                <p class="text-xs opacity-70">${formattedTime}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="mb-4">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2"> Data yang digunakan</p>
                            <p class="text-sm text-gray-600 bg-gray-50 p-2 rounded-lg">${prediction.data_yang_dipakai}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">🏆 Top 3 Menu Terlaris</p>
                            <div class="space-y-1">${previewHtml}</div>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full ${hasAktual ? 'bg-green-500' : 'bg-yellow-500'}"></span>
                                <span class="text-xs text-gray-500">
                                    ${hasAktual ? ' Data aktual tersedia' : '⏳ Menunggu data aktual'}
                                </span>
                            </div>
                            <span class="text-xs text-gray-400">
                                ${hasAktual ? 'Update: 27 ' + getMonthName(parseInt(prediction.bulan_target.split('-')[1])) : ''}
                            </span>
                        </div>
                        <button onclick="showDetail(${prediction.id_prediksi})" 
                            class="w-full mt-4 bg-gray-50 hover:bg-gray-100 text-gray-700 py-2 rounded-xl transition flex items-center justify-center gap-2 text-sm">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </button>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }
    
    function showDetail(predictionId) {
        const modal = document.getElementById('detailModal');
        const modalContent = document.getElementById('modalContent');
        const modalRekomendasi = document.getElementById('modalRekomendasiPromosi');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        modalContent.innerHTML = `
            <div class="text-center py-8">
                <div class="inline-block w-10 h-10 border-4 border-gray-200 border-t-[#D73535] rounded-full animate-spin"></div>
                <p class="mt-3 text-gray-500">Memuat detail...</p>
            </div>
        `;
        if (modalRekomendasi) modalRekomendasi.innerHTML = '';
        
            fetch(`/api/admin/predictions/${predictionId}`)
            .then(response => response.json())
            .then(prediction => {
                const date = new Date(prediction.tanggal_prediksi);
                const formattedDate = `${date.getDate()} ${getMonthName(date.getMonth() + 1)} ${date.getFullYear()}`;
                const formattedTime = `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
                
                const targetParts = prediction.bulan_target.split('-');
                const targetBulanFormat = `${getMonthName(parseInt(targetParts[1]))} ${targetParts[0]}`;
                
                let hasilPrediksi = prediction.hasil_prediksi;
                if (typeof hasilPrediksi === 'string') hasilPrediksi = JSON.parse(hasilPrediksi);
                if (!Array.isArray(hasilPrediksi)) hasilPrediksi = [];
                
                let detailAktual = prediction.detail_akurasi;
                if (typeof detailAktual === 'string') detailAktual = JSON.parse(detailAktual);
                if (!Array.isArray(detailAktual)) detailAktual = [];
                
                let rekomendasiPromosi = prediction.rekomendasi_promosi;
                if (typeof rekomendasiPromosi === 'string') rekomendasiPromosi = JSON.parse(rekomendasiPromosi);
                if (!Array.isArray(rekomendasiPromosi)) rekomendasiPromosi = [];
                
                const hasAktual = detailAktual.length > 0 && detailAktual.some(item => item.aktual > 0);
                
                // Gabungkan data prediksi dengan data aktual
                const mergedData = hasilPrediksi.map(pred => {
                    const aktual = detailAktual.find(a => a.nama_menu === pred.nama_menu);
                    return {
                        ...pred,
                        aktual: aktual ? aktual.aktual : null,
                        selisih: aktual ? aktual.aktual - pred.prediksi : null
                    };
                });
                
                let itemsHtml = '';
                const medals = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣', '6️⃣', '7️⃣', '8️⃣', '9️⃣', '🔟'];
                
                mergedData.forEach((item, idx) => {
                    const status = item.selisih !== null ? getStatusBadge(item.selisih) : null;
                    
                    itemsHtml += `
                        <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition px-2 rounded-lg">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">${medals[idx] || '🔹'}</span>
                                <div>
                                    <p class="font-semibold text-gray-800">${item.nama_menu}</p>
                                    <p class="text-xs text-gray-400">Rp ${formatRupiah(item.harga)}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center gap-4">
                                    <div>
                                        <p class="font-bold text-[#D73535]">${item.prediksi} porsi</p>
                                        <p class="text-xs text-gray-400">Prediksi</p>
                                    </div>
                                    ${item.aktual !== null ? `
                                        <div>
                                            <p class="font-bold text-green-600">${item.aktual} porsi</p>
                                            <p class="text-xs text-gray-400">Aktual</p>
                                        </div>
                                        <div class="min-w-[80px]">
                                            <span class="text-xs font-semibold px-3 py-1 rounded-full ${status.class} border">
                                                ${status.icon} ${item.selisih > 0 ? '+' : ''}${item.selisih}
                                            </span>
                                        </div>
                                    ` : `
                                        <div>
                                            <p class="text-sm text-gray-400">⏳</p>
                                            <p class="text-xs text-gray-400">Menunggu</p>
                                        </div>
                                    `}
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                let rekomendasiHtml = '';
                if (rekomendasiPromosi.length > 0) {
                    rekomendasiHtml = `
                        <div class="mt-6 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-4 border border-orange-200">
                            <h3 class="font-semibold mb-3 flex items-center gap-2 text-orange-800">
                                <i class="bi bi-megaphone"></i> 🎯 Rekomendasi Promosi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                ${rekomendasiPromosi.slice(0, 4).map(promo => `
                                    <div class="bg-white rounded-lg p-3 shadow-sm border border-orange-100">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xl">${promo.icon || '🎁'}</span>
                                            <span class="font-medium text-sm text-orange-800">${promo.judul || 'Promo'}</span>
                                        </div>
                                        <p class="text-xs text-gray-600">${promo.deskripsi || ''}</p>
                                        <p class="text-xs text-orange-600 mt-2">🎯 Target: ${promo.menu || 'Menu'}</p>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }
                
                modalContent.innerHTML = `
                    <div class="space-y-5">
                        <!-- Info Prediksi -->
                        <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl">
                            <div>
                                <p class="text-xs text-gray-500"> Dibuat Pada</p>
                                <p class="font-semibold text-gray-800">${formattedDate}</p>
                                <p class="text-xs text-gray-500">Jam: ${formattedTime}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500"> Bulan Target</p>
                                <p class="font-bold text-[#D73535] text-lg">${targetBulanFormat}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-500 mb-1"> Data yang digunakan</p>
                                <p class="text-sm text-gray-600 bg-white p-2 rounded-lg border">${prediction.data_yang_dipakai}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-500 mb-1"> Status Data Aktual</p>
                                <p class="text-sm ${hasAktual ? 'text-green-600' : 'text-yellow-600'} font-medium">
                                    ${hasAktual ? ' Data aktual sudah tersedia (Update 27)' : '⏳ Menunggu data aktual (akan update otomatis tgl 27)'}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Tabel Perbandingan -->
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="font-semibold text-gray-800"> Perbandingan Prediksi vs Aktual</span>
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">${mergedData.length} Menu</span>
                            </div>
                            <div class="bg-white rounded-xl border overflow-hidden">
                                ${itemsHtml}
                            </div>
                        </div>
                        
                        <!-- Info Metode -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                            <p class="text-xs text-gray-500">Metode Perhitungan</p>
                            <p class="font-semibold text-gray-800">Weighted Moving Average (WMA) 4 Minggu</p>
                            <p class="text-sm text-gray-600 mt-1">
                                Formula: <span class="font-mono bg-white px-2 py-0.5 rounded">(M1×0,1) + (M2×0,2) + (M3×0,3) + (M4×0,4) × 4,35</span>
                            </p>
                        </div>
                    </div>
                `;
                
                if (modalRekomendasi) {
                    modalRekomendasi.innerHTML = rekomendasiHtml;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="bi bi-exclamation-triangle text-3xl"></i>
                        <p class="mt-2">Gagal memuat detail</p>
                        <p class="text-xs mt-1">${error.message}</p>
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

    function bukaStokModal(predictionId, menuIndex, menuName) {
        activePredictionId = predictionId;
        activeMenuIndex = menuIndex;
        activeMenuName = menuName;
        
        const modal = document.getElementById('stokModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        document.getElementById('stokModalMenuName').innerText = menuName;
        
        renderStokTable();
    }

  function renderStokTable() {
    const tableBody = document.getElementById('stokModalTableBody');
    tableBody.innerHTML = `
        <tr>
            <td colspan="5" class="text-center py-8">
                <div class="inline-block w-8 h-8 border-4 border-gray-200 border-t-orange-600 rounded-full animate-spin"></div>
                <p class="mt-2 text-gray-500 text-xs">Memuat status stok...</p>
            </td>
        </tr>
    `;
    
    fetch(`/api/admin/predictions/${activePredictionId}`)
        .then(response => response.json())
        .then(prediction => {
            let hasilPrediksi = prediction.hasil_prediksi;
            if (typeof hasilPrediksi === 'string') hasilPrediksi = JSON.parse(hasilPrediksi);
            
            const menuObj = hasilPrediksi[activeMenuIndex];
            if (!menuObj) {
                tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-red-500">Menu tidak ditemukan</td></tr>`;
                return;
            }
            
            document.getElementById('stokModalMenuPrediksi').innerText = Math.round(menuObj.prediksi) + ' porsi';
            
            const stokDetail = menuObj.stok_detail || [];
            if (stokDetail.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-8 text-gray-400">Tidak ada data bahan baku untuk menu ini (Resep kosong).</td></tr>`;
                return;
            }
            
            let html = '';
            stokDetail.forEach((item, index) => {
                const isAman = item.is_aman;
                const statusClass = isAman ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50';
                const statusText = isAman 
                    ? `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold text-xs ${statusClass}"><i class="bi bi-check-circle"></i> AMAN</span>`
                    : `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold text-xs ${statusClass}"><i class="bi bi-exclamation-triangle"></i> KURANG ${Math.round(item.kurang * 100) / 100} ${item.satuan}</span>`;
                
                const rowId = `row-${item.id_bahan}`;
                const formId = `form-${item.id_bahan}`;
                
                // Main Row
                html += `
                    <tr id="${rowId}" class="border-b hover:bg-gray-50 transition ${!isAman ? 'cursor-pointer' : ''}">
                        <td class="px-4 py-3.5 font-semibold text-gray-800">${item.nama_bahan}</td>
                        <td class="px-4 py-3.5 font-mono">${Math.round(item.kebutuhan * 100) / 100} ${item.satuan}</td>
                        <td class="px-4 py-3.5 font-mono">${Math.round(item.stok * 100) / 100} ${item.satuan}</td>
                        <td class="px-4 py-3.5">${statusText}</td>
                        <td class="px-4 py-3.5 text-center">
                            ${!isAman ? `
                                <button onclick="toggleExpandRow(${item.id_bahan})" 
                                        class="w-8 h-8 rounded-full bg-orange-600 hover:bg-orange-700 text-white flex items-center justify-center transition shadow hover:shadow-md transform hover:scale-105"
                                        title="Tambah Stok">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            ` : '-'}
                        </td>
                    </tr>
                    
                    <!-- EXPANDABLE FORM ROW -->
                    <tr id="${formId}" style="display:none;" class="bg-yellow-50">
                        <td colspan="5" class="px-4 py-3">
                            <div class="flex flex-wrap items-center gap-4">
                                <div class="flex-1 min-w-[150px]">
                                    <label class="block text-xs text-gray-500 font-medium mb-1">Jumlah Tambahan</label>
                                    <div class="flex items-center">
                                        <input type="number" id="jumlah-${item.id_bahan}" 
                                               value="${Math.round(item.kurang * 100) / 100}" 
                                               class="w-full border border-gray-300 rounded-l-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                                        <span class="bg-gray-100 border border-l-0 border-gray-300 rounded-r-xl px-3 py-2 text-sm text-gray-600">${item.satuan}</span>
                                    </div>
                                </div>

                                <div class="flex gap-2 justify-items-end  mt-5 align-iitems-end">
                                    <button onclick="simpanTambahStokExpandable(${item.id_bahan})" 
                                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl transition flex items-center gap-1 shadow-sm hover:shadow-md">
                                        <i class=""></i> Simpan
                                    </button>
                                    <button onclick="toggleExpandRow(${item.id_bahan})" 
                                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition">
                                         Batal
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            tableBody.innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-red-500">Gagal memuat status stok: ${err.message}</td></tr>`;
        });
}

function toggleExpandRow(idBahan) {
    const formId = `form-${idBahan}`;
    const rowId = `row-${idBahan}`;
    const form = document.getElementById(formId);
    const row = document.getElementById(rowId);
    
    if (!form) return;
    
    if (form.style.display === 'none' || form.style.display === '') {
        // Buka form
        form.style.display = 'table-row';
        row.classList.add('bg-yellow-50');
        
        // Fokus ke input jumlah
        const input = document.getElementById(`jumlah-${idBahan}`);
        if (input) setTimeout(() => input.focus(), 100);
    } else {
        // Tutup form
        form.style.display = 'none';
        row.classList.remove('bg-yellow-50');
    }
}

function simpanTambahStokExpandable(idBahan) {
    const jumlahInput = document.getElementById(`jumlah-${idBahan}`);
    const keteranganInput = document.getElementById(`keterangan-${idBahan}`);
    
    const jumlah = parseFloat(jumlahInput?.value || 0);
    const keterangan = keteranganInput?.value || '';
    
    if (!jumlah || jumlah <= 0) {
        Swal.fire('Error', 'Jumlah tambahan harus lebih dari 0', 'error');
        return;
    }
    
    // Tampilkan loading pada tombol
    const btn = event?.target || document.querySelector(`#form-${idBahan} button`);
    const originalText = btn?.innerHTML || '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Menyimpan...';
    }
    
    fetch(`/admin/bahan-baku/tambah-stok/${idBahan}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            jumlah: jumlah,
            tipe: 'tambah',
            referensi: 'Prediksi WMA - menu ' + activeMenuName,
            keterangan: keterangan
        })
    })
    .then(response => response.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });
            
            // Tutup form expandable
            toggleExpandRow(idBahan);
            
            // Refresh tabel stok
            renderStokTable();
            
            // Refresh kartu prediksi utama
            loadPredictions();
        } else {
            Swal.fire('Gagal', data.message, 'error');
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
        Swal.fire('Error', err.message, 'error');
    });
}
    function submitTambahStok(event) {
        event.preventDefault();
        
        const idBahan = document.getElementById('tambahStokBahanId').value;
        const jumlah = document.getElementById('tambahStokJumlah').value;
        
        if (!jumlah || jumlah <= 0) {
            Swal.fire('Error', 'Jumlah tambahan harus lebih dari 0', 'error');
            return;
        }
        
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const origText = submitBtn.innerText;
        submitBtn.disabled = true;
        submitBtn.innerText = 'Menyimpan...';
        
        fetch(`/admin/bahan-baku/tambah-stok/${idBahan}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                jumlah: jumlah,
                tipe: 'tambah',
                referensi: 'Prediksi WMA - menu ' + activeMenuName
            })
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerText = origText;
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                
                closeTambahStokModal();
                renderStokTable(); // Refresh detail stock modal dynamically
                loadPredictions(); // Refresh main predictions grid
            } else {
                Swal.fire('Gagal', data.message, 'error');
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerText = origText;
            Swal.fire('Error', err.message, 'error');
        });
    }

    function closeStokModal(event) {
        if (event && event.target !== document.getElementById('stokModal')) return;
        const modal = document.getElementById('stokModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
    
    function closeTambahStokModal(event) {
        if (event && event.target !== document.getElementById('tambahStokModal')) return;
        const modal = document.getElementById('tambahStokModal');
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
                <title>Detail Prediksi - PizzaAnna</title>
                <style>
                    body { font-family: 'Arial', sans-serif; padding: 20px; font-size: 12px; }
                    @media print { body { margin: 0; padding: 15px; } }
                    .text-center { text-align: center; }
                    .font-bold { font-weight: bold; }
                    .text-[#D73535] { color: #D73535; }
                    .text-green-600 { color: #16a34a; }
                    .border { border: 1px solid #ddd; }
                    .border-b { border-bottom: 1px solid #ddd; }
                    .p-4 { padding: 1rem; }
                    .p-2 { padding: 0.5rem; }
                    .mb-4 { margin-bottom: 1rem; }
                    .mt-4 { margin-top: 1rem; }
                    .bg-gray-50 { background: #f9fafb; }
                    .bg-blue-50 { background: #eff6ff; }
                    .rounded { border-radius: 8px; }
                    table { width: 100%; border-collapse: collapse; }
                    th { background: #D73535; color: white; padding: 8px; text-align: left; }
                    td { padding: 8px; border-bottom: 1px solid #eee; }
                </style>
            </head>
            <body>
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #D73535;">PizzaAnna</h1>
                    <p style="font-size: 10px;">Jl. Pengging, Boyolali</p>
                    <hr style="border: 1px solid #D73535;">
                    <h2>Detail Prediksi Menu Terlaris</h2>
                </div>
                ${modalContent}
                ${rekomendasiHtml}
                <div style="text-align: center; margin-top: 20px; font-size: 8px; color: #999; border-top: 1px solid #ddd; padding-top: 10px;">
                    <p>Dicetak pada: ${new Date().toLocaleString()}</p>
                    <p>&copy; PizzaAnna - Sistem Informasi Restoran</p>
                </div>
            </body>
            </html>
        `);
        printWindow.print();
        printWindow.close();
    }
    
    function renderPagination(current, last, total) {
        const info = document.getElementById('paginationInfo');
        const buttons = document.getElementById('paginationButtons');
        
        if (total === 0) {
            info.innerHTML = 'Tidak ada data';
            buttons.innerHTML = '';
            return;
        }
        
        const start = (current - 1) * 6 + 1;
        const end = Math.min(current * 6, total);
        info.innerHTML = `Menampilkan ${start} - ${end} dari ${total} prediksi`;
        
        let html = '';
        if (current > 1) html += `<button onclick="goToPage(${current - 1})" class="px-3 py-1 border rounded-lg hover:bg-gray-100">← Prev</button>`;
        for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
            html += `<button onclick="goToPage(${i})" class="px-3 py-1 border rounded-lg ${i === current ? 'bg-[#D73535] text-white' : 'hover:bg-gray-100'}">${i}</button>`;
        }
        if (current < last) html += `<button onclick="goToPage(${current + 1})" class="px-3 py-1 border rounded-lg hover:bg-gray-100">Next →</button>`;
        buttons.innerHTML = html;
    }
    
    function goToPage(page) { currentPage = page; loadPredictions(); }
    
    function exportToExcel() {
        window.location.href = '/api/admin/predictions/export/excel';
    }
    
    function exportToPDF() {
        window.location.href = '/api/admin/predictions/export/pdf';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        loadPredictions();
    });
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection