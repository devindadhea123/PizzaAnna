@extends('layouts.app')

@section('title', 'Riwayat Prediksi')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Prediksi</h1>
        <p class="text-gray-500 mt-1">Riwayat prediksi menu terlaris menggunakan metode Weighted Moving Average (WMA)</p>
    </div>
    
    <!-- Export Buttons with Card Design -->
<div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Export Laporan</h3>
            <p class="text-sm text-gray-500 mt-0.5">Download data prediksi dalam format Excel atau PDF</p>
        </div>
        <div class="flex gap-3">
            <button onclick="exportToExcel()" 
                class="group relative bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2.5 rounded-xl transition-all duration-300 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="bi bi-file-earmark-excel text-lg"></i>
                <span class="font-medium">Export Excel</span>
                <div class="absolute inset-0 rounded-xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>
            <button onclick="exportToPDF()" 
                class="group relative bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-2.5 rounded-xl transition-all duration-300 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="bi bi-file-earmark-pdf text-lg"></i>
                <span class="font-medium">Export PDF</span>
                <div class="absolute inset-0 rounded-xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
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
</div>

<!-- MODAL DETAIL PREDIKSI -->
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4" onclick="closeModal(event)">
    <div class="relative w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-2xl bg-white shadow-2xl" onclick="event.stopPropagation()">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 px-6 py-4 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="bi bi-graph-up text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Detail Prediksi</h2>
                        <p class="text-xs text-red-100">Analisis hasil prediksi menu terlaris</p>
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
        <div class="border-t border-gray-100 px-6 py-4 bg-gray-50 flex justify-end gap-3">
            <button onclick="closeModal()" class="px-5 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 transition">
                Tutup
            </button>
            <button onclick="printDetail()" class="px-5 py-2 rounded-xl bg-[#D73535] hover:bg-red-700 text-white transition flex items-center gap-2">
                <i class="bi bi-printer"></i> Cetak
            </button>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    let totalPages = 1;
    
    function formatRupiah(angka) {
        if (!angka && angka !== 0) return '0';
        return Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function getMonthName(month) {
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agostus', 'September', 'Oktober', 'November', 'Desember'];
        return months[month - 1];
    }
    
    function getAkurasiBadge(akurasi) {
        if (akurasi >= 90) {
            return { icon: '🟢', text: 'Sangat Akurat', class: 'bg-green-100 text-green-700' };
        } else if (akurasi >= 80) {
            return { icon: '🟡', text: 'Akurat', class: 'bg-yellow-100 text-yellow-700' };
        } else if (akurasi >= 70) {
            return { icon: '🟠', text: 'Cukup Akurat', class: 'bg-orange-100 text-orange-700' };
        } else if (akurasi > 0) {
            return { icon: '🔴', text: 'Kurang Akurat', class: 'bg-red-100 text-red-700' };
        } else {
            return { icon: '⏳', text: 'Menunggu Data', class: 'bg-gray-100 text-gray-500' };
        }
    }
    
    function loadPredictions() {
        fetch(`/api/admin/predictions?page=${currentPage}`)
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
        predictions.forEach(prediction => {
            const date = new Date(prediction.tanggal_prediksi);
            const formattedDate = `${date.getDate()} ${getMonthName(date.getMonth() + 1)} ${date.getFullYear()}`;
            const formattedTime = `${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
            
            let hasilPrediksi = prediction.hasil_prediksi;
            if (typeof hasilPrediksi === 'string') hasilPrediksi = JSON.parse(hasilPrediksi);
            if (!Array.isArray(hasilPrediksi)) hasilPrediksi = [];
            
            let previewHtml = '';
            hasilPrediksi.slice(0, 3).forEach((item, idx) => {
                const medals = ['🥇', '🥈', '🥉'];
                previewHtml += `
                    <div class="flex justify-between items-center py-2 ${idx !== 2 ? 'border-b border-gray-100' : ''}">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">${medals[idx]}</span>
                            <span class="font-medium text-gray-800">${item.nama_menu}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-[#D73535]">${item.prediksi} porsi</span>
                            <span class="text-xs text-gray-500 ml-1 ${item.kenaikan >= 0 ? 'text-green-500' : 'text-red-500'}">
                                ${item.kenaikan >= 0 ? '↑' : '↓'} ${Math.abs(item.kenaikan)}%
                            </span>
                        </div>
                    </div>
                `;
            });
            
            const akurasi = prediction.rata_rata_akurasi || 0;
            const badge = getAkurasiBadge(akurasi);
            
            html += `
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-[#D73535] to-red-700 px-5 py-3 text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs opacity-80">Target Bulan</p>
                                <p class="text-lg font-bold">${prediction.bulan_target}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs opacity-80">Prediksi</p>
                                <p class="text-sm font-medium">${formattedDate}</p>
                                <p class="text-xs opacity-70">${formattedTime}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="mb-4">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Data yang digunakan</p>
                            <p class="text-sm text-gray-600 bg-gray-50 p-2 rounded-lg">${prediction.data_yang_dipakai}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Top 3 Menu Terlaris</p>
                            <div class="space-y-1">${previewHtml}</div>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-graph-up text-gray-400"></i>
                                <span class="text-xs text-gray-500">Akurasi</span>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full ${badge.class}">
                                ${badge.icon} ${akurasi > 0 ? akurasi + '%' : '-'} ${badge.text}
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
                
                let hasilPrediksi = prediction.hasil_prediksi;
                if (typeof hasilPrediksi === 'string') hasilPrediksi = JSON.parse(hasilPrediksi);
                if (!Array.isArray(hasilPrediksi)) hasilPrediksi = [];
                
                let rekomendasiPromosi = prediction.rekomendasi_promosi;
                if (typeof rekomendasiPromosi === 'string') rekomendasiPromosi = JSON.parse(rekomendasiPromosi);
                if (!Array.isArray(rekomendasiPromosi)) rekomendasiPromosi = [];
                
                const akurasi = prediction.rata_rata_akurasi || 0;
                const badge = getAkurasiBadge(akurasi);
                
                let itemsHtml = '';
                const medals = ['🥇', '🥈', '🥉'];
                hasilPrediksi.forEach((item, idx) => {
                    itemsHtml += `
                        <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-0">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">${medals[idx]}</span>
                                <div>
                                    <p class="font-semibold text-gray-800">${item.nama_menu}</p>
                                    <p class="text-xs text-gray-500">
                                        ${item.kenaikan >= 0 ? '📈 Naik' : '📉 Turun'} ${Math.abs(item.kenaikan)}% dari periode sebelumnya
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-[#D73535] text-lg">${item.prediksi} porsi</p>
                                <p class="text-xs text-gray-400">prediksi</p>
                            </div>
                        </div>
                    `;
                });
                
                let rekomendasiHtml = '';
                if (rekomendasiPromosi.length > 0) {
                    rekomendasiHtml = `
                        <div class="mt-6 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-4 border border-orange-200">
                            <h3 class="font-semibold mb-3 flex items-center gap-2 text-orange-800">
                                <i class="bi bi-megaphone"></i> Rekomendasi Promosi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                ${rekomendasiPromosi.map(promo => `
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xl">${promo.icon || '🎁'}</span>
                                            <span class="font-medium text-sm">${promo.judul || 'Promo'}</span>
                                        </div>
                                        <p class="text-xs text-gray-600">${promo.deskripsi || ''}</p>
                                        <p class="text-xs text-orange-600 mt-2">🎯 Target: ${promo.menu || 'Menu'}</p>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                } else {
                    rekomendasiHtml = `
                        <div class="mt-6 bg-gray-50 rounded-xl p-4 text-center">
                            <p class="text-sm text-gray-500">Tidak ada rekomendasi promosi</p>
                        </div>
                    `;
                }
                
                modalContent.innerHTML = `
                    <div class="space-y-5">
                        <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl">
                            <div>
                                <p class="text-xs text-gray-500">Tanggal Prediksi</p>
                                <p class="font-semibold text-gray-800">${formattedDate}</p>
                                <p class="text-xs text-gray-500">Jam: ${formattedTime}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Bulan Target</p>
                                <p class="font-bold text-[#D73535] text-lg">${prediction.bulan_target}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-500 mb-1">Data yang digunakan</p>
                                <p class="text-sm text-gray-600 bg-white p-2 rounded-lg border">${prediction.data_yang_dipakai}</p>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-xl border p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-semibold text-gray-700">
                                    <i class="bi bi-graph-up text-[#D73535]"></i> Rata-rata Akurasi
                                </span>
                                <span class="text-sm font-bold px-3 py-1 rounded-full ${badge.class}">
                                    ${badge.icon} ${akurasi > 0 ? akurasi + '%' : '-'} ${badge.text}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <p class="font-semibold mb-3 text-gray-800">📊 Hasil Prediksi Detail</p>
                            <div class="bg-white rounded-xl border overflow-hidden">
                                ${itemsHtml}
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-[#D73535] to-red-700 rounded-xl p-4 text-white">
                            <p class="text-xs opacity-90">Metode Perhitungan</p>
                            <p class="font-semibold">Weighted Moving Average (WMA)</p>
                            <p class="text-xs opacity-80 mt-1">Bobot 1,2,3,4,5,6 (6 bulan terakhir)</p>
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
                    .border { border: 1px solid #ddd; }
                    .border-b { border-bottom: 1px solid #ddd; }
                    .p-4 { padding: 1rem; }
                    .mb-4 { margin-bottom: 1rem; }
                    .mt-4 { margin-top: 1rem; }
                    .bg-gray-50 { background: #f9fafb; }
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
                <div style="text-align: center; margin-top: 20px; font-size: 8px; color: #999;">
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
@endsection