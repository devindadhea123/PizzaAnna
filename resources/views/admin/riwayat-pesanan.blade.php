@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Pesanan</h1>
        <p class="text-gray-500">Kelola dan lihat semua transaksi pesanan</p>
    </div>
    
    <!-- FILTER BULAN & TAHUN -->
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
        <div class="flex items-center gap-4 flex-wrap">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select id="filterMonth" class="border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    <option value="">-- Pilih Bulan --</option>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select id="filterYear" class="border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    <option value="">-- Pilih Tahun --</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                </select>
            </div>
            <button onclick="filterOrders()" class="bg-[#D73535] text-white px-6 py-2 rounded-xl hover:bg-red-700 transition mt-5">
                <i class="bi bi-search"></i> Filter
            </button>
            <button onclick="resetFilter()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-300 transition mt-5">
                Reset
            </button>
            <div class="text-sm text-gray-500 mt-5" id="filterInfo"></div>
        </div>
    </div>
    
    <!-- Export Buttons -->
    <div class="flex justify-end gap-3 mb-4">
        <button onclick="exportToExcel()" class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition flex items-center gap-2">
            <i class="bi bi-file-excel"></i> Export Excel
        </button>
        <button onclick="exportToPDF()" class="bg-red-600 text-white px-4 py-2 rounded-xl hover:bg-red-700 transition flex items-center gap-2">
            <i class="bi bi-file-pdf"></i> Export PDF
        </button>
    </div>
    
    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Tanggal & Jam</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Invoice</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Meja</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">Total</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Metode</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody id="ordersTable">
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-400">
                            <i class="bi bi-hourglass-split text-3xl animate-spin"></i>
                            <p class="mt-2">Loading data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="border-t px-6 py-4 flex justify-between items-center bg-gray-50">
            <div class="text-sm text-gray-500" id="paginationInfo"></div>
            <div class="flex gap-2" id="paginationButtons"></div>
        </div>
    </div>
</div>

