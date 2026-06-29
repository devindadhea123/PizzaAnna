@extends('layouts.app')

@section('title', 'Pengaturan Jadwal Prediksi')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="mb-8 flex items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengaturan Jadwal Prediksi</h1>
            <p class="text-gray-500 mt-1 text-sm">Atur kapan sistem menjalankan prediksi otomatis setiap bulan</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 mb-6 rounded-xl flex items-center gap-2 text-sm">
            <i class="ti ti-circle-check text-green-500" style="font-size: 18px;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">

        {{-- FORM --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('admin.pengaturan-jadwal.update') }}">
                @csrf

                <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ti ti-clock" style="font-size: 18px;"></i>
                    Waktu Eksekusi
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Tanggal</label>
                        <div class="relative">
                            <select name="prediction_day" class="w-full appearance-none border border-gray-200 bg-gray-50 rounded-xl pl-4 pr-9 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#D73535]/30 focus:border-[#D73535] transition">
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ $settings['prediction_day'] == $i ? 'selected' : '' }}>
                                        Tanggal {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <i class="ti ti-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" style="font-size: 16px;"></i>
                        </div>
                    </div>

                    {{-- Jam --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Jam</label>
                        <div class="relative">
                            <select name="prediction_hour" class="w-full appearance-none border border-gray-200 bg-gray-50 rounded-xl pl-4 pr-9 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#D73535]/30 focus:border-[#D73535] transition">
                                @for($i = 0; $i <= 23; $i++)
                                    <option value="{{ $i }}" {{ $settings['prediction_hour'] == $i ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $i) }}:00
                                    </option>
                                @endfor
                            </select>
                            <i class="ti ti-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" style="font-size: 16px;"></i>
                        </div>
                    </div>

                    {{-- Menit --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Menit</label>
                        <div class="relative">
                            <select name="prediction_minute" class="w-full appearance-none border border-gray-200 bg-gray-50 rounded-xl pl-4 pr-9 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#D73535]/30 focus:border-[#D73535] transition">
                                @for($i = 0; $i <= 59; $i++)
                                    <option value="{{ $i }}" {{ $settings['prediction_minute'] == $i ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $i) }} Menit
                                    </option>
                                @endfor
                            </select>
                            <i class="ti ti-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" style="font-size: 16px;"></i>
                        </div>
                    </div>
                </div>

                {{-- Toggle Aktif / Nonaktif --}}
                <div class="mb-2">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="ti ti-toggle-right" style="font-size: 18px;"></i>
                        Status Otomatisasi
                    </h3>
                    <label class="flex items-center justify-between gap-3 cursor-pointer border border-gray-200 rounded-xl px-4 py-3 hover:border-gray-300 transition">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Aktifkan Prediksi Otomatis</span>
                            <p class="text-xs text-gray-400 mt-0.5">Jika nonaktif, sistem tidak akan menjalankan prediksi otomatis</p>
                        </div>
                        <div class="relative inline-flex items-center">
                            <input type="checkbox" name="prediction_enabled" id="prediction_enabled" value="1" class="sr-only peer"
                                {{ $settings['prediction_enabled'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-[#D73535] transition-colors duration-300"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform duration-300 peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                </div>

                {{-- Tombol Simpan --}}
                <div class="flex justify-end mt-6 pt-6 border-t border-gray-100">
                    <button type="submit" class="bg-[#D73535] text-white px-6 py-2.5 rounded-xl hover:bg-red-700 transition flex items-center gap-2 text-sm font-medium shadow-sm">
                        <i class="ti ti-device-floppy" style="font-size: 18px;"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        {{-- RINGKASAN / PREVIEW --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="ti ti-info-circle" style="font-size: 18px;"></i>
                    Ringkasan Jadwal
                </h3>

                <div id="statusBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium mb-4">
                    <i id="statusIcon" style="font-size: 14px;"></i>
                    <span id="statusText"></span>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 mb-4">
                    <p class="text-xs text-gray-400 mb-1">Jadwal eksekusi</p>
                    <p class="text-lg font-semibold text-gray-800" id="ringkasanWaktu">-</p>
                </div>

                <p class="text-sm text-gray-500 leading-relaxed" id="ringkasanDeskripsi"></p>

                <div class="mt-5 pt-5 border-t border-gray-100 flex items-start gap-2 text-xs text-gray-400">
                    <i class="ti ti-bulb mt-0.5" style="font-size: 14px;"></i>
                    <span>Prediksi akan berjalan otomatis di background setiap kali jadwal tercapai, tanpa perlu interaksi pengguna.</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function updateRingkasan() {
        const day = document.querySelector('[name="prediction_day"]').value;
        const hour = document.querySelector('[name="prediction_hour"]').value;
        const minute = document.querySelector('[name="prediction_minute"]').value;
        const enabled = document.querySelector('[name="prediction_enabled"]').checked;

        const waktu = document.getElementById('ringkasanWaktu');
        const deskripsi = document.getElementById('ringkasanDeskripsi');
        const badge = document.getElementById('statusBadge');
        const icon = document.getElementById('statusIcon');
        const text = document.getElementById('statusText');

        waktu.textContent = `Tanggal ${day}, pukul ${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')} WIB`;

        if (enabled) {
            deskripsi.textContent = `Sistem akan menjalankan prediksi otomatis setiap bulan pada tanggal ${day} pukul ${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')} WIB.`;
            badge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium mb-4 bg-green-50 text-green-600';
            icon.className = 'ti ti-circle-check';
            text.textContent = 'Aktif';
        } else {
            deskripsi.textContent = 'Prediksi otomatis sedang dinonaktifkan. Aktifkan toggle di atas untuk menjalankan prediksi secara berkala.';
            badge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium mb-4 bg-gray-100 text-gray-500';
            icon.className = 'ti ti-circle-x';
            text.textContent = 'Nonaktif';
        }
    }

    document.querySelectorAll('[name="prediction_day"], [name="prediction_hour"], [name="prediction_minute"], [name="prediction_enabled"]').forEach(el => {
        el.addEventListener('change', updateRingkasan);
    });

    updateRingkasan();
</script>
@endsection