<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Prediksi - PIZZAANNA</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            font-size: 10px;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #D73535;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #D73535;
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 10px;
        }
        .periode {
            margin-bottom: 15px;
            font-size: 10px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #D73535;
            color: white;
            padding: 8px;
            text-align: center;
            font-size: 9px;
            border: 1px solid #ddd;
        }
        td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 9px;
        }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .akurasi-hijau { background: #d1fae5; color: #065f46; display: inline-block; padding: 2px 8px; border-radius: 12px; }
        .akurasi-kuning { background: #fef3c7; color: #92400e; display: inline-block; padding: 2px 8px; border-radius: 12px; }
        .akurasi-oranye { background: #ffedd5; color: #9a3412; display: inline-block; padding: 2px 8px; border-radius: 12px; }
        .akurasi-merah { background: #fee2e2; color: #991b1b; display: inline-block; padding: 2px 8px; border-radius: 12px; }
        .akurasi-abu { background: #f3f4f6; color: #6b7280; display: inline-block; padding: 2px 8px; border-radius: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PIZZAANNA</h1>
        <p>Jl. Pengging, Boyolali</p>
        <p>LAPORAN RIWAYAT PREDIKSI</p>
    </div>
    <div class="periode">
        Periode: {{ $periode }}<br>
        Tanggal Cetak: {{ $tanggal_cetak }}
    </div>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Prediksi</th>
                <th>Bulan Target</th>
                <th>Data yang Dipakai</th>
                <th>Top 1 Menu</th>
                <th>Top 1 Prediksi</th>
                <th>Top 2 Menu</th>
                <th>Top 2 Prediksi</th>
                <th>Top 3 Menu</th>
                <th>Top 3 Prediksi</th>
                <th>Akurasi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($predictions as $pred)
            @php
                $hasil = $pred->hasil_prediksi;
                if (is_string($hasil)) {
                    $hasil = json_decode($hasil, true);
                }
                if (!is_array($hasil)) {
                    $hasil = [];
                }
                $top1 = $hasil[0] ?? ['nama_menu' => '-', 'prediksi' => '-'];
                $top2 = $hasil[1] ?? ['nama_menu' => '-', 'prediksi' => '-'];
                $top3 = $hasil[2] ?? ['nama_menu' => '-', 'prediksi' => '-'];
                $akurasi = $pred->rata_rata_akurasi ?? 0;
                
                $akurasiClass = '';
                if ($akurasi >= 90) $akurasiClass = 'akurasi-hijau';
                elseif ($akurasi >= 80) $akurasiClass = 'akurasi-kuning';
                elseif ($akurasi >= 70) $akurasiClass = 'akurasi-oranye';
                elseif ($akurasi > 0) $akurasiClass = 'akurasi-merah';
                else $akurasiClass = 'akurasi-abu';
                $akurasiText = $akurasi > 0 ? $akurasi . '%' : '-';
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ date('d/m/Y H:i:s', strtotime($pred->tanggal_prediksi)) }}</td>
                <td class="text-center">{{ $pred->bulan_target }}</td>
                <td class="text-left">{{ $pred->data_yang_dipakai }}</td>
                <td class="text-left">{{ $top1['nama_menu'] }}</td>
                <td class="text-center">{{ $top1['prediksi'] ?? '-' }}</td>
                <td class="text-left">{{ $top2['nama_menu'] }}</td>
                <td class="text-center">{{ $top2['prediksi'] ?? '-' }}</td>
                <td class="text-left">{{ $top3['nama_menu'] }}</td>
                <td class="text-center">{{ $top3['prediksi'] ?? '-' }}</td>
                <td class="text-center"><span class="{{ $akurasiClass }}">{{ $akurasiText }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->nama_lengkap ?? 'Admin' }}</p>
        <p>&copy; {{ date('Y') }} PIZZAANNA - Sistem Informasi Restoran Terintegrasi</p>
    </div>
</body>
</html>