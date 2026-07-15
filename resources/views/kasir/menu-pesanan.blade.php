<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>PizzaAnna - Kasir</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        .category-active {
            background: #D73535 !important;
            color: white !important;
        }
        .menu-card {
            transition: 0.3s;
        }
        .menu-card:hover {
            transform: translateY(-5px);
        }
        .order-type-btn.active {
            background-color: #D73535 !important;
            color: white !important;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .modal-struk {
            animation: fadeIn 0.2s ease-out;
        }
        .custom-select {
            position: relative;
            width: 100%;
        }
        .custom-select select {
            appearance: none;
            -webkit-appearance: none;
            width: 100%;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: white;
            cursor: pointer;
        }
        .custom-select select:focus {
            outline: none;
            border-color: #D73535;
        }
        .custom-select .select-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #9ca3af;
        }
        .cart-items-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .cart-items-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .cart-items-scroll::-webkit-scrollbar-thumb {
            background: #D73535;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-gray-100">

<!-- NAVBAR -->
<nav class="bg-white shadow sticky top-0 z-40">
    <div class="container mx-auto px-4 py-2 flex justify-between items-center">

        <!-- LOGO (diperbaiki) -->
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 shadow-md">
                <img src="{{ asset('images/logo-pizzaanna.jpeg') }}" 
                     alt="Logo PizzaAnna" 
                     class="w-full h-full object-cover"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\"bi bi-pizza text-white text-sm bg-[#D73535] w-8 h-8 rounded-full flex items-center justify-center\"></i>'">
            </div>
            <div>
                <h1 class="font-bold text-lg">Pizz<span class="text-[#D73535]">Anna</span></h1>
                <p class="text-xs text-gray-500">Kasir Panel</p>
            </div>
        </div>

        <!-- MENU NAVIGASI -->
        <div class="flex items-center gap-3">
            <!-- Tombol Riwayat Pesanan -->
            <a href="{{ route('kasir.riwayat-pesanan') }}" class="bg-gray-100 p-2 rounded-full hover:bg-gray-200 transition" title="Riwayat Pesanan">
                <i class="bi bi-clock-history text-lg"></i>
            </a>

            <!-- Tombol Keranjang -->
            <button onclick="toggleCart()" class="relative bg-gray-100 p-2 rounded-full">
                <i class="bi bi-cart text-lg"></i>
                <span id="cartCount" class="absolute -top-1 -right-1 bg-[#D73535] text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">0</span>
            </button>

            <!-- Tombol Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-gray-100 p-2 rounded-full">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>

    </div>
</nav>

<!-- HERO -->
<section class="bg-gradient-to-r from-[#D73535] to-red-700 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-6 items-center">
            <div>
                <h1 class="text-3xl font-black">HOT FRESH <span class="text-yellow-300">PIZZA</span></h1>
                <p class="mt-1 text-white/80 text-sm">Pizza lezat dengan berbagai ukuran dan topping terbaik</p>
                <button onclick="scrollToMenu()" class="mt-3 bg-yellow-400 text-black px-5 py-2 rounded-lg font-bold text-sm flex items-center gap-1">
                    <i class="bi bi-cart3"></i> ORDER NOW
                </button>
            </div>
            <div>
                <img src="https://pngimg.com/uploads/pizza/pizza_PNG44095.png" class="w-full max-w-xs mx-auto">
            </div>
        </div>
    </div>
</section>

<!-- MENU SECTION -->
<section id="menuSection" class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold">Menu PizzAnna</h2>
            <p class="text-gray-500 text-sm mt-1">Pilih menu favorit customer</p>
        </div>

        <!-- CATEGORY BUTTONS -->
        <div id="categoryContainer" class="flex flex-wrap justify-center gap-2 mb-6">Loading...</div>

        <!-- MENU GRID - CARD GAMBAR FULL -->
        <div id="menuGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <div class="col-span-full text-center py-10">
                <i class="bi bi-hourglass-split text-2xl animate-spin text-[#D73535]"></i>
                <p class="text-gray-400 text-sm mt-2">Memuat menu...</p>
            </div>
        </div>
    </div>
</section>

<!-- CART SIDEBAR -->
<div id="cartSidebar" class="fixed top-0 right-0 h-full w-full sm:w-80 bg-white shadow-2xl z-50 transform translate-x-full transition duration-300">

    <div class="flex flex-col h-full">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 text-white py-2.5 px-4 flex justify-between items-center">
            <h2 class="font-bold text-base flex items-center gap-1"><i class="bi bi-receipt"></i> Detail Pesanan</h2>
            <button onclick="toggleCart()"><i class="bi bi-x-lg"></i></button>
        </div>

        <!-- FORM -->
        <div class="px-4 py-2 border-b space-y-2">

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-xs text-gray-500 flex items-center gap-1"><i class="bi bi-person"></i> Customer</label>
                    <input type="text" id="customerName" placeholder="Nama" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500 flex items-center gap-1"><i class="bi bi-upc-scan"></i> No Invoice</label>
                    <p id="invoiceNumber" class="font-mono text-xs font-semibold bg-gray-50 p-1.5 rounded-lg">INV-...</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-xs text-gray-500 flex items-center gap-1"><i class="bi bi-calendar"></i> Tanggal</label>
                    <p id="dateTimeDisplay" class="text-xs bg-gray-50 p-1.5 rounded-lg">-</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 mb-1 block flex items-center gap-1"><i class="bi bi-truck"></i> Tipe</label>
                    <div class="flex gap-1">
                        <button onclick="setOrderType('take_away')" id="btnTakeAway" class="order-type-btn flex-1 py-1.5 rounded-lg text-xs font-medium bg-[#D73535] text-white flex items-center justify-center gap-1"><i class=""></i> Take Away</button>
                        <button onclick="setOrderType('dine_in')" id="btnDineIn" class="order-type-btn flex-1 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 flex items-center justify-center gap-1"><i class=""></i> Dine In</button>
                    </div>
                </div>
            </div>

            <div id="mejaContainer" style="display: none;">
                <label class="text-xs text-gray-500 flex items-center gap-1"><i class="bi bi-table"></i> No Meja</label>
                <input type="number" id="noMeja" class="w-full border rounded-lg px-2 py-1.5 text-sm" min="1" value="1">
            </div>

        </div>

        <!-- CART ITEMS -->
        <div id="cartItems" class="flex-1 overflow-y-auto px-3 py-2 space-y-1.5 cart-items-scroll">
            <div class="text-center text-gray-400 py-6 text-sm"><i class="bi bi-cart-x"></i> Cart kosong</div>
        </div>

        <!-- TOTAL & PEMBAYARAN -->
        <div class="border-t px-4 py-2 bg-gray-50">
            
            <!-- Metode Pembayaran -->
            <div class="mb-2">
                <label class="text-xs text-gray-500 mb-1 block flex items-center gap-1"><i class="bi bi-credit-card"></i> Metode Bayar</label>
                <div class="custom-select">
                    <select id="paymentMethod" class="w-full text-sm py-1.5 px-2 border rounded-lg bg-white">
                        <option value="tunai"><i class="bi bi-cash"></i> Tunai</option>
                        <option value="qris"><i class="bi bi-qr-code"></i> QRIS</option>
                    </select>
                    <i class="bi bi-chevron-down select-icon"></i>
                </div>
            </div>

            <!-- Total -->
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600 font-medium text-sm flex items-center gap-1"><i class="bi bi-calculator"></i> Total</span>
                <span id="total" class="font-bold text-[#D73535] text-xl">Rp 0</span>
            </div>

            <!-- Tombol Bayar -->
            <button onclick="processOrder()" class="w-full bg-gradient-to-r from-[#D73535] to-red-700 text-white py-2 rounded-lg font-bold text-sm shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                <i class="bi bi-cash-stack"></i> BAYAR
            </button>
        </div>

    </div>
</div>

<!-- MODAL STRUK -->
<div id="strukModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl modal-struk">
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 text-white p-4 rounded-t-2xl text-center">
            <i class="bi bi-pizza text-2xl"></i>
            <h2 class="text-xl font-bold">PizzAnna</h2>
            <p class="text-xs opacity-90">Jl. Pengging, Boyolali</p>
        </div>
        <div class="p-5" id="strukContent"></div>  <!-- PASTIKAN ID INI ADA -->
        <div class="border-t p-4 flex gap-3">
            <button onclick="cetakPDF()" class="flex-1 bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition font-semibold flex items-center justify-center gap-1">
                <i class="bi bi-file-pdf"></i> Struk
            </button>
            <button onclick="closeStrukAndReset()" class="flex-1 bg-[#D73535] text-white py-2 rounded-xl hover:bg-red-700 transition font-semibold flex items-center justify-center gap-1">
                <i class="bi bi-check-lg"></i> OK
            </button>
            <button onclick="closeStrukAndReset()" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-xl hover:bg-gray-300 transition flex items-center justify-center gap-1">
                <i class="bi bi-x-lg"></i> Tutup
            </button>
        </div>
    </div>
</div>

<!-- MODAL PIZZA CUSTOM -->
<div id="pizzaModal"
    class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm p-4">

    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-fadeIn">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-[#D73535] to-red-500 p-5 text-white relative">

            <button
                onclick="closePizzaModal()"
                class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 transition flex items-center justify-center"
            >
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="flex items-center gap-3">

                <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-2xl">
                    <i class="bi bi-pizza"></i>
                </div>

                <div>
                    <h2 id="pizzaName" class="text-2xl font-bold">
                        Favorite Pizza
                    </h2>

                    <p class="text-sm text-red-100">
                        Pilih topping favoritmu 
                    </p>
                </div>

            </div>

        </div>

        <!-- BODY -->
        <div class="p-5 space-y-5 max-h-[75vh] overflow-y-auto">
            <!-- TOPPING -->
            <div>

                <h3 class="font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <i class=""></i>
                    Extra Topping
                </h3>

                <div
                    id="toppingContainer"
                    class="space-y-3"
                >

                </div>

            </div>

            <!-- QUANTITY -->
            <div>

                <h3 class="font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="bi bi-basket"></i>
                    Quantity
                </h3>

                <div class="flex items-center justify-center gap-4">

                    <button
                        onclick="changePizzaQty(-1)"
                        class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center justify-center"
                    >
                        <i class="bi bi-dash-lg"></i>
                    </button>

                    <input
                        type="number"
                        id="pizzaQty"
                        value="1"
                        min="1"
                        class="w-20 text-center border rounded-xl py-2 font-bold"
                    >

                    <button
                        onclick="changePizzaQty(1)"
                        class="w-10 h-10 rounded-full bg-red-500 hover:bg-red-600 text-white transition flex items-center justify-center"
                    >
                        <i class="bi bi-plus-lg"></i>
                    </button>

                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="border-t p-5 bg-gray-50">

            <div class="flex justify-between items-center mb-4">

                <div>
                    <p class="text-sm text-gray-500">
                        Total Harga
                    </p>

                    <h2 class="text-2xl font-extrabold text-[#D73535]">
                        Rp <span id="pizzaTotal">0</span>
                    </h2>
                </div>

                <button
                    onclick="confirmPizza()"
                    class="bg-[#D73535] hover:bg-red-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:scale-105 transition flex items-center gap-2"
                >
                    <i class="bi bi-cart-plus"></i>
                    Tambah ke Cart
                </button>

            </div>

        </div>

    </div>

</div>

<script>

let cart = [];
let activeCategory = "all";
let currentOrderType = "take_away";
let invoiceCounter = 1;

let selectedPizza = null;
let basePizzaPrice = 0;
let toppingsData = [];
let toppings = [];


function cetakPDF() {
    // Simpan konten asli body
    const originalBody = document.body.innerHTML;
    
    // Ambil konten struk modal
    const strukModal = document.getElementById("strukModal");
    const strukContent = document.getElementById("strukContent").cloneNode(true);
    
    // Buat container untuk print
    const printContent = `
        <div style="padding: 20px; font-family: 'Courier New', monospace; max-width: 350px; margin: 0 auto;">
            <div class="header" style="text-align: center; border-bottom: 1px dashed #ccc; padding-bottom: 10px; margin-bottom: 10px;">
                <h2 style="color: #D73535; margin: 0;">PIZZAANNA</h2>
                <p style="margin: 5px 0; font-size: 11px;">Jl. Pengging, Boyoladi</p>
            </div>
            ${strukContent.innerHTML}
            <div style="text-align: center; font-size: 9px; color: #999; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #ccc;">
                <p>Terima kasih atas kunjungan Anda!</p>
                <p>❤️ PizzaAnna - Pizza Lezat untuk Semua ❤️</p>
            </div>
        </div>
    `;
    
    // Ganti body dengan konten print
    document.body.innerHTML = printContent;
    
    // Print
    window.print();
    
    // Kembalikan konten asli setelah print selesai
    window.onafterprint = function() {
        location.reload(); // Refresh halaman untuk mengembalikan semua fungsi
    };
}

function changePizzaQty(delta)
{
    let qtyInput = document.getElementById("pizzaQty");

    let current = parseInt(qtyInput.value);

    current += delta;

    if (current < 1) current = 1;

    qtyInput.value = current;

    calculatePizzaTotal();
}

function calculatePizzaTotal() {
    let qty = parseInt(document.getElementById("pizzaQty").value) || 1;
    let toppingTotal = 0;

    document.querySelectorAll(".topping:checked").forEach(t => {
        toppingTotal += parseInt(t.dataset.price);
    });

    let total = (basePizzaPrice + toppingTotal) * qty;
    document.getElementById("pizzaTotal").innerText = formatRupiah(total);
}

function formatRupiah(number) {
    return new Intl.NumberFormat("id-ID").format(number);
}
function loadToppings(ukuran)
{
    fetch(`/kasir/toppings?ukuran=${ukuran}`)

        .then(res => res.json())

        .then(res => {

            let html = '';

            res.data.forEach(item => {

                html += `
                    <label class="flex items-center justify-between border rounded-xl p-2 cursor-pointer">

                        <div class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                class="topping"
                                value="${item.id_topping}"
                                data-name="${item.nama_topping}"
                                data-price="${item.harga}"
                                onchange="calculatePizzaTotal()"
                            >

                            <span class="text-sm">
                                ${item.nama_topping}
                            </span>

                        </div>

                        <span class="text-xs font-bold text-red-600">
                            Rp ${formatRupiah(item.harga)}
                        </span>

                    </label>
                `;
            });

            document.getElementById("toppingContainer")
                .innerHTML = html;

            calculatePizzaTotal();
        });
}
function renderToppings() {
    let html = '';

    toppingsData.forEach(t => {
        html += `
            <label class="flex items-center gap-2 text-sm mb-1">
                <input type="checkbox"
                    class="topping"
                    value="${t.id_topping}"
                    data-name="${t.nama_topping}"
                    data-price="${t.harga}">
                <span>
                    ${t.nama_topping} (${t.ukuran}) - Rp ${formatRupiah(t.harga)}
                </span>
            </label>
        `;
    });

    document.getElementById("toppingContainer").innerHTML = html;
}
function updateDateTime() {
    const now = new Date();
    const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    const dayName = days[now.getDay()];
    const date = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    const dateTimeString = `${dayName.substring(0,3)}, ${date}/${month.substring(0,3)}/${year} ${hours}:${minutes}`;
    document.getElementById("dateTimeDisplay").innerText = dateTimeString;
    return { dateTimeString };
}

function generateInvoiceNumber() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const sequence = String(invoiceCounter).padStart(4, '0');
    const invoiceNumber = `INV-${year}${month}${day}-${sequence}`;
    document.getElementById("invoiceNumber").innerText = invoiceNumber;
    invoiceCounter++;
    return invoiceNumber;
}

