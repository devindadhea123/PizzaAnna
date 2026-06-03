@extends('layouts.app')

@section('title', 'Manajemen Topping')

@section('content')
<div class="p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Topping</h1>
    </div>

    {{-- Tombol Tambah Topping --}}
    <div class="mb-6">
        <button onclick="openAddModal()" class="bg-[#D73535] text-white px-6 py-2 rounded-xl hover:bg-red-700 transition inline-flex items-center gap-2 shadow-md">
            <i class="bi bi-plus-circle"></i> Tambah Topping Baru
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4 text-left">No</th>
                    <th class="p-4 text-left">Nama</th>
                    <th class="p-4 text-left">Ukuran</th>
                    <th class="p-4 text-left">Harga</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="toppingTable">
                @forelse($toppings as $item)
                <tr class="border-t" id="row-{{ $item->id_topping }}">
                    <td class="p-4">{{ $loop->iteration }}</td>
                    <td class="p-4 font-semibold">{{ $item->nama_topping }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($item->ukuran == 'S') bg-green-100 text-green-700
                            @elseif($item->ukuran == 'M') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $item->ukuran }}
                        </span>
                    </td>
                    <td class="p-4 font-semibold text-[#D73535]">Rp {{ number_format($item->harga,0,',','.') }}</td>
                    <td class="p-4 text-center">
                        <button onclick="openEditModal({{ $item->id_topping }})" class="text-blue-600 hover:text-blue-800 mr-3 transition" title="Edit">
                            <i class="bi bi-pencil-square text-xl"></i>
                        </button>
                        <button onclick="confirmDelete({{ $item->id_topping }}, '{{ $item->nama_topping }}')" class="text-red-600 hover:text-red-800 transition" title="Hapus">
                            <i class="bi bi-trash text-xl"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-5 text-gray-400">Belum ada topping</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH TOPPING --}}
<div id="addModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 text-white p-4 rounded-t-2xl -mt-6 -mx-6 mb-4 flex justify-between items-center">
            <h2 class="text-xl font-bold">Tambah Topping</h2>
            <button onclick="closeAddModal()" class="hover:bg-white/20 rounded-full p-1">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="addForm">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold">Nama Topping</label>
                    <input type="text" id="addNama" name="nama_topping" class="w-full border rounded-xl px-3 py-2 mt-1" required>
                </div>
                <div>
                    <label class="text-sm font-semibold">Ukuran</label>
                    <select id="addUkuran" name="ukuran" class="w-full border rounded-xl px-3 py-2 mt-1" required>
                        <option value="">-- Pilih Ukuran --</option>
                        <option value="S">S (Small)</option>
                        <option value="M">M (Medium)</option>
                        <option value="L">L (Large)</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Harga</label>
                    <input type="number" id="addHarga" name="harga" class="w-full border rounded-xl px-3 py-2 mt-1" required>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeAddModal()" class="bg-gray-300 px-4 py-2 rounded-xl">Batal</button>
                <button type="submit" class="bg-[#D73535] text-white px-4 py-2 rounded-xl">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT TOPPING --}}
<div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-4 rounded-t-2xl -mt-6 -mx-6 mb-4 flex justify-between items-center">
            <h2 class="text-xl font-bold">Edit Topping</h2>
            <button onclick="closeEditModal()" class="hover:bg-white/20 rounded-full p-1">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="editForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="editId">
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold">Nama Topping</label>
                    <input type="text" id="editNama" name="nama_topping" class="w-full border rounded-xl px-3 py-2 mt-1" required>
                </div>
                <div>
                    <label class="text-sm font-semibold">Ukuran</label>
                    <select id="editUkuran" name="ukuran" class="w-full border rounded-xl px-3 py-2 mt-1" required>
                        <option value="S">S (Small)</option>
                        <option value="M">M (Medium)</option>
                        <option value="L">L (Large)</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold">Harga</label>
                    <input type="number" id="editHarga" name="harga" class="w-full border rounded-xl px-3 py-2 mt-1" required>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeEditModal()" class="bg-gray-300 px-4 py-2 rounded-xl">Batal</button>
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-xl">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==================== TAMBAH TOPPING ====================
    function openAddModal() {
        document.getElementById('addForm').reset();
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('addModal').classList.remove('flex');
    }

    document.getElementById('addForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData();
        formData.append('nama_topping', document.getElementById('addNama').value);
        formData.append('ukuran', document.getElementById('addUkuran').value);
        formData.append('harga', document.getElementById('addHarga').value);
        formData.append('_token', '{{ csrf_token() }}');
        
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch('{{ route("admin.topping.store") }}', {
            method: 'POST',
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
                    location.reload();
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
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan',
                confirmButtonColor: '#D73535',
                confirmButtonText: 'OK'
            });
        });
    });

    // ==================== EDIT TOPPING ====================
    function openEditModal(id) {
        let row = document.getElementById(`row-${id}`);
        let cells = row.getElementsByTagName('td');
        
        let nama = cells[1].innerText;
        let ukuran = cells[2].innerText.trim();
        let harga = cells[3].innerText.replace('Rp ', '').replace(/\./g, '');
        
        document.getElementById('editId').value = id;
        document.getElementById('editNama').value = nama;
        document.getElementById('editUkuran').value = ukuran;
        document.getElementById('editHarga').value = harga;
        
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let id = document.getElementById('editId').value;
        let formData = new FormData();
        formData.append('nama_topping', document.getElementById('editNama').value);
        formData.append('ukuran', document.getElementById('editUkuran').value);
        formData.append('harga', document.getElementById('editHarga').value);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch(`/admin/topping/update/${id}`, {
            method: 'POST',
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
                    location.reload();
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
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat update',
                confirmButtonColor: '#D73535',
                confirmButtonText: 'OK'
            });
        });
    });

    // ==================== HAPUS TOPPING ====================
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Topping?',
            text: `Apakah Anda yakin ingin menghapus topping "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D73535',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                fetch(`/admin/topping/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
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
                            location.reload();
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghapus',
                        confirmButtonColor: '#D73535',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }
</script>
@endsection