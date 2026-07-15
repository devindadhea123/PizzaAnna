@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="p-6">
    <!-- Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500">Welcome back, {{ Auth::user()->nama_lengkap ?? 'Admin' }}!</p>
</div>

<!-- NOTIFIKASI DASHBOARD -->
<div id="dynamic-notifications" class="mb-6">
    @php
        $today = now();
        $currentHour = (int)$today->format('H');
        $isPredictionDay = ($today->day == 27);
        $existingPrediksi = App\Models\RiwayatPrediksi::where(
            'bulan_target',
            now()->addMonth()->format('Y-m')
        )->first();
    @endphp

    @if($isPredictionDay && $currentHour >= 12 && !$existingPrediksi)
        <div class="bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-2xl shadow-sm">
            <div class="flex items-start gap-3">
                <i class="bi bi-hourglass-split text-2xl text-blue-600"></i>
                <div>
                    <p class="font-semibold">Sistem Sedang Memproses Prediksi</p>
                    <p class="text-sm mt-1">
                        Waktu prediksi manual telah berakhir. Sistem akan menjalankan prediksi otomatis.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
    <!-- FILTER BULAN & TAHUN -->
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
        <div class="flex items-center gap-4 flex-wrap">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select id="filterMonth" class="border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    <option value="">-- Pilih Bulan --</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select id="filterYear" class="border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    <option value="">-- Pilih Tahun --</option>
                </select>
            </div>
            <button onclick="applyFilter()" class="bg-[#D73535] text-white px-6 py-2 rounded-xl hover:bg-red-700 transition mt-5">
                <i class="bi bi-search"></i> Tampilkan
            </button>
            <div class="text-sm text-gray-500 mt-5" id="filterInfo">-- Pilih bulan dan tahun --</div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Pendapatan Hari Ini -->
        <div class="stat-card bg-white rounded-2xl shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Pendapatan Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-800" id="pendapatanHariIni">Rp 0</p>
                    <p class="text-xs mt-2" id="trendHarian">
                        <i class="bi bi-arrow-up"></i> <span class="text-green-500">0% dari kemarin</span>
                    </p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="bi bi-cash-stack text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Pendapatan Bulan Ini -->
        <div class="stat-card bg-gradient-to-r from-[#D73535] to-red-700 rounded-2xl shadow-sm p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-white/80 text-sm mb-1">
                        <span id="bulanTarget">Pendapatan Bulan Ini</span>
                    </p>
                    <p class="text-3xl font-bold" id="pendapatanBulanIni">Rp 0</p>
                    <p class="text-xs text-white/70 mt-2" id="trendBulanan">
                        <i class="bi bi-arrow-up"></i> 0% dari bulan lalu
                    </p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <i class="bi bi-graph-up text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-white/20 rounded-full h-2">
                    <div id="targetProgress" class="bg-yellow-400 h-2 rounded-full" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Omzet Chart -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-800">Omzet Harian</h2>
                <select id="omzetFilter" class="text-sm border rounded-lg px-3 py-1">
                    <option value="7">7 Hari</option>
                    <option value="14">14 Hari</option>
                    <option value="30">30 Hari</option>
                </select>
            </div>
            <canvas id="omzetChart" height="250"></canvas>
        </div>
        
        <!-- Payment Method Chart -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4"> Metode Pembayaran</h2>
            <div class="flex flex-col items-center">
                <canvas id="paymentChart" width="300" height="300" class="mb-4"></canvas>
                <div class="flex justify-center gap-6 mt-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#D73535]"></div>
                        <span class="text-sm">Tunai</span>
                        <span id="tunaiPersen" class="font-bold">0%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <span class="text-sm">QRIS</span>
                        <span id="qrisPersen" class="font-bold">0%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Prediksi Section -->
    <div class="bg-gradient-to-r from-[#D73535] to-red-700 rounded-2xl shadow-sm p-6 mb-8 text-white">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="text-xl font-bold">Prediksi Menu Terlaris</h2>               
                <p class="text-white/60 text-xs mt-2" id="periodeData">Periode: -</p>
            </div>
            <div class="flex gap-2 items-center flex-wrap">
                <select id="targetMonth" class="text-gray-900 text-sm border rounded-lg px-3 py-2 bg-white">
                    <option value="1" {{ date('n') == 1 ? 'selected' : '' }}>Januari</option>
                    <option value="2" {{ date('n') == 2 ? 'selected' : '' }}>Februari</option>
                    <option value="3" {{ date('n') == 3 ? 'selected' : '' }}>Maret</option>
                    <option value="4" {{ date('n') == 4 ? 'selected' : '' }}>April</option>
                    <option value="5" {{ date('n') == 5 ? 'selected' : '' }}>Mei</option>
                    <option value="6" {{ date('n') == 6 ? 'selected' : '' }}>Juni</option>
                    <option value="7" {{ date('n') == 7 ? 'selected' : '' }}>Juli</option>
                    <option value="8" {{ date('n') == 8 ? 'selected' : '' }}>Agustus</option>
                    <option value="9" {{ date('n') == 9 ? 'selected' : '' }}>September</option>
                    <option value="10" {{ date('n') == 10 ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ date('n') == 11 ? 'selected' : '' }}>November</option>
                    <option value="12" {{ date('n') == 12 ? 'selected' : '' }}>Desember</option>
                </select>
                <input type="number" id="targetYear" class="text-gray-900 text-sm border rounded-lg px-3 py-2 w-24 bg-white" placeholder="Tahun" value="{{ date('Y') }}">
                <button onclick="lakukanPrediksi()" id="prediksiBtn" class="bg-yellow-400 text-gray-900 px-6 py-2 rounded-full font-semibold hover:bg-yellow-500 transition flex items-center gap-2">
                    <i class="bi bi-magic"></i> Lakukan Prediksi
                </button>
            </div>
        </div>
        
        <!-- Hasil Prediksi -->
        <div id="hasilPrediksi" class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white/10 rounded-xl p-4 text-center">
                <p class="text-sm text-white/80">Klik tombol di atas untuk melihat prediksi</p>
            </div>
        </div>
        
        <!-- REKOMENDASI PROMOSI -->
        <div id="rekomendasiPromosi" class="mt-6"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentMonth = '';
    let currentYear = '';
    
    function formatRupiah(angka) {
        if (!angka && angka !== 0) return '0';
        let bulat = Math.round(angka);
        return bulat.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // ==================== UPDATE NOTIFIKASI REAL-TIME ====================
    function updateNotifications() {
        fetch('/api/admin/prediction-status')
            .then(response => response.json())
            .then(data => {
                console.log('Updating notifications:', data);
                
               let notificationContainer = document.getElementById('dynamic-notifications');

                if (!notificationContainer) return;
                
                if (notificationContainer) {
                    let notifHtml = '';
                    
                    if (data.has_prediction) {
                        notifHtml = `
                            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg shadow-md">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-check-circle-fill text-2xl text-green-600"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="font-bold text-lg">Prediksi Sudah Tersedia!</p>
                                        <p class="text-sm mt-1">Prediksi menu terlaris untuk bulan <span class="font-semibold">${data.bulan_target || 'bulan depan'}</span> sudah berhasil disimpan.</p>
                                        <p class="text-xs mt-1 text-green-600">
                                            <i class="bi bi-calendar-check"></i> Lihat hasil prediksi di bawah ini atau buka menu <a href="{{ route('admin.riwayat-prediksi') }}" class="underline font-semibold">Riwayat Prediksi</a>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                    } 
                    // Kasus 2: Hari prediksi dan belum lewat deadline
                    // Kasus 2: Hari prediksi tapi belum ada prediksi otomatis
                    else if (data.is_prediction_day && !data.has_prediction) {
                        notifHtml = `
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded-lg shadow-md">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-bell-fill text-2xl text-yellow-600"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="font-bold text-lg"> Hari ini tanggal ${data.prediction_date}! Jadwal Prediksi Otomatis.</p>
                                        <p class="text-sm mt-1">Sistem akan melakukan prediksi otomatis untuk <span class="font-semibold">${data.formatted_bulan || data.bulan_target || 'bulan depan'}</span> pada jam <span class="font-bold">${data.deadline_time}</span>.</p>
                                        <p class="text-xs mt-1 text-yellow-600">
                                            <i class="bi bi-info-circle"></i> Anda tetap bisa melakukan prediksi manual kapan saja tanpa batasan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    
                    notificationContainer.innerHTML = notifHtml;
                }
            })
            .catch(err => console.error('Error update notifications:', err));
    }
    
    // ==================== LOAD FILTER OPTIONS ====================
    function loadFilterOptions() {
        fetch('/api/admin/filter-options')
            .then(response => response.json())
            .then(data => {
                const monthSelect = document.getElementById('filterMonth');
                const yearSelect = document.getElementById('filterYear');
                
                monthSelect.innerHTML = '<option value="">-- Pilih Bulan --</option>';
                yearSelect.innerHTML = '<option value="">-- Pilih Tahun --</option>';
                
                data.months.forEach(month => {
                    const option = document.createElement('option');
                    option.value = month.value;
                    option.textContent = month.name;
                    monthSelect.appendChild(option);
                });
                
                data.years.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    yearSelect.appendChild(option);
                });
                
                if (data.defaultMonth) {
                    monthSelect.value = data.defaultMonth;
                    currentMonth = data.defaultMonth;
                } else if (data.months.length > 0) {
                    monthSelect.value = data.months[0].value;
                    currentMonth = data.months[0].value;
                }
                
                if (data.defaultYear) {
                    yearSelect.value = data.defaultYear;
                    currentYear = data.defaultYear;
                } else if (data.years.length > 0) {
                    yearSelect.value = data.years[0];
                    currentYear = data.years[0];
                }
                
                if (currentMonth && currentYear) {
                    document.getElementById('pendapatanHariIni').innerHTML = `Rp ${formatRupiah(data.pendapatanHariIni)}`;
                    document.getElementById('pendapatanBulanIni').innerHTML = `Rp ${formatRupiah(data.pendapatanBulanIni)}`;
                    loadDashboardData();
                    loadOmzetChart(7);
                    loadPaymentChart();
                    
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    function applyFilter() {
    currentMonth = document.getElementById('filterMonth').value;
    currentYear = document.getElementById('filterYear').value;
    
    if (!currentMonth || !currentYear) {
        document.getElementById('filterInfo').innerHTML = 'Silakan pilih bulan dan tahun terlebih dahulu';
        return;
    }
    
    const monthName = document.getElementById('filterMonth').options[document.getElementById('filterMonth').selectedIndex].text;
    document.getElementById('bulanTarget').innerHTML = `Pendapatan ${monthName} ${currentYear}`;
    document.getElementById('filterInfo').innerHTML = `Menampilkan data: ${monthName} ${currentYear}`;
    
    loadDashboardData();
    loadOmzetChart(document.getElementById('omzetFilter').value);
    loadPaymentChart(); 
}
    
    function loadDashboardData() {
        if (!currentMonth || !currentYear) return;
        
        fetch(`/api/admin/dashboard-data?month=${currentMonth}&year=${currentYear}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('pendapatanHariIni').innerHTML = `Rp ${formatRupiah(data.pendapatanHariIni)}`;
                document.getElementById('pendapatanBulanIni').innerHTML = `Rp ${formatRupiah(data.pendapatanBulanIni)}`;
                
                const trendHarianIcon = data.trendHarian === 'naik' ? 'bi-arrow-up' : (data.trendHarian === 'turun' ? 'bi-arrow-down' : 'bi-dash');
                const trendHarianColor = data.trendHarian === 'naik' ? 'text-green-500' : (data.trendHarian === 'turun' ? 'text-red-500' : 'text-gray-500');
                const trendHarianSign = data.persentaseHarian > 0 ? '+' : '';
                document.getElementById('trendHarian').innerHTML = `
                    <i class="bi ${trendHarianIcon}"></i> 
                    <span class="${trendHarianColor}">${trendHarianSign}${data.persentaseHarian}% dari kemarin</span>
                `;
                
                const trendBulananIcon = data.trendBulanan === 'naik' ? 'bi-arrow-up' : (data.trendBulanan === 'turun' ? 'bi-arrow-down' : 'bi-dash');
                const trendBulananColor = data.trendBulanan === 'naik' ? 'text-green-400' : (data.trendBulanan === 'turun' ? 'text-red-400' : 'text-gray-400');
                const trendBulananSign = data.persentaseBulanan > 0 ? '+' : '';
                document.getElementById('trendBulanan').innerHTML = `
                    <i class="bi ${trendBulananIcon}"></i> 
                    <span class="${trendBulananColor}">${trendBulananSign}${data.persentaseBulanan}% dari bulan lalu</span>
                `;
                
                const target = 15000000;
                const progress = (data.pendapatanBulanIni / target) * 100;
                document.getElementById('targetProgress').style.width = `${Math.min(progress, 100)}%`;
                
                const totalPendapatan = data.tunaiAmount + data.qrisAmount;
                const tunaiPersen = totalPendapatan > 0 ? ((data.tunaiAmount / totalPendapatan) * 100).toFixed(1) : 0;
                const qrisPersen = totalPendapatan > 0 ? ((data.qrisAmount / totalPendapatan) * 100).toFixed(1) : 0;
                document.getElementById('tunaiPersen').innerText = `${tunaiPersen}%`;
                document.getElementById('qrisPersen').innerText = `${qrisPersen}%`;
                
                if (window.paymentChartInstance) {
                    window.paymentChartInstance.data.datasets[0].data = [data.tunaiAmount, data.qrisAmount];
                    window.paymentChartInstance.update();
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    function loadOmzetChart(days = 7) {
        if (!currentMonth || !currentYear) return;
        
        fetch(`/api/admin/omzet-chart?days=${days}&month=${currentMonth}&year=${currentYear}`)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('omzetChart').getContext('2d');
                if (window.omzetChartInstance) window.omzetChartInstance.destroy();
                
                window.omzetChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Omzet (Rp)',
                            data: data.values,
                            borderColor: '#D73535',
                            backgroundColor: 'rgba(215, 53, 53, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#D73535',
                            pointBorderColor: 'white',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { ticks: { callback: (value) => `Rp ${value.toLocaleString('id-ID')}` } } }
                    }
                });
            })
            .catch(error => console.error('Error:', error));
    }
    

function loadPaymentChart() {
    if (!currentMonth || !currentYear) return;
    
    console.log('Loading payment chart for:', currentMonth, currentYear);
    
    fetch(`/api/admin/payment-chart?month=${currentMonth}&year=${currentYear}`)
        .then(response => response.json())
        .then(data => {
            console.log('Payment chart data:', data);
            
            const tunaiValue = parseFloat(data.tunai) || 0;
            const qrisValue = parseFloat(data.qris) || 0;
            const total = tunaiValue + qrisValue;
            
            // ✅ UPDATE PERSENTASE DI BAWAH CHART
            if (total > 0) {
                const tunaiPersen = ((tunaiValue / total) * 100).toFixed(1);
                const qrisPersen = ((qrisValue / total) * 100).toFixed(1);
                
                document.getElementById('tunaiPersen').innerHTML = tunaiPersen + '%';
                document.getElementById('qrisPersen').innerHTML = qrisPersen + '%';
                
                // ✅ UPDATE TOOLTIP (hover)
                document.getElementById('tunaiPersen').title = 'Rp ' + formatRupiah(tunaiValue);
                document.getElementById('qrisPersen').title = 'Rp ' + formatRupiah(qrisValue);
            } else {
                document.getElementById('tunaiPersen').innerHTML = '0%';
                document.getElementById('qrisPersen').innerHTML = '0%';
            }
            
            const ctx = document.getElementById('paymentChart').getContext('2d');
            if (window.paymentChartInstance) window.paymentChartInstance.destroy();
            
            window.paymentChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Tunai', 'QRIS'],
                    datasets: [{ 
                        data: [tunaiValue, qrisValue], 
                        backgroundColor: ['#D73535', '#F59E0B'], 
                        borderWidth: 0 
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { 
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: Rp ${formatRupiah(value)} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error loadPaymentChart:', error));
}
    // ==================== PREDIKSI ====================
    
    // PREDIKSI MANUAL
    function lakukanPrediksi() {
        const btn = document.getElementById('prediksiBtn');
        const originalText = '<i class="bi bi-magic"></i> Lakukan Prediksi';
        const targetMonth = document.getElementById('targetMonth').value;
        const targetYear = document.getElementById('targetYear').value;

        if (!targetYear) {
            Swal.fire('Error', 'Tahun target tidak boleh kosong', 'error');
            return;
        }

        btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Memproses...';
        btn.disabled = true;
        
        fetch('/api/admin/lakukan-prediksi', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                target_month: targetMonth,
                target_year: targetYear
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayPredictionResult(data);
                updateNotifications(); // Update notifikasi
                
                if (data.already_exists) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Info',
                        text: data.message,
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Prediksi berhasil dilakukan',
                        confirmButtonColor: '#3085d6'
                    });
                }
                
                // Kembalikan tombol ke keadaan semula
                btn.disabled = false;
                btn.innerHTML = originalText;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Gagal',
                    text: data.message,
                    confirmButtonColor: '#D73535'
                });
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', error.message, 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }
    

    
    function displayPredictionResult(data) {
        document.getElementById('periodeData').innerHTML = `Periode: ${data.periode}`;
        document.getElementById('bulanTarget').innerHTML = `Pendapatan Bulan Ini: ${data.bulan_target}`;
        
        let prediksiHtml = '';
        if (data.data && data.data.length > 0) {
            data.data.forEach((item, index) => {
                const medal = index === 0 ? '🥇' : (index === 1 ? '🥈' : '🥉');
                prediksiHtml += `
                    <div class="bg-white/10 rounded-xl p-3 text-center">
                        <div class="text-2xl mb-1">${medal}</div>
                        <div class="font-bold text-base">${item.nama_menu}</div>
                        <div class="text-xs text-white/80 mt-1">Prediksi: ${item.prediksi} porsi</div>
                        <div class="text-xs text-white/60 mt-1">${item.kenaikan > 0 ? '+' : ''}${item.kenaikan}%</div>
                    </div>
                `;
            });
        } else {
            prediksiHtml = '<div class="bg-white/10 rounded-xl p-4 text-center">Belum ada data prediksi</div>';
        }
        document.getElementById('hasilPrediksi').innerHTML = prediksiHtml;
        
        let rekomendasiHtml = '';
        if (data.rekomendasi && data.rekomendasi.length > 0) {
            rekomendasiHtml = `
                <div class="mt-6 bg-white/10 rounded-xl p-4">
                    <h3 class="font-bold mb-3 flex items-center gap-2 text-base">
                        <i class="bi bi-megaphone"></i> Rekomendasi Promosi Bulan Depan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        ${data.rekomendasi.map(promo => `
                            <div class="bg-white/20 rounded-lg p-3 hover:bg-white/30 transition">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-2xl">${promo.icon || ''}</span>
                                    <span class="font-semibold text-sm">${promo.judul}</span>
                                </div>
                                <p class="text-xs">${promo.deskripsi}</p>
                                <div class="text-xs text-yellow-300 mt-2 flex items-center gap-1">
                                    <i class="bi bi-tag"></i> Target: ${promo.menu}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        } else {
            rekomendasiHtml = `
                <div class="mt-6 bg-white/10 rounded-xl p-4 text-center">
                    <p class="text-sm text-white/80">Tidak ada rekomendasi promosi</p>
                </div>
            `;
        }
        
        let rekomendasiContainer = document.getElementById('rekomendasiPromosi');
        if (!rekomendasiContainer) {
            const prediksiSection = document.querySelector('#hasilPrediksi').parentNode;
            const newDiv = document.createElement('div');
            newDiv.id = 'rekomendasiPromosi';
            prediksiSection.appendChild(newDiv);
            rekomendasiContainer = newDiv;
        }
        rekomendasiContainer.innerHTML = rekomendasiHtml;
    }
    
    function cekStatusPrediksi() {
        // Tombol Lakukan Prediksi selalu aktif sekarang.
        // Fungsi ini hanya digunakan untuk sinkronisasi jika diperlukan di masa depan.
        // Update notifikasi secara reguler cukup menggunakan updateNotifications().
        updateNotifications();
    }
    
    // ==================== EVENT LISTENERS ====================
    document.getElementById('omzetFilter').addEventListener('change', function() {
        loadOmzetChart(this.value);
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        loadFilterOptions();
        
        // Update notifikasi pertama kali
        updateNotifications();
        
        // Update notifikasi setiap 5 detik (hanya notifikasi, tombol tidak terpengaruh)
        setInterval(() => {
            updateNotifications();
        }, 5000);
        
        // Update notifikasi saat halaman menjadi visible
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateNotifications();
            }
        });
    });
</script>
@endpush