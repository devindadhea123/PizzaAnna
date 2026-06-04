<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Riwayat Prediksi - PIZZAANNA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #D73535;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #D73535;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
            color: #666;
        }
        .periode {
            margin-bottom: 20px;
            font-size: 11px;
            font-weight: bold;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #D73535;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #fff;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 10px;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .rekomendasi-cell {
            white-space: pre-wrap;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PIZZAANNA</h1>
        <p>Jl. Pengging, Boyolali</p>
        <p>Laporan Riwayat Prediksi Menu Terlaris</p>
        <p>Metode: Weighted Moving Average (WMA)</p>
    </div>

    <div class="periode">
        Periode: {{ $periode }}
        <br>
        Tanggal Cetak: {{ $tanggal_cetak }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal Prediksi</th>
                <th width="10%">Bulan Target</th>
                <th width="15%">Periode Data</th>
                <th width="12%">Top 1 Menu</th>
                <th width="8%">Top 1 Prediksi</th>
                <th width="12%">Top 2 Menu</th>
                <th width="8%">Top 2 Prediksi</th>
                <th width="12%">Top 3 Menu</th>
                <th width="8%">Top 3 Prediksi</th>
                <th width="18%">Rekomendasi Promosi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($predictions as $prediction)
            @php
                $hasilPrediksi = $prediction->hasil_prediksi;
                if (is_string($hasilPrediksi)) {
                    $hasilPrediksi = json_decode($hasilPrediksi, true);
                }
                if (!is_array($hasilPrediksi)) {
                    $hasilPrediksi = [];
                }
                
                $top1 = $hasilPrediksi[0] ?? ['nama_menu' => '-', 'prediksi' => '-'];
                $top2 = $hasilPrediksi[1] ?? ['nama_menu' => '-', 'prediksi' => '-'];
                $top3 = $hasilPrediksi[2] ?? ['nama_menu' => '-', 'prediksi' => '-'];
                
                $rekomendasi = $prediction->rekomendasi_promosi;
                if (is_string($rekomendasi)) {
                    $rekomendasi = json_decode($rekomendasi, true);
                }
                if (!is_array($rekomendasi)) {
                    $rekomendasi = [];
                }
                
                $rekomendasiText = '';
                foreach ($rekomendasi as $rec) {
                    $rekomendasiText .= '- ' . ($rec['judul'] ?? 'Promo') . ': ' . ($rec['deskripsi'] ?? '') . "\n";
                }
                if ($rekomendasiText == '') {
                    $rekomendasiText = '-';
                }
                
                $date = new DateTime($prediction->tanggal_prediksi);
                $formattedDate = $date->format('d/m/Y H:i:s');
            @endphp
            <tr>
                <td>{{ $no }}</td>
                <td>{{ $formattedDate }}</td>
                <td>{{ $prediction->bulan_target }}</td>
                <td>{{ $prediction->data_yang_dipakai }}</td>
                <td>{{ $top1['nama_menu'] }}</td>
                <td>{{ $top1['prediksi'] }} porsi</div></td>
                <td>{{ $top2['nama_menu'] }}</div></td>
                <td>{{ $top2['prediksi'] }} porsi</div></td>
                <td>{{ $top3['nama_menu'] }}</div></td>
                <td>{{ $top3['prediksi'] }} porsi</div></td>
                <td class="rekomendasi-cell">{{ nl2br($rekomendasiText) }}</div></td>
            </tr>
            @php $no++; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->nama_lengkap ?? 'Admin' }}</p>
        <p>&copy; {{ date('Y') }} PIZZAANNA - Sistem Informasi Restoran Terintegrasi</p>
        <p>* Data prediksi dihitung menggunakan metode Weighted Moving Average (WMA) berdasarkan 6 bulan terakhir</p>
    </div>
</body>
</html>