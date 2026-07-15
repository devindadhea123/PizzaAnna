@extends('layouts.app')

@section('title', 'Tambah Resep Menu')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.resep-menu') }}" class="text-gray-500 hover:text-[#D73535] transition">
                <i class="bi bi-arrow-left text-2xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Tambah Resep Baru</h1>
        </div>
        <p class="text-gray-500 mt-1">Atur bahan baku untuk menu yang dipilih</p>
    </div>

    <!-- Form Tambah Resep -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <form id="resepForm">
            @csrf
            
            <!-- Pilih Menu -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Menu <span class="text-red-500">*</span></label>
                <select id="pilihMenu" name="id_menu" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]" required>
                    <option value="">-- Pilih Menu --</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id_menu }}">{{ $menu->nama_menu }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Ukuran -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Pizza</label>
                <select id="ukuran" name="ukuran" class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    <option value="">Tanpa Ukuran</option>
                    <option value="S">S (Small)</option>
                    <option value="M">M (Medium)</option>
                    <option value="L">L (Large)</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">Pilih ukuran jika menu adalah Pizza</p>
            </div>
            
            <!-- Daftar Bahan Baku dengan Checkbox -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Bahan Baku yang Dibutuhkan</label>
                <div class="border rounded-xl p-3 max-h-60 overflow-y-auto bg-gray-50" id="bahanContainer">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($bahan as $item)
                        <div class="flex items-center gap-2 bg-white p-2 rounded-lg border hover:border-[#D73535] transition bahan-item" data-id="{{ $item->id_bahan }}">
                            <input type="checkbox" class="bahan-checkbox w-4 h-4 text-[#D73535] rounded" data-id="{{ $item->id_bahan }}" data-nama="{{ $item->nama_bahan }}" data-satuan="{{ $item->satuan }}">
                            <label class="flex-1 text-sm font-medium text-gray-700 cursor-pointer">{{ $item->nama_bahan }}</label>
                            <span class="text-xs text-gray-400">{{ $item->satuan }}</span>
                            
                            <!-- Input Jumlah -->
                            <div class="hidden jumlah-wrapper flex items-center gap-1">
                                <input type="number" class="jumlah-input w-16 border rounded px-1.5 py-0.5 text-xs" placeholder="Jml" min="0.01" step="0.01" disabled>
                                <span class="text-xs text-gray-500 satuan-text">{{ $item->satuan }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">Centang bahan yang dibutuhkan, lalu isi jumlahnya</p>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-[#D73535] text-white py-2 rounded-xl hover:bg-red-700 transition font-semibold">
                    <i class="bi bi-save"></i> Simpan Resep
                </button>
                <a href="{{ route('admin.resep-menu') }}" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-xl hover:bg-gray-300 transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==================== TOGGLE JUMLAH INPUT SAAT CHECKBOX DICENTANG ====================
    document.querySelectorAll('.bahan-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const wrapper = this.closest('.bahan-item').querySelector('.jumlah-wrapper');
            const input = wrapper.querySelector('.jumlah-input');
            
            if (this.checked) {
                wrapper.classList.remove('hidden');
                input.disabled = false;
                input.focus();
            } else {
                wrapper.classList.add('hidden');
                input.disabled = true;
                input.value = '';
            }
        });
    });

    // ==================== SUBMIT FORM ====================
    document.getElementById('resepForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const menuId = document.getElementById('pilihMenu').value;
        const ukuran = document.getElementById('ukuran').value;
        
        if (!menuId) {
            Swal.fire('Error', 'Pilih menu terlebih dahulu', 'error');
            return;
        }
        
        // Kumpulkan data bahan yang dipilih
        const resepData = [];
        const checkedItems = document.querySelectorAll('.bahan-checkbox:checked');
        
        if (checkedItems.length === 0) {
            Swal.fire('Error', 'Pilih minimal 1 bahan baku', 'error');
            return;
        }
        
        let valid = true;
        checkedItems.forEach(function(checkbox) {
            const wrapper = checkbox.closest('.bahan-item').querySelector('.jumlah-wrapper');
            const input = wrapper.querySelector('.jumlah-input');
            const satuan = checkbox.dataset.satuan;
            
            const jumlah = parseFloat(input.value);
            
            if (!jumlah || jumlah <= 0) {
                Swal.fire('Error', `Jumlah untuk "${checkbox.dataset.nama}" harus diisi`, 'error');
                valid = false;
                return;
            }
            
            resepData.push({
                id_bahan: parseInt(checkbox.dataset.id),
                jumlah: jumlah,
                satuan: satuan
            });
        });
        
        if (!valid || resepData.length === 0) return;
        
        // Kirim ke server
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        fetch('{{ route("admin.resep-menu.store-bulk") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id_menu: parseInt(menuId),
                ukuran: ukuran || null,
                resep: resepData
            })
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
                    window.location.href = '{{ route("admin.resep-menu") }}';
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
    });
</script>
@endsection