<!-- MODAL DETAIL PESANAN -->
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm" onclick="closeModal(event)">
    <div class="bg-white rounded-2xl w-full max-w-3xl mx-4 shadow-2xl" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 p-5 rounded-t-2xl flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="bi bi-receipt text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Detail Pesanan</h2>
                    <p class="text-white/80 text-xs mt-0.5">Informasi lengkap transaksi</p>
                </div>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 transition flex items-center justify-center text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Content -->
        <div id="modalContent" class="p-5 max-h-[70vh] overflow-y-auto bg-gray-50">
            <div class="flex flex-col items-center justify-center py-12">
                <div class="w-12 h-12 border-4 border-gray-200 border-t-[#D73535] rounded-full animate-spin mb-4"></div>
                <p class="text-gray-500">Memuat detail pesanan...</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t px-5 py-4 flex justify-end gap-3 bg-white rounded-b-2xl">
            <button onclick="closeModal()" class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition font-medium">
                Tutup
            </button>
            <button onclick="printDetail()" class="px-5 py-2 rounded-xl bg-[#D73535] hover:bg-red-700 text-white transition font-medium flex items-center gap-2">
                <i class="bi bi-printer"></i> Cetak Detail
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentPage = 1;
    let totalPages = 1;
    
    function formatRupiah(angka) {
        if (!angka && angka !== 0) return '0';
        return Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    function getMonthName(month) {
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return months[month - 1];
    }
    
    function loadOrders() {
        const month = document.getElementById('filterMonth').value;
        const year = document.getElementById('filterYear').value;
        
        let url = `/api/admin/orders?page=${currentPage}`;
        if (month) url += `&month=${month}`;
        if (year) url += `&year=${year}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                totalPages = data.last_page;
                renderTable(data.data);
                renderPagination(data.current_page, data.last_page, data.total);
                
                const monthName = month ? document.getElementById('filterMonth').options[document.getElementById('filterMonth').selectedIndex]?.text : '';
                if (month && year) {
                    document.getElementById('filterInfo').innerHTML = `Menampilkan data: ${monthName} ${year}`;
                } else if (month) {
                    document.getElementById('filterInfo').innerHTML = `Menampilkan data bulan: ${monthName}`;
                } else if (year) {
                    document.getElementById('filterInfo').innerHTML = `Menampilkan data tahun: ${year}`;
                } else {
                    document.getElementById('filterInfo').innerHTML = 'Semua data';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('ordersTable').innerHTML = `
                    <tr><td colspan="8" class="text-center py-10 text-red-500">
                        <i class="bi bi-wifi-off text-3xl"></i>
                        <p class="mt-2">Error loading data. Please refresh.</p>
                    <\/td><\/tr>
                `;
            });
    }
    
    function renderTable(orders) {
        const tbody = document.getElementById('ordersTable');
        
        if (orders.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center py-10 text-gray-400">
                <i class="bi bi-inbox text-3xl"></i>
                <p class="mt-2">Tidak ada data pesanan</p><\/td><\/tr>`;
            return;
        }
        
        let html = '';
        orders.forEach((order, index) => {
            let formattedDate = '-';
            if (order.tanggal) {
                const date = new Date(order.tanggal);
                if (!isNaN(date.getTime())) {
                    const day = date.getDate().toString().padStart(2, '0');
                    const month = (date.getMonth() + 1).toString().padStart(2, '0');
                    const year = date.getFullYear();
                    const hours = date.getHours().toString().padStart(2, '0');
                    const minutes = date.getMinutes().toString().padStart(2, '0');
                    formattedDate = `${day}/${month}/${year} ${hours}:${minutes}`;
                }
            }
            
            let mejaText = 'Take Away';
            if (order.no_meja && order.no_meja !== null) {
                mejaText = `Meja ${order.no_meja}`;
            }
            
            html += `
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-600">${(currentPage - 1) * 15 + index + 1}<\/td>
                    <td class="px-6 py-4 text-sm text-gray-600">${formattedDate}<\/td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-600">${order.no_invoice || '-'}<\/td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">${order.nama_customer || '-'}<\/td>
                    <td class="px-6 py-4 text-sm text-gray-600">${mejaText}<\/td>
                    <td class="px-6 py-4 text-sm font-bold text-[#D73535] text-right">Rp ${formatRupiah(order.total_bayar)}<\/td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded-full text-xs ${order.metode_bayar === 'tunai' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'}">
                            ${order.metode_bayar === 'tunai' ? 'Tunai' : 'QRIS'}
                        <\/span>
                    <\/td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="showDetail(${order.id_pesanan})" class="text-blue-600 hover:text-blue-800 transition">
                            <i class="bi bi-eye text-xl"></i>
                        <\/button>
                    <\/td>
                <\/tr>
            `;
        });
        tbody.innerHTML = html;
    }
    
    function showDetail(orderId) {
        const modal = document.getElementById('detailModal');
        const modalContent = document.getElementById('modalContent');
        
        if (!modal) return;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        modalContent.innerHTML = `<div class="flex flex-col items-center justify-center py-12"><div class="w-12 h-12 border-4 border-gray-200 border-t-[#D73535] rounded-full animate-spin mb-4"></div><p class="text-gray-500">Memuat detail pesanan...</p></div>`;
        
        fetch(`/api/admin/orders/${orderId}`)
            .then(response => response.json())
            .then(order => {
                console.log("Order data:", order);
                
                let formattedDate = '-';
                let formattedTime = '-';
                if (order.tanggal) {
                    const date = new Date(order.tanggal);
                    if (!isNaN(date.getTime())) {
                        const day = date.getDate();
                        const month = date.getMonth() + 1;
                        const year = date.getFullYear();
                        const hours = date.getHours().toString().padStart(2, '0');
                        const minutes = date.getMinutes().toString().padStart(2, '0');
                        formattedDate = `${day} ${getMonthName(month)} ${year}`;
                        formattedTime = `${hours}:${minutes}`;
                    }
                }
                
                const metodeText = order.metode_bayar === 'tunai' ? 'Tunai' : 'QRIS';
                const metodeIcon = order.metode_bayar === 'tunai' ? 'cash' : 'qr-code';
                
                let mejaText = 'Take Away';
                if (order.no_meja && order.no_meja !== null) {
                    mejaText = `Meja ${order.no_meja}`;
                }
                
                let itemsHtml = '';
                if (order.detail_pesanan && order.detail_pesanan.length > 0) {
                    order.detail_pesanan.forEach(item => {
                        let menuName = item.menu?.nama_menu || 'Menu tidak ditemukan';
                        let ukuranText = '';
                        
                        if (item.ukuran) {
                            ukuranText = `<span class="text-xs text-gray-400 ml-1">(${item.ukuran})</span>`;
                        }
                        
                        itemsHtml += `
                            <div class="border-b border-gray-100 pb-3 mb-3 last:border-0">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800">${menuName}${ukuranText}</p>
                                        <p class="text-sm text-gray-500">${item.jumlah} x Rp ${formatRupiah(item.harga_satuan)}</p>
                        `;
                        
                        const toppingList = item.topping_list || item.toppings;
                        if (toppingList && toppingList.length > 0) {
                            itemsHtml += `<div class="mt-1 ml-3 space-y-0.5">`;
                            toppingList.forEach(topping => {
                                const toppingNama = topping.nama || topping.nama_topping;
                                itemsHtml += `
                                    <p class="text-xs text-gray-500 flex justify-between">
                                        <span>+ ${toppingNama}</span>
                                        <span>Rp ${formatRupiah(topping.harga)}</span>
                                    </p>
                                `;
                            });
                            itemsHtml += `</div>`;
                        }
                        
                        itemsHtml += `
                                    </div>
                                    <div class="text-right ml-4">
                                        <p class="font-bold text-[#D73535]">Rp ${formatRupiah(item.subtotal)}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    itemsHtml = '<p class="text-gray-500 text-sm text-center py-4">Tidak ada detail menu</p>';
                }
                
                modalContent.innerHTML = `
                    <div class="space-y-5">
                        <!-- Info Pesanan -->
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-xs text-gray-400">No. Invoice</p>
                                    <p class="font-semibold text-sm">${order.no_invoice || '-'}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Tanggal</p>
                                    <p class="font-semibold text-sm">${formattedDate}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Jam</p>
                                    <p class="font-semibold text-sm">${formattedTime}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Customer</p>
                                    <p class="font-semibold text-sm">${order.nama_customer || '-'}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Meja</p>
                                    <p class="font-semibold text-sm">${mejaText}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Kasir</p>
                                    <p class="font-semibold text-sm">${order.kasir?.nama_lengkap || '-'}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Metode Bayar</p>
                                    <p class="font-semibold text-sm flex items-center gap-1">
                                        <i class="bi bi-${metodeIcon} text-[#D73535]"></i> ${metodeText}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detail Menu -->
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                            <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="bi bi-receipt text-[#D73535]"></i> Detail Menu
                            </h3>
                            <div class="max-h-64 overflow-y-auto">
                                ${itemsHtml}
                            </div>
                        </div>
                        
                        <!-- Total -->
                        <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-4 border border-gray-100">
                            <div class="flex justify-between items-center">
                                <p class="font-bold text-lg">TOTAL</p>
                                <p class="font-bold text-2xl text-[#D73535]">Rp ${formatRupiah(order.total_bayar)}</p>
                            </div>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                console.error("Error:", error);
                modalContent.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="bi bi-exclamation-triangle text-3xl"></i>
                        <p class="mt-2">Gagal memuat detail pesanan</p>
                        <p class="text-xs mt-1">${error.message}</p>
                    </div>
                `;
            });
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
        
        const start = (current - 1) * 15 + 1;
        const end = Math.min(current * 15, total);
        info.innerHTML = `Menampilkan ${start} - ${end} dari ${total} data`;
        
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
    
    function goToPage(page) { currentPage = page; loadOrders(); }
    function filterOrders() { currentPage = 1; loadOrders(); }
    function resetFilter() {
        document.getElementById('filterMonth').value = '';
        document.getElementById('filterYear').value = '';
        currentPage = 1;
        loadOrders();
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
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Detail Pesanan - PizzaAnna</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; font-size: 12px; }
                    @media print { body { margin: 0; padding: 15px; } }
                    .bg-white { background: white; }
                    .rounded-xl { border-radius: 0.75rem; }
                    .border { border: 1px solid #e5e7eb; }
                    .p-4 { padding: 1rem; }
                    .mb-3 { margin-bottom: 0.75rem; }
                    .text-center { text-align: center; }
                    .flex { display: flex; }
                    .justify-between { justify-content: space-between; }
                    .font-bold { font-weight: bold; }
                    .text-2xl { font-size: 1.5rem; }
                    .text-gray-400 { color: #9ca3af; }
                    .text-gray-500 { color: #6b7280; }
                    .text-gray-800 { color: #1f2937; }
                    .text-[#D73535] { color: #D73535; }
                    .grid { display: grid; }
                    .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                    .gap-4 { gap: 1rem; }
                </style>
            </head>
            <body>
                ${modalContent}
            </body>
            </html>
        `);
        printWindow.print();
        printWindow.close();
    }
    
    function exportToExcel() {
        const month = document.getElementById('filterMonth').value;
        const year = document.getElementById('filterYear').value;
        let url = '/api/admin/orders/export/excel';
        let params = [];
        if (month) params.push('month=' + month);
        if (year) params.push('year=' + year);
        if (params.length > 0) url = url + '?' + params.join('&');
        window.location.href = url;
    }

    function exportToPDF() {
        const month = document.getElementById('filterMonth').value;
        const year = document.getElementById('filterYear').value;
        let url = '/api/admin/orders/export/pdf';
        let params = [];
        if (month) params.push('month=' + month);
        if (year) params.push('year=' + year);
        if (params.length > 0) url = url + '?' + params.join('&');
        window.location.href = url;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Halaman riwayat pesanan dimuat");
        loadOrders();
    });
</script>
@endsection