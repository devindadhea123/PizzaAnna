@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500">Welcome back, {{ Auth::user()->nama_lengkap ?? 'Admin' }}!</p>
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
        
        <!-- ✅ NOTIFIKASI PREDIKSI -->
        @php
            $today = now();
            $currentHour = (int)$today->format('H');
            $isPredictionDay = ($today->day == 27);
            $bulanTarget = now()->addMonth()->format('F Y');
            $existingPrediksi = App\Models\RiwayatPrediksi::where('bulan_target', now()->addMonth()->format('Y-m'))->first();
        @endphp

        @if($isPredictionDay && !$existingPrediksi && $currentHour < 12)
            <!-- Notifikasi WARNING (sebelum jam 12) -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded-lg shadow-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-bell-fill text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="font-bold text-lg">Hari ini tanggal 27! Waktunya Prediksi!</p>
                        <p class="text-sm mt-1">Lakukan prediksi menu terlaris untuk <span class="font-semibold">{{ $bulanTarget }}</span>.</p>
                        <p class="text-xs mt-1 text-yellow-600">
                            <i class="bi bi-clock"></i> Anda masih punya waktu hingga <span class="font-bold">jam 12:00 siang</span>.
                            <span class="font-semibold">Belum ada prediksi untuk bulan depan.</span>
                        </p>
                        <div class="mt-2 flex gap-2">
                            <div class="bg-yellow-100 rounded-full px-3 py-1 text-xs">
                                <i class="bi bi-hand-index-thumb"></i> Klik tombol "Lakukan Prediksi" sekarang
                            </div>
                            <div class="bg-gray-100 rounded-full px-3 py-1 text-xs">
                                <i class="bi bi-clock-history"></i> Atau tunggu sistem otomatis jam 12:00
                            </div>
                        </div>
                    </div>
                    <button onclick="this.parentElement.parentElement.style.display='none'" class="text-yellow-500 hover:text-yellow-700">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
       
        @elseif($isPredictionDay && $currentHour >= 12 && !$existingPrediksi)
            <!-- Notifikasi INFO (sudah lewat jam 12, prediksi akan otomatis) -->
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded-lg shadow-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-hourglass-split text-2xl text-blue-600 animate-spin"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="font-bold text-lg">Sistem Sedang Memproses Prediksi</p>
                        <p class="text-sm mt-1">Waktu prediksi manual telah berakhir. Sistem akan menjalankan prediksi otomatis.</p>
                        <p class="text-xs mt-1 text-blue-600">Mohon tunggu, halaman akan refresh setelah prediksi selesai.</p>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="text-xl font-bold">Prediksi Menu Terlaris</h2>
                <p class="text-white/80 text-sm mt-1">Berdasarkan data penjualan 6 bulan terakhir</p>
                <p class="text-white/60 text-xs mt-2" id="periodeData">Periode: -</p>
            </div>
            <button onclick="lakukanPrediksi()" id="prediksiBtn" class="bg-yellow-400 text-gray-900 px-6 py-2 rounded-full font-semibold hover:bg-yellow-500 transition flex items-center gap-2">
                <i class="bi bi-magic"></i> Lakukan Prediksi
            </button>
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
    let pollingInterval = null;
    let predictionChecked = false;
    let autoPredictionTriggered = false;
    let intensiveInterval = null;
    let statusCheckInterval = null;
    
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
                
                if (!notificationContainer) {
                    const prediksiSection = document.querySelector('.bg-gradient-to-r');
                    if (prediksiSection) {
                        const newNotifDiv = document.createElement('div');
                        newNotifDiv.id = 'dynamic-notifications';
                        prediksiSection.insertBefore(newNotifDiv, prediksiSection.firstChild);
                        notificationContainer = newNotifDiv;
                    }
                }
                
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
                    else if (data.is_prediction_day && data.is_before_deadline && !data.has_prediction) {
                        notifHtml = `
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded-lg shadow-md">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-bell-fill text-2xl text-yellow-600"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="font-bold text-lg">Hari ini tanggal 27! Waktunya Prediksi!</p>
                                        <p class="text-sm mt-1">Lakukan prediksi menu terlaris untuk <span class="font-semibold">${data.bulan_target || 'bulan depan'}</span>.</p>
                                        <p class="text-xs mt-1 text-yellow-600">
                                            <i class="bi bi-clock"></i> Anda masih punya waktu hingga <span class="font-bold">jam 12:00 siang</span>.
                                            <span class="font-semibold">Belum ada prediksi untuk bulan depan.</span>
                                        </p>
                                        <div class="mt-2 flex gap-2">
                                            <div class="bg-yellow-100 rounded-full px-3 py-1 text-xs">
                                                <i class="bi bi-hand-index-thumb"></i> Klik tombol "Lakukan Prediksi" sekarang
                                            </div>
                                            <div class="bg-gray-100 rounded-full px-3 py-1 text-xs">
                                                <i class="bi bi-clock-history"></i> Atau tunggu sistem otomatis jam 12:00
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                    // Kasus 3: Sudah lewat deadline tapi belum ada prediksi
                    else if (data.is_prediction_day && !data.is_before_deadline && !data.has_prediction) {
                        notifHtml = `
                            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded-lg shadow-md">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-hourglass-split text-2xl text-blue-600 animate-spin"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="font-bold text-lg"> Sistem Sedang Memproses Prediksi</p>
                                        <p class="text-sm mt-1">Waktu prediksi manual telah berakhir. Sistem akan menjalankan prediksi otomatis.</p>
                                        <p class="text-xs mt-1 text-blue-600">Mohon tunggu, halaman akan refresh setelah prediksi selesai.</p>
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
        loadTopMenu();
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
        
        fetch(`/api/admin/payment-chart?month=${currentMonth}&year=${currentYear}`)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('paymentChart').getContext('2d');
                if (window.paymentChartInstance) window.paymentChartInstance.destroy();
                
                window.paymentChartInstance = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Tunai', 'QRIS'],
                        datasets: [{ data: [data.tunai, data.qris], backgroundColor: ['#D73535', '#F59E0B'], borderWidth: 0 }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            })
            .catch(error => console.error('Error:', error));
    }
    // ==================== PREDIKSI ====================
    
    // PREDIKSI MANUAL
    function lakukanPrediksi() {
        const btn = document.getElementById('prediksiBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Memproses...';
        btn.disabled = true;
        
        fetch('/api/admin/lakukan-prediksi', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayPredictionResult(data);
                updateNotifications(); // Update notifikasi setelah prediksi berhasil
                btn.disabled = true;
                btn.innerHTML = 'Prediksi Selesai';
                btn.classList.remove('bg-yellow-400', 'hover:bg-yellow-500', 'text-gray-900');
                btn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
                predictionChecked = true;
                autoPredictionTriggered = true;
                stopAllIntervals();
            } else {
                alert('Gagal: ' + data.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: ' + error.message);
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
    
    // INTENSIVE CHECK UNTUK AUTO PREDIKSI
    function startIntensiveCheck() {
        if (intensiveInterval) return;
        
        console.log('📡 Memulai intensive check untuk auto prediksi (cek setiap detik)');
        
        intensiveInterval = setInterval(() => {
            fetch('/api/admin/prediction-status')
                .then(response => response.json())
                .then(data => {
                    if (data.need_auto_prediction && !autoPredictionTriggered && !predictionChecked) {
                        console.log('Waktunya auto prediksi! Menjalankan...');
                        clearInterval(intensiveInterval);
                        intensiveInterval = null;
                        lakukanPrediksiOtomatis();
                    }
                    if (data.has_prediction) {
                        if (intensiveInterval) {
                            clearInterval(intensiveInterval);
                            intensiveInterval = null;
                        }
                        updateNotifications(); // Update notifikasi jika sudah ada prediksi
                    }
                })
                .catch(err => console.error('Intensive check error:', err));
        }, 1000);
    }
    
    // PREDIKSI OTOMATIS
    function lakukanPrediksiOtomatis() {
        if (predictionChecked || autoPredictionTriggered) {
            console.log('Prediksi sudah ada, skip auto prediksi');
            return;
        }
        
        console.log('🤖 Menjalankan prediksi otomatis...');
        autoPredictionTriggered = true;
        
        const btn = document.getElementById('prediksiBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Prediksi Otomatis...';
        btn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
        
        fetch('/api/admin/lakukan-prediksi', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ auto: true })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Hasil auto prediksi:', data);
            
            if (data.success) {
                displayPredictionResult(data);
                updateNotifications(); // Update notifikasi setelah auto prediksi berhasil
                predictionChecked = true;
                
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-check-circle"></i> Prediksi Selesai';
                btn.classList.remove('bg-yellow-400', 'hover:bg-yellow-500', 'text-gray-900');
                btn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
                
                cekStatusPrediksi();
                stopAllIntervals();
            } else {
                console.error('Auto prediksi gagal:', data.message);
                autoPredictionTriggered = false;
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-magic"></i> Coba Lagi';
                btn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
                btn.classList.add('bg-yellow-400', 'hover:bg-yellow-500', 'text-gray-900');
            }
        })
        .catch(error => {
            console.error('Error auto prediksi:', error);
            autoPredictionTriggered = false;
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-magic"></i> Coba Lagi';
            btn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
            btn.classList.add('bg-yellow-400', 'hover:bg-yellow-500', 'text-gray-900');
        });
    }
    
    // FUNGSI UNTUK MENGHENTIKAN SEMUA INTERVAL
    function stopAllIntervals() {
        if (intensiveInterval) {
            clearInterval(intensiveInterval);
            intensiveInterval = null;
        }
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
            statusCheckInterval = null;
        }
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
        console.log('Semua interval telah dihentikan');
    }
    
    function displayPredictionResult(data) {
        document.getElementById('periodeData').innerHTML = `Periode: ${data.periode}`;
        document.getElementById('bulanTarget').innerHTML = `Prediksi untuk: ${data.bulan_target}`;
        
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
    
    function loadLatestPrediction() {
        fetch('/api/admin/prediction-latest')
            .then(response => response.json())
            .then(data => {
                console.log('Load latest prediction:', data);
                
                if (data.has_prediction && data.data) {
                    displayPredictionResult(data);
                    predictionChecked = true;
                    autoPredictionTriggered = true;
                    
                    const btn = document.getElementById('prediksiBtn');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="bi bi-check-circle"></i> Prediksi Selesai';
                    btn.classList.remove('bg-yellow-400', 'hover:bg-yellow-500', 'text-gray-900');
                    btn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
                    
                    stopAllIntervals();
                }
            })
            .catch(err => console.error('Error load latest:', err));
    }
    
    function startPollingPrediction() {
        if (pollingInterval) return;
        
        pollingInterval = setInterval(() => {
            if (!predictionChecked) {
                fetch('/api/admin/prediction-latest')
                    .then(response => response.json())
                    .then(data => {
                        if (data.has_prediction) {
                            clearInterval(pollingInterval);
                            pollingInterval = null;
                            displayPredictionResult(data);
                            updateNotifications(); // Update notifikasi
                            predictionChecked = true;
                            autoPredictionTriggered = true;
                            
                            const btn = document.getElementById('prediksiBtn');
                            btn.disabled = true;
                            btn.innerHTML = '<i class="bi bi-check-circle"></i> Prediksi Selesai';
                            btn.classList.remove('bg-yellow-400', 'hover:bg-yellow-500', 'text-gray-900');
                            btn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
                            
                            stopAllIntervals();
                        }
                    })
                    .catch(err => console.error('Polling error:', err));
            } else {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }
        }, 5000);
    }
    
    function cekStatusPrediksi() {
        fetch('/api/admin/prediction-status')
            .then(response => response.json())
            .then(data => {
                const btn = document.getElementById('prediksiBtn');
                
                console.log('Status:', data);
                
                // Update notifikasi setiap kali cek status
                updateNotifications();
                
                // PRIORITAS 1: SUDAH ADA PREDIKSI
                if (data.has_prediction) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="bi bi-check-circle"></i> Prediksi Selesai';
                    btn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
                    btn.classList.remove('bg-yellow-400', 'hover:bg-yellow-500', 'text-gray-900');
                    
                    if (!predictionChecked) {
                        loadLatestPrediction();
                        predictionChecked = true;
                    }
                    
                    stopAllIntervals();
                    return;
                }
                
                // PRIORITAS 2: MASIH BISA PREDIKSI MANUAL (sebelum deadline)
                if (data.can_predict && data.is_prediction_day && data.is_before_deadline) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-magic"></i> Lakukan Prediksi Manual';
                    btn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
                    btn.classList.add('bg-yellow-400', 'hover:bg-yellow-500', 'text-gray-900');
                    return;
                }
                
                // PRIORITAS 3: SUDAH LEWAT DEADLINE - JALANKAN AUTO PREDIKSI LANGSUNG
                if (data.is_prediction_day && !data.is_before_deadline && !data.has_prediction) {
                    if (data.need_auto_prediction && !autoPredictionTriggered && !predictionChecked) {
                        console.log('Deadline tercapai! Menjalankan prediksi otomatis...');
                        
                        btn.disabled = true;
                        btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Prediksi Otomatis...';
                        btn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
                        
                        if (intensiveInterval) {
                            clearInterval(intensiveInterval);
                            intensiveInterval = null;
                        }
                        
                        lakukanPrediksiOtomatis();
                    } else {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Menunggu Prediksi...';
                        btn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
                    }
                    return;
                }
                
                // PRIORITAS 4: BUKAN HARI PREDIKSI
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-calendar-x"></i> Prediksi Tidak Tersedia';
                btn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-500');
            })
            .catch(err => console.error('Error cek status:', err));
    }
    
    // ==================== EVENT LISTENERS ====================
    document.getElementById('omzetFilter').addEventListener('change', function() {
        loadOmzetChart(this.value);
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        loadFilterOptions();
        cekStatusPrediksi();
        
        // Update notifikasi pertama kali
        updateNotifications();
        
        // Update notifikasi setiap 5 detik
        setInterval(() => {
            updateNotifications();
        }, 5000);
        
        // Update notifikasi saat halaman menjadi visible
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateNotifications();
            }
        });
        
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
        }
        statusCheckInterval = setInterval(cekStatusPrediksi, 2000);
        
        loadLatestPrediction();
        
        fetch('/api/admin/prediction-status')
            .then(response => response.json())
            .then(data => {
                if (data.is_prediction_day && !data.has_prediction && data.is_before_deadline) {
                    console.log('Memulai intensive check untuk auto prediksi');
                    startIntensiveCheck();
                }
            })
            .catch(err => console.error('Error cek intensive:', err));
    });
</script>
@endpush