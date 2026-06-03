<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Riwayat Pesanan - PizzaAnna Kasir</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        .category-active {
            background: #D73535 !important;
            color: white !important;
        }
    </style>
</head>

<body class="bg-gray-100">

<!-- NAVBAR -->
<nav class="bg-white shadow sticky top-0 z-40">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">

        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-[#D73535] text-white flex items-center justify-center">
                <i class="bi bi-pizza text-sm"></i>
            </div>
            <div>
                <h1 class="font-bold text-xl">Pizza<span class="text-[#D73535]">Anna</span></h1>
                <p class="text-xs text-gray-500">Kasir Panel</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Menu Pesanan -->
            <a href="{{ route('kasir.menu-pesanan') }}" class="bg-gray-100 p-2.5 rounded-full hover:bg-gray-200 transition">
                <i class="bi bi-grid-3x3-gap-fill text-lg"></i>
            </a>

            <!-- Riwayat Pesanan (aktif) -->
            <a href="{{ route('kasir.riwayat-pesanan') }}" class="bg-[#D73535] text-white p-2.5 rounded-full transition">
                <i class="bi bi-clock-history text-lg"></i>
            </a>

            <!-- Cart -->
            <a href="{{ route('kasir.menu-pesanan') }}" class="relative bg-gray-100 p-2.5 rounded-full hover:bg-gray-200 transition">
                <i class="bi bi-cart text-lg"></i>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-gray-100 p-2.5 rounded-full hover:bg-gray-200 transition">
                    <i class="bi bi-box-arrow-right text-lg"></i>
                </button>
            </form>
        </div>

    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Pesanan</h1>
        <p class="text-gray-500">Lihat transaksi pesanan yang telah Anda proses</p>
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
                </select>
            </div>
            <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Customer</label>
    <div class="relative">
        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        <input type="text" id="searchCustomer" placeholder="Nama customer..." 
               class="pl-10 pr-4 py-2 border rounded-xl w-full focus:outline-none focus:ring-2 focus:ring-[#D73535]">
    </div>
</div>
          <button onclick="filterOrders()" class="bg-[#D73535] text-white px-6 py-2 rounded-xl hover:bg-red-700 transition">
    <i class="bi bi-search"></i> Filter
</button>
<button onclick="resetFilter()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-300 transition">
    Reset
</button>
            <div class="text-sm text-gray-500 mt-5" id="filterInfo"></div>
        </div>
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
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 shadow-2xl" onclick="event.stopPropagation()">
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 text-white p-5 rounded-t-2xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="bi bi-receipt text-2xl"></i>
                <h2 class="text-xl font-bold">Detail Pesanan</h2>
            </div>
            <button onclick="closeModal()" class="hover:bg-white/20 rounded-full p-2 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div class="p-6" id="modalContent">
            <div class="text-center py-8">
                <i class="bi bi-hourglass-split text-3xl animate-spin text-gray-400"></i>
                <p class="mt-2">Loading...</p>
            </div>
        </div>
        <div class="border-t p-4 flex justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300 transition">Tutup</button>
            <button onclick="printDetail()" class="px-4 py-2 bg-[#D73535] text-white rounded-xl hover:bg-red-700 transition">Cetak</button>
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
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return months[month - 1];
    }
    
    // ==================== LOAD ORDERS ====================
  function loadOrders() {
    const month = document.getElementById('filterMonth').value;
    const year = document.getElementById('filterYear').value;
    const search = document.getElementById('searchCustomer').value;
    
    let url = `/api/kasir/orders?page=${currentPage}`;
    if (month) url += `&month=${month}`;
    if (year) url += `&year=${year}`;
    if (search) url += `&search=${search}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            totalPages = data.last_page;
            renderTable(data.data);
            renderPagination(data.current_page, data.last_page, data.total);
            
            const monthName = month ? document.getElementById('filterMonth').options[document.getElementById('filterMonth').selectedIndex]?.text : '';
            if (month && year) {
                document.getElementById('filterInfo').innerHTML = `Menampilkan data: ${monthName} ${year} ${search ? ' - Cari: "' + search + '"' : ''}`;
            } else if (month) {
                document.getElementById('filterInfo').innerHTML = `Menampilkan data bulan: ${monthName} ${search ? ' - Cari: "' + search + '"' : ''}`;
            } else if (year) {
                document.getElementById('filterInfo').innerHTML = `Menampilkan data tahun: ${year} ${search ? ' - Cari: "' + search + '"' : ''}`;
            } else if (search) {
                document.getElementById('filterInfo').innerHTML = `Hasil pencarian: "${search}"`;
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
                 </td>
                </tr>
            `;
        });
}
    
    // ==================== RENDER TABLE ====================
    function renderTable(orders) {
        const tbody = document.getElementById('ordersTable');
        
        if (orders.length === 0) {
            tbody.innerHTML = `
                <tr><td colspan="8" class="text-center py-10 text-gray-400">
                    <i class="bi bi-inbox text-3xl"></i>
                    <p class="mt-2">Tidak ada data pesanan</p>
                </td></tr>
            `;
            return;
        }
        
        let html = '';
        orders.forEach((order, index) => {
            const date = new Date(order.tanggal);
            const formattedDate = `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
            
            let mejaText = 'Take Away';
            if (order.no_meja && order.no_meja !== null) {
                mejaText = `Meja ${order.no_meja}`;
            }
            
            html += `
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-600">${(currentPage - 1) * 15 + index + 1}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">${formattedDate}</td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-600">${order.no_invoice || '-'}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">${order.nama_customer}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">${mejaText}</td>
                    <td class="px-6 py-4 text-sm font-bold text-[#D73535] text-right">Rp ${formatRupiah(order.total_bayar)}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded-full text-xs ${order.metode_bayar === 'tunai' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'}">
                            ${order.metode_bayar === 'tunai' ? ' Tunai' : 'QRIS'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="showDetail(${order.id_pesanan})" class="text-blue-600 hover:text-blue-800 transition">
                            <i class="bi bi-eye text-xl"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }
    
    // ==================== SHOW DETAIL MODAL ====================
   // ==================== SHOW DETAIL MODAL ====================
function showDetail(orderId) {
    const modal = document.getElementById('detailModal');
    const modalContent = document.getElementById('modalContent');
    
    if (!modal) return;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    modalContent.innerHTML = `
        <div class="text-center py-8">
            <i class="bi bi-hourglass-split text-3xl animate-spin text-gray-400"></i>
            <p class="mt-2">Loading...</p>
        </div>
    `;
    
    fetch(`/api/kasir/orders/${orderId}`)
        .then(response => response.json())
        .then(order => {
            console.log('Order data:', order); // Debug: lihat data di console
            
            const date = new Date(order.tanggal);
            const formattedDate = `${date.getDate()} ${getMonthName(date.getMonth() + 1)} ${date.getFullYear()}, ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
            const metodeText = order.metode_bayar === 'tunai' ? ' Tunai' : 'QRIS';
            
            let mejaText = 'Take Away';
            if (order.no_meja && order.no_meja !== null) {
                mejaText = `Meja ${order.no_meja}`;
            }
            
            let itemsHtml = '';
            if (order.detail_pesanan && order.detail_pesanan.length > 0) {
                order.detail_pesanan.forEach(item => {
                    let menuName = item.menu?.nama_menu || 'Menu tidak ditemukan';
                    let ukuranText = '';
                    
                    // Tampilkan ukuran jika ada
                    if (item.ukuran) {
                        ukuranText = ` <span class="text-xs text-gray-400">(${item.ukuran})</span>`;
                    }
                    
                    itemsHtml += `
                        <div class="border-b pb-3 mb-2">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">
                                        ${menuName}${ukuranText}
                                    </p>
                                    <p class="text-sm text-gray-500">${item.jumlah} x Rp ${formatRupiah(item.harga_satuan)}</p>
                    `;
                    
                    // TAMPILKAN TOPPING jika ada
                    if (item.topping_list && item.topping_list.length > 0) {
                        itemsHtml += `<div class="mt-1 space-y-0.5 ml-2">`;
                        item.topping_list.forEach(topping => {
                            itemsHtml += `
                                <p class="text-xs text-gray-500 flex justify-between">
                                    <span><i class="bi bi-plus-circle"></i> + ${topping.nama}</span>
                                    <span>Rp ${formatRupiah(topping.harga)}</span>
                                </p>
                            `;
                        });
                        itemsHtml += `</div>`;
                    }
                    
                    itemsHtml += `
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-[#D73535]">Rp ${formatRupiah(item.subtotal)}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                itemsHtml = '<p class="text-gray-500 text-sm">Tidak ada detail menu</p>';
            }
            
            modalContent.innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 pb-3 border-b">
                        <div>
                            <p class="text-xs text-gray-500">No. Invoice</p>
                            <p class="font-semibold text-sm">${order.no_invoice || '-'}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal</p>
                            <p class="font-semibold text-sm">${formattedDate}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Customer</p>
                            <p class="font-semibold text-sm">${order.nama_customer}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Meja</p>
                            <p class="font-semibold text-sm">${mejaText}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Metode Pembayaran</p>
                            <p class="font-semibold text-sm">${metodeText}</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="font-semibold text-gray-700 mb-2 flex items-center gap-1">
                            <i class="bi bi-receipt"></i> Detail Menu
                        </p>
                        <div class="max-h-80 overflow-y-auto">
                            ${itemsHtml}
                        </div>
                    </div>
                    
                    <div class="border-t pt-3 flex justify-between items-center">
                        <p class="font-bold text-lg">TOTAL</p>
                        <p class="font-bold text-xl text-[#D73535]">Rp ${formatRupiah(order.total_bayar)}</p>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error("Error:", error);
            modalContent.innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <i class="bi bi-exclamation-triangle text-3xl"></i>
                    <p class="mt-2">Error loading detail</p>
                    <p class="text-xs mt-1">${error.message}</p>
                </div>
            `;
        });
}
    // ==================== PAGINATION ====================
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
    
    function goToPage(page) {
        currentPage = page;
        loadOrders();
    }
    
    // ==================== FILTER FUNCTIONS ====================
    function filterOrders() {
        currentPage = 1;
        loadOrders();
    }
    
  function resetFilter() {
    document.getElementById('filterMonth').value = '';
    document.getElementById('filterYear').value = '';
    document.getElementById('searchCustomer').value = '';
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
                <title>Detail Pesanan</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    @media print { body { margin: 0; padding: 15px; } }
                </style>
            </head>
            <body>${modalContent}</body>
            </html>
        `);
        printWindow.print();
        printWindow.close();
    }
    
    // ==================== INITIAL LOAD ====================
    document.addEventListener('DOMContentLoaded', function() {
        console.log("🚀 Halaman riwayat pesanan kasir dimuat");
        loadOrders();

        
    });
</script>
</body>
</html>