function setOrderType(type) {
    currentOrderType = type;
    const btnTakeAway = document.getElementById("btnTakeAway");
    const btnDineIn = document.getElementById("btnDineIn");
    const mejaContainer = document.getElementById("mejaContainer");
    if (type === "take_away") {
        btnTakeAway.classList.add("bg-[#D73535]", "text-white");
        btnTakeAway.classList.remove("bg-gray-100", "text-gray-700");
        btnDineIn.classList.add("bg-gray-100", "text-gray-700");
        btnDineIn.classList.remove("bg-[#D73535]", "text-white");
        mejaContainer.style.display = "none";
    } else {
        btnDineIn.classList.add("bg-[#D73535]", "text-white");
        btnDineIn.classList.remove("bg-gray-100", "text-gray-700");
        btnTakeAway.classList.add("bg-gray-100", "text-gray-700");
        btnTakeAway.classList.remove("bg-[#D73535]", "text-white");
        mejaContainer.style.display = "block";
    }
}

// ==================== LOAD KATEGORI ====================
function loadCategories() {
    fetch("/api/kategori")
        .then(response => response.json())
        .then(data => {
            let html = `<button onclick="filterCategory('all')" class="category-btn category-active px-3 py-1.5 rounded-full bg-white shadow text-xs flex items-center gap-1" data-category="all">
                            <i class="bi bi-grid"></i> Semua
                        </button>`;
            
            data.forEach(kategori => {
                html += `<button onclick="filterCategory('${kategori.id_kategori}')" class="category-btn px-3 py-1.5 rounded-full bg-white shadow text-xs flex items-center gap-1" data-category="${kategori.id_kategori}">
                            <i class="bi bi-tag"></i> ${kategori.nama_kategori}
                        </button>`;
            });
            
            document.getElementById("categoryContainer").innerHTML = html;
        })
        .catch(error => {
            console.error("Error loading categories:", error);
        });
}

