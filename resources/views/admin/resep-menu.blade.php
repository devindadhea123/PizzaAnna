@extends('layouts.app')

@section('title', 'Manajemen Resep Menu')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800"> Manajemen Resep Menu</h1>
        <p class="text-gray-500 mt-1">Atur bahan baku untuk setiap menu</p>
    </div>

    <!-- Tombol Tambah -->
    <div class="mb-6">
        <a href="{{ route('admin.resep-menu.create') }}" class="bg-[#D73535] text-white px-6 py-2 rounded-xl hover:bg-red-700 transition inline-flex items-center gap-2 shadow-md">
            <i class="bi bi-plus-circle"></i> Tambah Resep Baru
        </a>
    </div>

    <!-- Tabel Resep -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">No</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Menu</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Ukuran</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Bahan Baku</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Group resep berdasarkan id_menu + ukuran
                        $grouped = $resep->groupBy(function($item) {
                            return $item->id_menu . '-' . ($item->ukuran ?? 'no-ukuran');
                        });
                    @endphp

                    @forelse($grouped as $key => $items)
                        @php
                            $first = $items->first();
                            $menu = $first->menu;
                            $ukuran = $first->ukuran;
                            
                            // Gabungkan semua bahan jadi 1 string
                            $bahanList = $items->map(function($item) {
                                return $item->bahanBaku->nama_bahan . ' (' . number_format($item->jumlah, 2) . ' ' . $item->satuan . ')';
                            })->implode(', ');
                        @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $menu->nama_menu ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($ukuran)
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($ukuran == 'S') bg-green-100 text-green-700
                                    @elseif($ukuran == 'M') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ $ukuran }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $bahanList }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.resep-menu.edit', $first->id_resep) }}" class="text-blue-600 hover:text-blue-800 mr-2 inline-block" title="Edit">
                                <i class="bi bi-pencil-square text-xl"></i>
                            </a>
                            <button onclick="deleteResep({{ $first->id_resep }}, '{{ $menu->nama_menu ?? '' }}')" class="text-red-600 hover:text-red-800" title="Hapus">
                                <i class="bi bi-trash text-xl"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-400">Belum ada resep. Tambahkan resep baru!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==================== HAPUS RESEP ====================
    function deleteResep(id, namaMenu) {
        Swal.fire({
            title: 'Hapus Resep?',
            text: `Yakin ingin menghapus resep untuk menu "${namaMenu}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D73535',
            cancelButtonColor: '#6B7280',
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
                
                fetch(`/admin/resep-menu/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
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
                        }).then(() => location.reload());
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
        });
    }
</script>
@endsection