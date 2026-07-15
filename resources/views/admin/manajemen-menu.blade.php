@extends('layouts.app')

@section('title', 'Manajemen Menu')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Menu</h1>
        <p class="text-gray-500">Kelola daftar menu makanan dan minuman</p>
    </div>
    
    <!-- Tombol Tambah Menu -->
    <div class="mb-6">
        <button onclick="openAddModal()" class="bg-[#D73535] text-white px-6 py-2 rounded-xl hover:bg-red-700 transition inline-flex items-center gap-2 shadow-md">
            <i class="bi bi-plus-circle"></i> Tambah Menu Baru
        </button>
    </div>
    
    <!-- Filter & Pencarian -->
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter Kategori</label>
                <select id="filterKategori" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    <option value="all">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Menu</label>
                <input type="text" id="searchMenu" placeholder="Cari nama menu..." class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
            </div>
            <div class="flex items-end gap-2">
                <button onclick="filterMenu()" class="bg-[#D73535] text-white px-6 py-2 rounded-xl hover:bg-red-700 transition flex items-center gap-2">
                    <i class="bi bi-search"></i> Cari
                </button>
                <button onclick="resetFilter()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-300 transition">
                    Reset
                </button>
            </div>
        </div>
    </div>
    
    <!-- Tabel Menu -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">No</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Gambar</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama Menu</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Kategori</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Ukuran & Harga</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Diskon</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Deskripsi</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Stok</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody id="menuTable">
                    <tr><td colspan="9" class="text-center py-10 text-gray-400">Loading...</td></tr>
                </tbody>
            </table>
        </div>
        <div class="border-t px-6 py-4 flex justify-between items-center bg-gray-50">
            <div class="text-sm text-gray-500" id="paginationInfo"></div>
            <div class="flex gap-2" id="paginationButtons"></div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH/EDIT MENU -->