// ==================== FILTER KATEGORI ====================
function filterCategory(category) {
    activeCategory = category;
    
    // Update active class pada button
    document.querySelectorAll(".category-btn").forEach(btn => {
        btn.classList.remove("category-active");
        if (btn.dataset.category == category) {
            btn.classList.add("category-active");
        }
    });
    
    // Load menu berdasarkan kategori
    loadMenu();
}

// ==================== LOAD MENU ====================
function loadMenu() {
    // Tentukan URL berdasarkan kategori aktif
    let url = activeCategory === "all" 
        ? "/api/menu" 
        : `/api/menu/category/${activeCategory}`;
    
    fetch(url)
        .then(response => response.json())
        .then(menus => {
            let html = "";
            
            menus.forEach(menu => {
                const gambarUrl = menu.gambar ? `/storage/${menu.gambar}` : null;
                let ukuranHtml = "";
                
                // KHUSUS PIZZA (id_kategori = 1)
           // CEK STOK
const stok = menu.stok_menu || 0;

// TAMPILAN STOK
let stokHtml = '';
if (stok > 0) {
    stokHtml = `
        <div class="mt-1 flex items-center gap-1">
            <span class="text-xs ${stok <= 3 ? 'text-red-500 font-bold' : 'text-gray-500'}">
                <i class=""></i> Stok: ${stok}
                ${stok <= 3 ? '<span class="text-red-500">⚠️ Menipis!</span>' : ''}
            </span>
        </div>
    `;
} else {
    stokHtml = `
        <div class="mt-1 text-red-500 font-bold text-xs">
            <i class="bi bi-x-circle"></i> HABIS
        </div>
    `;
}

// KHUSUS PIZZA (id_kategori = 1)
if (menu.id_kategori == 1 && menu.pizza_ukuran && menu.pizza_ukuran.length > 0) {
    ukuranHtml = `<div class="mt-2 space-y-1">`;
    
    menu.pizza_ukuran.forEach(ukuran => {
        // CEK STOK UNTUK PIZZA (pakai stok menu)
        if (stok > 0) {
            ukuranHtml += `
                <button onclick='openPizzaModal(
                    ${menu.id_menu},
                    "${menu.nama_menu.replace(/"/g, '&quot;')}",
                    ${ukuran.harga},
                    "${ukuran.ukuran}"
                )' class="w-full flex justify-between items-center bg-[#D73535] text-white hover:bg-red-700 text-xs px-2 py-1.5 rounded-lg">
                    <span><i class="bi bi-pizza"></i> Ukuran ${ukuran.ukuran}</span>
                    <span class="font-bold">Rp ${formatRupiah(ukuran.harga)}</span>
                </button>
            `;
        } else {
            ukuranHtml += `
                <button class="w-full flex justify-between items-center bg-gray-300 text-gray-500 text-xs px-2 py-1.5 rounded-lg cursor-not-allowed" disabled>
                    <span><i class="bi bi-pizza"></i> Ukuran ${ukuran.ukuran}</span>
                    <span class="font-bold">❌ HABIS</span>
                </button>
            `;
        }
    });
    
    ukuranHtml += `</div>`;
} else {
    // MENU NON-PIZZA
    if (stok > 0) {
        ukuranHtml = `
            <div class="mt-2 flex justify-between items-center">
                <span class="font-bold text-[#D73535] text-sm">
                    Rp ${formatRupiah(menu.harga)}
                </span>
                <button onclick='addToCart(
                    ${menu.id_menu},
                    "${menu.nama_menu.replace(/"/g, '&quot;')}",
                    ${menu.harga}
                )' class="bg-[#D73535] text-white px-2 py-1 rounded-lg text-xs flex items-center gap-1 hover:bg-red-700 transition">
                    <i class="bi bi-plus-lg"></i> Tambah
                </button>
            </div>
        `;
    } else {
        ukuranHtml = `
            <div class="mt-2 flex justify-between items-center">
                <span class="font-bold text-[#D73535] text-sm">
                    Rp ${formatRupiah(menu.harga)}
                </span>
                <button class="bg-gray-300 text-gray-500 px-2 py-1 rounded-lg text-xs cursor-not-allowed" disabled>
                    <i class="bi bi-x-circle"></i> Habis
                </button>
            </div>
        `;
    }
}
                
html += `
    <div class="menu-card bg-white rounded-xl overflow-hidden shadow hover:shadow-lg transition-all duration-300">
        <div class="h-36 w-full overflow-hidden bg-gray-100">
            ${gambarUrl ? `
                <img src="${gambarUrl}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
            ` : `
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                    <i class="bi bi-image text-4xl text-gray-300"></i>
                </div>
            `}
        </div>
        <div class="p-3">
            <h3 class="font-bold text-sm">${menu.nama_menu}</h3>
            <p class="text-gray-500 text-xs mt-1 line-clamp-2">
                ${menu.deskripsi ? menu.deskripsi.substring(0, 60) : "Menu lezat dari PizzaAnna"}
            </p>
            ${stokHtml}
            ${ukuranHtml}
        </div>
    </div>
`;
            });
            
            document.getElementById("menuGrid").innerHTML = html;
        })
        .catch(error => {
            console.error("Error loading menu:", error);
            document.getElementById("menuGrid").innerHTML = `
                <div class="col-span-full text-center py-10 text-red-500">
                    <i class="bi bi-wifi-off text-3xl"></i>
                    <p class="mt-2">Error loading menu. Please refresh.</p>
                </div>
            `;
        });
}

