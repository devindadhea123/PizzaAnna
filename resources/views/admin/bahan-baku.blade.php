@extends('layouts.app')

@section('title', 'Manajemen Bahan Baku')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Manajemen Bahan Baku</h1>
            <p class="text-gray-500 text-sm">Kelola stok bahan baku untuk resep menu</p>
        </div>
        <button onclick="openAddModal()" class="bg-[#D73535] text-white px-4 py-2 rounded-xl hover:bg-red-700 transition">
            <i class="bi bi-plus-circle"></i> Tambah Bahan
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4 text-left text-sm font-semibold text-gray-600">No</th>
                    <th class="p-4 text-left text-sm font-semibold text-gray-600">Nama Bahan</th>
                    <th class="p-4 text-left text-sm font-semibold text-gray-600">Stok</th>
                    <th class="p-4 text-left text-sm font-semibold text-gray-600">Satuan</th>
                    <th class="p-4 text-left text-sm font-semibold text-gray-600">Stok Minimal</th>
                    <th class="p-4 text-center text-sm font-semibold text-gray-600">Status</th>
                    <th class="p-4 text-center text-sm font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bahan as $item)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-4 text-sm">{{ $loop->iteration }}</td>
                    <td class="p-4 text-sm font-semibold">{{ $item->nama_bahan }}</td>
                    <td class="p-4 text-sm">{{ number_format($item->stok) }}</td>
                    <td class="p-4 text-sm">{{ $item->satuan }}</td>
                    <td class="p-4 text-sm">{{ number_format($item->stok_minimal) }}</td>
                    <td class="p-4 text-center">
                        @if($item->stok <= $item->stok_minimal)
                            <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">⚠️ Menipis</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700"> Aman</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <button onclick="editBahan({{ $item->id_bahan }})" class="text-blue-600 hover:text-blue-800 mr-2">
                            <i class="bi bi-pencil-square text-lg"></i>
                        </button>
                        <button onclick="tambahStok({{ $item->id_bahan }})" class="text-green-600 hover:text-green-800 mr-2" title="Tambah Stok">
                            <i class="bi bi-plus-circle text-lg"></i>
                        </button>
                        <button onclick="deleteBahan({{ $item->id_bahan }})" class="text-red-600 hover:text-red-800">
                            <i class="bi bi-trash text-lg"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center p-5 text-gray-400">Belum ada bahan baku</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH/EDIT -->
<div id="bahanModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 text-white p-4 rounded-t-2xl -mt-6 -mx-6 mb-4 flex justify-between items-center">
            <h2 id="modalTitle" class="text-xl font-bold">Tambah Bahan Baku</h2>
            <button onclick="closeModal()" class="hover:bg-white/20 rounded-full p-1">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="bahanForm">
            @csrf
            <input type="hidden" id="bahanId" name="id">
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold">Nama Bahan <span class="text-red-500">*</span></label>
                    <input type="text" id="namaBahan" name="nama_bahan" class="w-full border rounded-xl px-3 py-2 mt-1" required>
                </div>
                <div>
                    <label class="text-sm font-semibold">Satuan <span class="text-red-500">*</span></label>
                    <input type="text" id="satuan" name="satuan" placeholder="kg, gram, ml, liter" class="w-full border rounded-xl px-3 py-2 mt-1" required>
                </div>
                <div>
                    <label class="text-sm font-semibold">Stok Awal <span class="text-red-500">*</span></label>
                    <input type="number" id="stok" name="stok" step="0.01" min="0" class="w-full border rounded-xl px-3 py-2 mt-1" required>
                </div>
                <div>
                    <label class="text-sm font-semibold">Stok Minimal <span class="text-red-500">*</span></label>
                    <input type="number" id="stokMinimal" name="stok_minimal" step="0.01" min="0" class="w-full border rounded-xl px-3 py-2 mt-1" required>
                    <p class="text-xs text-gray-400 mt-1">Peringatan akan muncul jika stok di bawah angka ini</p>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded-xl">Batal</button>
                <button type="submit" class="bg-[#D73535] text-white px-4 py-2 rounded-xl">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL TAMBAH STOK -->
<div id="stokModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white p-4 rounded-t-2xl -mt-6 -mx-6 mb-4 flex justify-between items-center">
            <h2 class="text-xl font-bold">Tambah Stok</h2>
            <button onclick="closeStokModal()" class="hover:bg-white/20 rounded-full p-1">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="stokForm">
            @csrf
            <input type="hidden" id="stokBahanId" name="id">
            <div>
                <label class="text-sm font-semibold">Jumlah yang Ditambahkan <span class="text-red-500">*</span></label>
                <input type="number" id="stokJumlah" name="jumlah" step="0.01" min="0.01" class="w-full border rounded-xl px-3 py-2 mt-1" required>
                <p class="text-xs text-gray-400 mt-1">Masukkan jumlah stok yang akan ditambahkan</p>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button type="button" onclick="closeStokModal()" class="bg-gray-300 px-4 py-2 rounded-xl">Batal</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-xl">Tambah Stok</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ==================== MODAL BAHAN ====================
    function openAddModal() {
        document.getElementById('bahanForm').reset();
        document.getElementById('bahanId').value = '';
        document.getElementById('modalTitle').innerHTML = 'Tambah Bahan Baku';
        document.getElementById('bahanModal').classList.remove('hidden');
        document.getElementById('bahanModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('bahanModal').classList.add('hidden');
        document.getElementById('bahanModal').classList.remove('flex');
    }

    function editBahan(id) {
        fetch(`/api/admin/bahan-baku/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('bahanId').value = data.id_bahan;
                document.getElementById('namaBahan').value = data.nama_bahan;
                document.getElementById('satuan').value = data.satuan;
                document.getElementById('stok').value = data.stok;
                document.getElementById('stokMinimal').value = data.stok_minimal;
                document.getElementById('modalTitle').innerHTML = 'Edit Bahan Baku';
                document.getElementById('bahanModal').classList.remove('hidden');
                document.getElementById('bahanModal').classList.add('flex');
            });
    }

    document.getElementById('bahanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('bahanId').value;
        const url = id ? `/admin/bahan-baku/update/${id}` : '/admin/bahan-baku/store';
        let formData = new FormData(this);
        if (id) formData.append('_method', 'PUT');
        formData.append('_token', '{{ csrf_token() }}');
        
        Swal.fire({ title: 'Menyimpan...', text: 'Mohon tunggu', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
        
        fetch(url, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message });
                }
            });
    });

    // ==================== MODAL TAMBAH STOK ====================
    function tambahStok(id) {
        document.getElementById('stokBahanId').value = id;
        document.getElementById('stokJumlah').value = '';
        document.getElementById('stokModal').classList.remove('hidden');
        document.getElementById('stokModal').classList.add('flex');
    }

    function closeStokModal() {
        document.getElementById('stokModal').classList.add('hidden');
        document.getElementById('stokModal').classList.remove('flex');
    }

    document.getElementById('stokForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('stokBahanId').value;
        const formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');
        
        Swal.fire({ title: 'Menyimpan...', text: 'Mohon tunggu', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
        
        fetch(`/admin/bahan-baku/tambah-stok/${id}`, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message }).then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message });
                }
            });
    });

    // ==================== HAPUS ====================
    function deleteBahan(id) {
        Swal.fire({
            title: 'Hapus Bahan?',
            text: 'Yakin ingin menghapus bahan baku ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D73535',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/bahan-baku/delete/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection