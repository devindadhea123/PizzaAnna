@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Kategori</h1>
        <p class="text-gray-500">Kelola kategori menu (Pizza, Burger, Others, Beverage)</p>
    </div>
    
    <!-- Tombol Tambah Kategori -->
    <div class="mb-6">
        <button onclick="openAddModal()" class="bg-[#D73535] text-white px-6 py-2 rounded-xl hover:bg-red-700 transition inline-flex items-center gap-2 shadow-md">
            <i class="bi bi-plus-circle"></i> Tambah Kategori Baru
        </button>
    </div>
    
    <!-- Tabel Kategori -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Nama Kategori</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Jumlah Menu</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kategoriTable">
                    <tr>
                        <td colspan="4" class="text-center py-10 text-gray-400">
                            <i class="bi bi-hourglass-split text-3xl animate-spin"></i>
                            <p class="mt-2">Loading data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH/EDIT KATEGORI -->
<div id="kategoriModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm" onclick="closeModal(event)">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl" onclick="event.stopPropagation()">
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 text-white p-5 rounded-t-2xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="bi bi-tags text-2xl"></i>
                <h2 id="modalTitle" class="text-xl font-bold">Tambah Kategori</h2>
            </div>
            <button onclick="closeModal()" class="hover:bg-white/20 rounded-full p-2 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <form id="kategoriForm" class="p-6">
            @csrf
            <input type="hidden" id="kategoriId" name="id">
            <input type="hidden" id="methodField" name="_method">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" id="namaKategori" name="nama_kategori" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]" placeholder="Contoh: Pizza, Burger, Others, Beverage" required>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-[#D73535] text-white py-2 rounded-xl hover:bg-red-700 transition font-semibold">Simpan</button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-xl hover:bg-gray-300 transition">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL HAPUS -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal(event)">
    <div class="bg-white rounded-2xl w-full max-w-sm mx-4 shadow-2xl" onclick="event.stopPropagation()">
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white p-5 rounded-t-2xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="bi bi-trash text-2xl"></i>
                <h2 class="text-xl font-bold">Hapus Kategori</h2>
            </div>
            <button onclick="closeDeleteModal()" class="hover:bg-white/20 rounded-full p-2 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menghapus kategori <strong id="deleteKategoriName"></strong>?</p>
            <p class="text-sm text-red-500 mb-4">⚠️ Perhatian: Kategori yang memiliki menu tidak dapat dihapus!</p>
            <div class="flex gap-3">
                <button onclick="confirmDelete()" class="flex-1 bg-red-600 text-white py-2 rounded-xl hover:bg-red-700 transition">Ya, Hapus</button>
                <button onclick="closeDeleteModal()" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-xl hover:bg-gray-300 transition">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let deleteId = null;
    let deleteName = null;

    // Load kategori dari server
    function loadKategori() {
        fetch('/api/admin/kategori')
            .then(response => response.json())
            .then(data => {
                renderTable(data);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('kategoriTable').innerHTML = `
                    <tr><td colspan="4" class="text-center py-10 text-red-500">
                        <i class="bi bi-wifi-off text-3xl"></i>
                        <p class="mt-2">Error loading data. Please refresh.</p>
                    </td>
                    </tr>
                `;
            });
    }

    // Render tabel
    function renderTable(kategoris) {
        const tbody = document.getElementById('kategoriTable');
        
        if (kategoris.length === 0) {
            tbody.innerHTML = `
                <tr><td colspan="4" class="text-center py-10 text-gray-400">
                    <i class="bi bi-inbox text-3xl"></i>
                    <p class="mt-2">Tidak ada data kategori</p>
                </td></tr>
            `;
            return;
        }
        
        let html = '';
        kategoris.forEach((kategori, index) => {
            html += `
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-600">${index + 1}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">${kategori.nama_kategori}</td>
                    <td class="px-6 py-4 text-sm text-center">
                        <span class="px-2 py-1 rounded-full text-xs ${kategori.menu_count > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500'}">
                            ${kategori.menu_count} menu
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="editKategori(${kategori.id_kategori})" class="text-blue-600 hover:text-blue-800 mr-3 transition" title="Edit">
                            <i class="bi bi-pencil-square text-xl"></i>
                        </button>
                        <button onclick="showDeleteModal(${kategori.id_kategori}, '${kategori.nama_kategori}', ${kategori.menu_count})" class="text-red-600 hover:text-red-800 transition" title="Hapus">
                            <i class="bi bi-trash text-xl"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    // Open modal tambah
    function openAddModal() {
        document.getElementById('modalTitle').innerHTML = 'Tambah Kategori';
        document.getElementById('kategoriForm').reset();
        document.getElementById('kategoriId').value = '';
        document.getElementById('methodField').value = '';
        document.getElementById('kategoriModal').classList.remove('hidden');
        document.getElementById('kategoriModal').classList.add('flex');
    }

    // Edit kategori
    function editKategori(id) {
        fetch(`/api/admin/kategori/${id}`)
            .then(response => response.json())
            .then(kategori => {
                document.getElementById('kategoriId').value = kategori.id_kategori;
                document.getElementById('methodField').value = 'PUT';
                document.getElementById('modalTitle').innerHTML = 'Edit Kategori';
                document.getElementById('namaKategori').value = kategori.nama_kategori;
                document.getElementById('kategoriModal').classList.remove('hidden');
                document.getElementById('kategoriModal').classList.add('flex');
            })
            .catch(error => console.error('Error:', error));
    }

    // Close modal
    function closeModal(event) {
        if (event && event.target !== document.getElementById('kategoriModal') && event.target !== event.currentTarget) return;
        document.getElementById('kategoriModal').classList.add('hidden');
        document.getElementById('kategoriModal').classList.remove('flex');
    }

    // Submit form dengan SweetAlert
    document.getElementById('kategoriForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('kategoriId').value;
        let url = '/api/admin/kategori';
        let formData = new FormData();
        
        formData.append('nama_kategori', document.getElementById('namaKategori').value);
        
        if (id) {
            url = `/api/admin/kategori/${id}`;
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
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
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
                    loadKategori();
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
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan kategori',
                confirmButtonColor: '#D73535',
                confirmButtonText: 'OK'
            });
        });
    });

    // Show delete modal
    function showDeleteModal(id, name, menuCount) {
        deleteId = id;
        deleteName = name;
        document.getElementById('deleteKategoriName').innerText = name;
        
        if (menuCount > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Dapat Dihapus!',
                text: `Kategori "${name}" tidak dapat dihapus karena masih memiliki ${menuCount} menu!`,
                confirmButtonColor: '#D73535',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    // Close delete modal
    function closeDeleteModal(event) {
        if (event && event.target !== document.getElementById('deleteModal') && event.target !== event.currentTarget) return;
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
        deleteId = null;
    }

    // Confirm delete dengan SweetAlert
    function confirmDelete() {
        if (!deleteId) return;
        
        Swal.fire({
            title: 'Menghapus...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch(`/api/admin/kategori/${deleteId}`, {
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
                    loadKategori();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message,
                    confirmButtonColor: '#D73535',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menghapus kategori',
                confirmButtonColor: '#D73535',
                confirmButtonText: 'OK'
            });
        });
    }

    // Initial load
    loadKategori();
</script>
@endsection