function confirmPizza() {
    const qty = parseInt(document.getElementById("pizzaQty").value || 1);
    
    fetch(`/api/menu/${selectedPizza.id}`)
        .then(res => res.json())
        .then(menu => {
            if (menu.stok_menu < qty) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Cukup!',
                    text: 'Stok "' + selectedPizza.name + '" tersisa ' + menu.stok_menu + ' porsi.',
                    confirmButtonColor: '#D73535'
                });
                return;
            }
            
            // ✅ PAKAI VARIABLE LOKAL (BUKAN GLOBAL)
            let selectedToppings = [];
            let toppingTotal = 0;
            
            document.querySelectorAll(".topping:checked").forEach(t => {
                selectedToppings.push({
                    id_topping: parseInt(t.value),
                    nama: t.dataset.name,
                    harga: parseInt(t.dataset.price)
                });
                toppingTotal += parseInt(t.dataset.price);
            });
            
            let finalPrice = (basePizzaPrice + toppingTotal) * qty;
            let toppingIds = selectedToppings.map(t => t.id_topping);
            
            // Cek apakah pizza dengan spesifikasi sama sudah ada di cart
            let found = false;
            for (let i = 0; i < cart.length; i++) {
                if (cart[i].id_menu === selectedPizza.id && 
                    cart[i].ukuran === selectedSize &&
                    JSON.stringify(cart[i].topping_ids || []) === JSON.stringify(toppingIds)) {
                    
                    cart[i].qty += qty;
                    cart[i].harga = (cart[i].harga_satuan + cart[i].topping_total) * cart[i].qty;
                    found = true;
                    console.log("Pizza ditemukan, quantity menjadi:", cart[i].qty);
                    break;
                }
            }
            
            if (!found) {
                cart.push({
                    id_menu: selectedPizza.id,
                    name: selectedPizza.name,
                    harga: finalPrice,
                    harga_satuan: basePizzaPrice,
                    qty: qty,
                    ukuran: selectedSize,
                    topping: selectedToppings,
                    topping_ids: toppingIds,
                    topping_total: toppingTotal,
                    custom: true
                });
                console.log("Pizza baru ditambahkan");
            }
            
            updateCartUI();
            closePizzaModal();
        })
        .catch(err => console.error('Error cek stok pizza:', err));
}