<div id="menuModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm" onclick="closeModal(event)">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 text-white p-5 rounded-t-2xl sticky top-0 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="bi bi-egg-fried text-2xl"></i>
                <h2 id="modalTitle" class="text-xl font-bold">Tambah Menu</h2>
            </div>
            <button onclick="closeModal()" class="hover:bg-white/20 rounded-full p-2 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <form id="menuForm" class="p-6" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="menuId" name="id">
            <input type="hidden" id="methodField" name="_method">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                <input type="text" id="namaMenu" name="nama_menu" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select id="idKategori" name="id_kategori" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- FORM UKURAN PIZZA -->
            <div id="ukuranContainer" class="mb-4" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran & Harga Pizza</label>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-16 font-bold text-gray-700">S (Small)</div>
                        <input type="number" id="hargaS" name="harga_s" placeholder="Harga S" class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-16 font-bold text-gray-700">M (Medium)</div>
                        <input type="number" id="hargaM" name="harga_m" placeholder="Harga M" class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-16 font-bold text-gray-700">L (Large)</div>
                        <input type="number" id="hargaL" name="harga_l" placeholder="Harga L" class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Isi harga untuk masing-masing ukuran pizza (S, M, L)</p>
            </div>
            
            <!-- Harga untuk Non-Pizza -->
            <div id="hargaContainer" class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" id="harga" name="harga" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                <p class="text-xs text-gray-500 mt-1">Isi harga untuk menu biasa (bukan pizza)</p>
            </div>
            
            <!-- ✅ STOK MENU -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Menu</label>
                <input type="number" id="stokMenu" name="stok_menu" value="0" min="0" 
                       class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                <p class="text-xs text-gray-400 mt-1">Jumlah porsi yang tersedia untuk hari ini</p>
            </div>
            
            <!-- Gambar -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Menu</label>
                <input type="file" id="gambar" name="gambar" accept="image/*" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                <div id="previewGambar" class="mt-2 hidden">
                    <img id="previewImg" class="w-20 h-20 object-cover rounded-lg border">
                </div>
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maks 2MB</p>
            </div>
            
            <!-- Diskon -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Diskon</label>
                <select id="diskonJenis" name="diskon_jenis" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    <option value="none">Tanpa Diskon</option>
                    <option value="persen">Diskon (%)</option>
                </select>
            </div>

            <div id="diskonNilaiWrapper" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Diskon (%)</label>
                <input type="number" id="diskonNilai" name="diskon_nilai" step="1" min="0" max="100" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]"></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-[#D73535] text-white py-2 rounded-xl hover:bg-red-700 transition font-semibold">Simpan</button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-xl hover:bg-gray-300 transition">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL NONAKTIFKAN -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal(event)">
    <div class="bg-white rounded-2xl w-full max-w-sm mx-4 shadow-2xl" onclick="event.stopPropagation()">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-5 rounded-t-2xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="bi bi-exclamation-triangle text-2xl"></i>
                <h2 class="text-xl font-bold">Nonaktifkan Menu</h2>
            </div>
            <button onclick="closeDeleteModal()" class="hover:bg-white/20 rounded-full p-2 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menonaktifkan menu <strong id="deleteMenuName"></strong>?</p>
            <div id="menuUsageInfo" class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-4 text-sm text-yellow-700 hidden">
                <i class="bi bi-info-circle"></i> <span id="menuUsageText"></span>
            </div>
            <div class="flex gap-3">
                <button onclick="confirmDelete()" class="flex-1 bg-[#D73535] text-white py-2 rounded-xl hover:bg-red-700 transition">Ya, Nonaktifkan</button>
                <button onclick="closeDeleteModal()" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-xl hover:bg-gray-300 transition">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let currentPage = 1;
    let deleteId = null;

    function formatRupiah(angka) {
        if (!angka && angka !== 0) return '0';
        return Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // ==================== LOAD MENU ====================
    function loadMenu() {
        const kategori = document.getElementById('filterKategori').value;
        const search = document.getElementById('searchMenu').value;
        let url = `/api/admin/menu?page=${currentPage}`;
        if (kategori !== 'all') url += `&kategori=${kategori}`;
        if (search) url += `&search=${search}`;
        
        fetch(url)
            .then(res => res.json())
            .then(data => {
                renderTable(data.data);
                renderPagination(data.current_page, data.last_page, data.total);
            })
            .catch(err => console.error(err));
    }

    // ==================== RENDER TABLE ====================
    function renderTable(menus) {
        const tbody = document.getElementById('menuTable');
        if (!menus.length) {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center py-10 text-gray-400">Tidak ada data menu</td></tr>';
            return;
        }
        
        let html = '';
        menus.forEach((menu, idx) => {
            const gambarUrl = menu.gambar ? `/storage/${menu.gambar}` : null;
            const diskonText = (menu.diskon_jenis === 'persen' && menu.diskon_nilai > 0) ? `${menu.diskon_nilai}%` : '-';
            
            let ukuranHargaText = '';
            if (menu.id_kategori == 1 && menu.pizza_ukuran && menu.pizza_ukuran.length > 0) {
                const list = menu.pizza_ukuran.map(uk => `${uk.ukuran}=${formatRupiah(uk.harga)}`);
                ukuranHargaText = list.join(', ');
            } else {
                ukuranHargaText = `Rp ${formatRupiah(menu.harga)}`;
            }
            
            // ✅ STATUS STOK
            let stokStatus = '';
            if (menu.stok_menu > 3) {
                stokStatus = `<span class="text-gray-500">${menu.stok_menu}</span>`;
            } else if (menu.stok_menu > 0 && menu.stok_menu <= 3) {
                stokStatus = `<span class="text-orange-500 font-bold">${menu.stok_menu} ⚠️</span>`;
            } else {
                stokStatus = `<span class="text-red-500 font-bold">0 ❌</span>`;
            }
            
            html += `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-600">${(currentPage-1)*15 + idx+1}</td>
                    <td class="px-4 py-3">
                        ${gambarUrl ? `<img src="${gambarUrl}" class="w-12 h-12 object-cover rounded-lg">` : '<div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-2xl">🍽️</div>'}
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">${menu.nama_menu}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${menu.kategori?.nama_kategori || '-'}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${ukuranHargaText}</td>
                    <td class="px-4 py-3 text-sm text-center">${diskonText}</td>
                    <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">${menu.deskripsi || '-'}</td>
                    <td class="px-4 py-3 text-center">${stokStatus}</td>
                    <td class="px-4 py-3 text-center">
                        <button onclick="editMenu(${menu.id_menu})" class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                            <i class="bi bi-pencil-square text-xl"></i>
                        </button>
                        <button onclick="nonaktifkanMenu(${menu.id_menu}, '${menu.nama_menu}')" class="text-red-600 hover:text-red-800" title="Nonaktifkan">
                            <i class="bi bi-trash text-xl"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    // ==================== PAGINATION ====================
    function renderPagination(current, last, total) {
        const info = document.getElementById('paginationInfo');
        const buttons = document.getElementById('paginationButtons');
        if (!info || !buttons) return;
        
        info.innerHTML = `Menampilkan ${(current-1)*15+1} - ${Math.min(current*15, total)} dari ${total} menu`;
        let html = '';
        if (current > 1) html += `<button onclick="goToPage(${current-1})" class="px-3 py-1 border rounded-lg hover:bg-gray-100">← Prev</button>`;
        for (let i = Math.max(1, current-2); i <= Math.min(last, current+2); i++) {
            html += `<button onclick="goToPage(${i})" class="px-3 py-1 border rounded-lg ${i===current ? 'bg-[#D73535] text-white' : 'hover:bg-gray-100'}">${i}</button>`;
        }
        if (current < last) html += `<button onclick="goToPage(${current+1})" class="px-3 py-1 border rounded-lg hover:bg-gray-100">Next →</button>`;
        buttons.innerHTML = html;
    }

    function goToPage(page) { currentPage = page; loadMenu(); }
    function filterMenu() { currentPage = 1; loadMenu(); }
    function resetFilter() {
        document.getElementById('filterKategori').value = 'all';
        document.getElementById('searchMenu').value = '';
        currentPage = 1;
        loadMenu();
    }

    // ==================== PREVIEW GAMBAR ====================
    const gambarInput = document.getElementById('gambar');
    if (gambarInput) {
        gambarInput.addEventListener('change', function(e) {
            const preview = document.getElementById('previewGambar');
            const img = document.getElementById('previewImg');
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = ev => { img.src = ev.target.result; preview.classList.remove('hidden'); };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        });
    }

    // ==================== MODAL ====================
    function openAddModal() {
        document.getElementById('modalTitle').innerHTML = 'Tambah Menu';
        document.getElementById('menuForm').reset();
        document.getElementById('menuId').value = '';
        document.getElementById('methodField').value = '';
        document.getElementById('previewGambar').classList.add('hidden');
        document.getElementById('diskonJenis').value = 'none';
        document.getElementById('diskonNilaiWrapper').classList.add('hidden');
        document.getElementById('ukuranContainer').style.display = 'none';
        document.getElementById('hargaContainer').style.display = 'block';
        document.getElementById('harga').value = '';
        document.getElementById('stokMenu').value = 0;
        document.getElementById('menuModal').classList.remove('hidden');
        document.getElementById('menuModal').classList.add('flex');
    }

    function editMenu(id) {
        fetch(`/api/admin/menu/${id}`)
            .then(res => res.json())
            .then(menu => {
                document.getElementById('menuId').value = menu.id_menu;
                document.getElementById('methodField').value = 'PUT';
                document.getElementById('modalTitle').innerHTML = 'Edit Menu';
                document.getElementById('namaMenu').value = menu.nama_menu;
                document.getElementById('idKategori').value = menu.id_kategori;
                document.getElementById('deskripsi').value = menu.deskripsi || '';
                document.getElementById('stokMenu').value = menu.stok_menu || 0;
                
                document.getElementById('diskonJenis').value = menu.diskon_jenis || 'none';
                if (menu.diskon_jenis === 'persen') {
                    document.getElementById('diskonNilaiWrapper').classList.remove('hidden');
                    document.getElementById('diskonNilai').value = menu.diskon_nilai;
                } else {
                    document.getElementById('diskonNilaiWrapper').classList.add('hidden');
                }
                
                const ukuranContainer = document.getElementById('ukuranContainer');
                const hargaContainer = document.getElementById('hargaContainer');
                
                if (menu.id_kategori == 1) {
                    ukuranContainer.style.display = 'block';
                    hargaContainer.style.display = 'none';
                    
                    document.getElementById('hargaS').value = '';
                    document.getElementById('hargaM').value = '';
                    document.getElementById('hargaL').value = '';
                    
                    if (menu.pizza_ukuran && menu.pizza_ukuran.length > 0) {
                        menu.pizza_ukuran.forEach(uk => {
                            if (uk.ukuran === 'S') document.getElementById('hargaS').value = uk.harga;
                            if (uk.ukuran === 'M') document.getElementById('hargaM').value = uk.harga;
                            if (uk.ukuran === 'L') document.getElementById('hargaL').value = uk.harga;
                        });
                    }
                } else {
                    ukuranContainer.style.display = 'none';
                    hargaContainer.style.display = 'block';
                    document.getElementById('harga').value = menu.harga;
                }
                
                if (menu.gambar) {
                    document.getElementById('previewImg').src = `/storage/${menu.gambar}`;
                    document.getElementById('previewGambar').classList.remove('hidden');
                } else {
                    document.getElementById('previewGambar').classList.add('hidden');
                }
                
                document.getElementById('menuModal').classList.remove('hidden');
                document.getElementById('menuModal').classList.add('flex');
            })
            .catch(err => console.error(err));
    }

    function toggleDiskonForm() {
        const diskonJenis = document.getElementById('diskonJenis').value;
        const wrapper = document.getElementById('diskonNilaiWrapper');
        if (diskonJenis === 'persen') {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
            document.getElementById('diskonNilai').value = '';
        }
    }

    function closeModal(event) {
        if (event && event.target !== document.getElementById('menuModal')) return;
        document.getElementById('menuModal').classList.add('hidden');
        document.getElementById('menuModal').classList.remove('flex');
    }

    // ==================== SUBMIT FORM ====================
    document.getElementById('menuForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('menuId').value;
        let url = '/api/admin/menu';
        const formData = new FormData();
        
        formData.append('nama_menu', document.getElementById('namaMenu').value);
        formData.append('id_kategori', document.getElementById('idKategori').value);
        formData.append('deskripsi', document.getElementById('deskripsi').value);
        formData.append('diskon_jenis', document.getElementById('diskonJenis').value);
        formData.append('diskon_nilai', document.getElementById('diskonNilai').value || 0);
        formData.append('stok_menu', document.getElementById('stokMenu').value || 0);
        
        const kategoriId = document.getElementById('idKategori').value;
        if (kategoriId == 1) {
            formData.append('harga_s', document.getElementById('hargaS').value || 0);
            formData.append('harga_m', document.getElementById('hargaM').value || 0);
            formData.append('harga_l', document.getElementById('hargaL').value || 0);
            formData.append('harga', 0);
        } else {
            formData.append('harga', document.getElementById('harga').value || 0);
        }
        
        const gambarFile = document.getElementById('gambar').files[0];
        if (gambarFile) formData.append('gambar', gambarFile);
        
        if (id) {
            url = `/api/admin/menu/${id}`;
            formData.append('_method', 'PUT');
        }
        
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonColor: '#D73535',
                    confirmButtonText: 'OK'
                }).then(() => {
                    closeModal();
                    loadMenu();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Error: ' + data.message,
                    confirmButtonColor: '#D73535',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan menu',
                confirmButtonColor: '#D73535',
                confirmButtonText: 'OK'
            });
        });
    });

    // ==================== NONAKTIFKAN MENU ====================
    let deleteMenuName = '';
    
    function nonaktifkanMenu(id, name) {
        deleteId = id;
        deleteMenuName = name;
        
        fetch(`/api/admin/menu/cek-dipesan/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('deleteMenuName').innerText = name;
                const usageInfo = document.getElementById('menuUsageInfo');
                const usageText = document.getElementById('menuUsageText');
                
                if (data.sudah_dipesan > 0) {
                    usageInfo.classList.remove('hidden');
                    usageText.innerHTML = `
                        <i class="bi bi-receipt"></i> Menu ini sudah dipesan <strong>${data.sudah_dipesan}</strong> kali.
                        <br>Data historis penjualan akan tetap tersimpan di database.
                        <br>Menu tidak akan muncul di daftar menu kasir.
                    `;
                } else {
                    usageInfo.classList.add('hidden');
                }
                
                document.getElementById('deleteModal').classList.remove('hidden');
                document.getElementById('deleteModal').classList.add('flex');
            })
            .catch(() => {
                document.getElementById('deleteMenuName').innerText = name;
                document.getElementById('menuUsageInfo').classList.add('hidden');
                document.getElementById('deleteModal').classList.remove('hidden');
                document.getElementById('deleteModal').classList.add('flex');
            });
    }

    function closeDeleteModal(event) {
        if (event && event.target !== document.getElementById('deleteModal')) return;
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
        deleteId = null;
    }

    function confirmDelete() {
        if (!deleteId) return;
        
        Swal.fire({
            title: 'Memproses...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch(`/api/admin/menu/${deleteId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonColor: '#D73535',
                    confirmButtonText: 'OK'
                }).then(() => {
                    closeDeleteModal();
                    loadMenu();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan',
                    confirmButtonColor: '#D73535',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan pada server',
                confirmButtonColor: '#D73535',
                confirmButtonText: 'OK'
            });
        });
    }

    // ==================== EVENT LISTENERS ====================
    document.getElementById('diskonJenis').addEventListener('change', toggleDiskonForm);
    
    const kategoriSelect = document.getElementById('idKategori');
    const ukuranContainer = document.getElementById('ukuranContainer');
    const hargaContainer = document.getElementById('hargaContainer');
    
    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', function() {
            if (this.value == 1) {
                ukuranContainer.style.display = 'block';
                hargaContainer.style.display = 'none';
            } else {
                ukuranContainer.style.display = 'none';
                hargaContainer.style.display = 'block';
                document.getElementById('hargaS').value = '';
                document.getElementById('hargaM').value = '';
                document.getElementById('hargaL').value = '';
            }
        });
    }
    
    // ==================== INITIAL LOAD ====================
    loadMenu();
</script>
@endsection