function openPizzaModal(id, name, price, ukuran)
{
    selectedPizza = {
        id,
        name
    };

selectedSize = ukuran;
    basePizzaPrice = price;

    document.getElementById("pizzaName").innerText =
        `${name} (${ukuran})`;

    document.getElementById("pizzaQty").value = 1;

    loadToppings(ukuran);

    document.getElementById("pizzaModal")
        .classList.remove("hidden");

    document.getElementById("pizzaModal")
        .classList.add("flex");
}

function closePizzaModal() {
    document.getElementById("pizzaModal").classList.add("hidden");
    document.getElementById("pizzaModal").classList.remove("flex");
}

function addToCart(id, name, price) {
    console.log("Menambah ke cart:", id, name, price);
    
    fetch(`/api/menu/${id}`)
        .then(res => res.json())
        .then(menu => {
            if (menu.stok_menu <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Habis!',
                    text: 'Menu "' + name + '" sedang habis. Silakan pilih menu lain.',
                    confirmButtonColor: '#D73535'
                });
                return;
            }
            
            // Cari apakah item sudah ada di cart
            let found = false;
            for (let i = 0; i < cart.length; i++) {
                if (cart[i].id_menu === id && !cart[i].ukuran) {
                    cart[i].qty++;
                    cart[i].harga = cart[i].harga_satuan * cart[i].qty;
                    found = true;
                    console.log("Item ditemukan, quantity menjadi:", cart[i].qty);
                    break;
                }
            }
            
            if (!found) {
                cart.push({
                    id_menu: id,
                    name: name,
                    harga: price,
                    harga_satuan: price,
                    qty: 1,
                    ukuran: null,
                    topping: [],
                    topping_ids: [],
                    custom: false
                });
                console.log("Item baru ditambahkan");
            }
            
            updateCartUI();
        })
        .catch(err => console.error('Error cek stok:', err));
}
function removeCart(index) {
    cart.splice(index, 1);
    updateCartUI();
}
function changeQuantity(index, delta) {
    const newQty = cart[index].qty + delta;
    if (newQty <= 0) {
        removeCart(index);
    } else {
        cart[index].qty = newQty;
        updateCartUI();
    }
}

function updateCartUI() {
    const container = document.getElementById("cartItems");
    let html = "";
    let total = 0;
    
    if (cart.length === 0) {
        container.innerHTML = `<div class="text-center text-gray-400 py-6 text-sm"><i class="bi bi-cart-x"></i> Cart kosong</div>`;
        document.getElementById("total").innerHTML = "Rp 0";
        document.getElementById("cartCount").innerText = "0";
        return;
    }
    
    for (let i = 0; i < cart.length; i++) {
        const item = cart[i];
        const subtotal = item.harga;
        total += subtotal;
        
        let itemName = item.name;
        if (item.ukuran) {
            itemName += ` (${item.ukuran})`;
        }
        
        html += `
            <div class="bg-white border rounded-lg p-2 mb-2">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 text-sm">
                            ${itemName} x${item.qty}
                        </h3>
                        ${item.topping && item.topping.length > 0 ? `
                            <div class="mt-1 space-y-0.5">
                                ${item.topping.map(t => `<p class="text-[10px] text-gray-500">+ ${t.nama}</p>`).join('')}
                            </div>
                        ` : ''}
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-[#D73535] text-sm">Rp ${formatRupiah(subtotal)}</p>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-2 pt-1 border-t">
                    <button onclick="changeQuantity(${i}, -1)" class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-6 h-6 rounded-full flex items-center justify-center transition">
                        <i class="bi bi-dash-lg text-xs"></i>
                    </button>
                    <span class="font-semibold text-sm min-w-[30px] text-center">${item.qty}</span>
                    <button onclick="changeQuantity(${i}, 1)" class="bg-[#D73535] hover:bg-red-700 text-white w-6 h-6 rounded-full flex items-center justify-center transition">
                        <i class="bi bi-plus-lg text-xs"></i>
                    </button>
                    <button onclick="removeCart(${i})" class="text-red-500 hover:text-red-700 text-xs flex items-center gap-1 ml-2">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
            </div>
        `;
    }
    
    container.innerHTML = html;
    document.getElementById("total").innerHTML = `Rp ${formatRupiah(total)}`;
    document.getElementById("cartCount").innerText = cart.reduce((sum, item) => sum + item.qty, 0);
}
function toggleCart() {
    const sidebar = document.getElementById("cartSidebar");
    sidebar.classList.toggle("translate-x-full");
    if (!sidebar.classList.contains("translate-x-full")) { generateInvoiceNumber(); updateDateTime(); }
}

function resetCartAndForm() {
    cart = []; updateCartUI();
    document.getElementById("customerName").value = "";
    document.getElementById("noMeja").value = "1";
    document.getElementById("paymentMethod").value = "tunai";
    setOrderType("take_away");
    generateInvoiceNumber(); updateDateTime();
    document.getElementById("cartSidebar").classList.add("translate-x-full");
}

function showStrukModal(orderData, total, customerName, invoiceNumber, noMeja, paymentMethod) {
    const datetime = updateDateTime();
    
    // Cek apakah elemen modal ada
    if (!document.getElementById("strukContent")) {
        console.error("Element 'strukContent' tidak ditemukan!");
        alert("Error: Modal struk tidak tersedia. Silakan refresh halaman.");
        return;
    }
    
    if (!document.getElementById("strukModal")) {
        console.error("Element 'strukModal' tidak ditemukan!");
        alert("Error: Modal tidak tersedia. Silakan refresh halaman.");
        return;
    }
    
    window.currentOrderData = orderData;
    window.currentTotal = total;
    window.currentCustomerName = customerName;
    window.currentInvoiceNumber = invoiceNumber;
    window.currentNoMeja = noMeja;
    window.currentPaymentMethod = paymentMethod;
    window.currentOrderType = currentOrderType;

    let itemsHtml = "";
    
    // PERBAIKAN: Tampilkan item beserta topping
    if (orderData.items && orderData.items.length > 0) {
        orderData.items.forEach(item => { 
            // Hitung subtotal item (harga * qty)
            const subtotal = item.harga * item.qty;
            
            // Mulai dengan nama item dan ukuran jika ada
            let itemDisplay = `${item.name}`;
            if (item.ukuran) {
                itemDisplay += ` (${item.ukuran})`;
            }
            
            itemsHtml += `<div class="flex justify-between text-xs py-0.5">
                <span><i class="bi bi-dot"></i> ${itemDisplay} x${item.qty}</span>
                <span>Rp ${formatRupiah(subtotal)}</span>
            </div>`;
            
            // TAMPILKAN TOPPING jika ada
            if (item.topping && item.topping.length > 0) {
                item.topping.forEach(topping => {
                    itemsHtml += `<div class="flex justify-between text-xs py-0.5 pl-4">
                        <span class="text-gray-500"><i class="bi bi-plus-circle"></i> + ${topping.nama}</span>
                        <span class="text-gray-500">Rp ${formatRupiah(topping.harga * item.qty)}</span>
                    </div>`;
                });
            }
        });
    } else {
        itemsHtml = '<div class="text-center text-xs text-gray-400">Tidak ada item</div>';
    }
    
    const metodeBayarText = paymentMethod === "tunai" ? "Tunai" : "QRIS";
    const metodeBayarIcon = paymentMethod === "tunai" ? "cash" : "qr-code";
    
    const strukHtml = `
        <div class="text-center border-b pb-2 mb-2">
            <p class="text-xs text-gray-500"><i class="bi bi-clock"></i> ${datetime.dateTimeString}</p>
            <p class="text-sm font-bold font-mono"><i class="bi bi-upc-scan"></i> ${invoiceNumber}</p>
        </div>
        <div class="space-y-1 mb-2">
            <div class="flex justify-between text-xs">
                <span class="text-gray-500"><i class="bi bi-person"></i> Customer:</span>
                <span class="font-semibold">${customerName}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-500"><i class="bi bi-truck"></i> Tipe:</span>
                <span class="font-semibold">${currentOrderType === "dine_in" ? "Dine In" : "Take Away"}</span>
            </div>
            ${currentOrderType === "dine_in" && noMeja ? `<div class="flex justify-between text-xs">
                <span class="text-gray-500"><i class="bi bi-table"></i> Meja:</span>
                <span class="font-semibold">${noMeja}</span>
            </div>` : ''}
        </div>
        <div class="border-t border-b py-2 mb-2 space-y-0.5">${itemsHtml}</div>
        <div class="space-y-1">
            <div class="flex justify-between font-bold">
                <span><i class="bi bi-calculator"></i> TOTAL</span>
                <span class="text-[#D73535]">Rp ${formatRupiah(total)}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-500"><i class="bi bi-credit-card"></i> Metode Bayar</span>
                <span><i class="bi bi-${metodeBayarIcon}"></i> ${metodeBayarText}</span>
            </div>
        </div>
        <div class="text-center text-xs text-gray-400 pt-2 border-t mt-2">
            <i class="bi bi-emoji-smile"></i> Terima kasih!
        </div>
    `;
    
    document.getElementById("strukContent").innerHTML = strukHtml;
    document.getElementById("strukModal").classList.remove("hidden");
    document.getElementById("strukModal").classList.add("flex");
}

function closeStrukAndReset() {
    document.getElementById("strukModal").classList.add("hidden");
    document.getElementById("strukModal").classList.remove("flex");
    resetCartAndForm();
}

function processOrder() {
    if (cart.length === 0) { alert("Cart kosong"); return; }
    const customerName = document.getElementById("customerName").value;
    if (customerName === "") { alert("Masukkan nama customer"); return; }
    let total = 0;
cart.forEach(item => { total += item.harga * item.qty; });    const invoiceNumber = document.getElementById("invoiceNumber").innerText;
    const noMeja = currentOrderType === "dine_in" ? document.getElementById("noMeja").value : null;
    const paymentMethod = document.getElementById("paymentMethod").value;
    const orderData = { customer_name: customerName, invoice_number: invoiceNumber, table_number: noMeja, order_type: currentOrderType, items: cart, total: total, payment_method: paymentMethod };
fetch('{{ route("kasir.store-pesanan") }}', {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify(orderData)
})    .then(response => response.json()).then(data => { if (data.success) showStrukModal(orderData, total, customerName, invoiceNumber, noMeja, paymentMethod); else alert("Error: " + data.message); })
    .catch(error => { console.error("Error:", error); alert("Terjadi kesalahan pada server"); });
}

function scrollToMenu() { document.getElementById("menuSection").scrollIntoView({ behavior: "smooth" }); }

document.addEventListener("DOMContentLoaded", () => { loadCategories(); loadMenu(); generateInvoiceNumber(); updateDateTime(); setInterval(updateDateTime, 1000); });

</script>

</body>
</html>