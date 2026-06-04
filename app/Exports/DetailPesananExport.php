<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Detail Pesanan - PIZZAANNA</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            font-size: 10px;
            padding: 20px;
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
            padding: 6px;
            text-align: left;
            font-size: 8px;
        }
        td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 8px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PIZZAANNA</h1>
        <p>Jl. Pengging, Boyolali</p>
        <p>LAPORAN DETAIL PESANAN</p>
    </div>

    <div class="periode">
        Periode: {{ $periode }}<br>
        Tanggal Cetak: {{ $tanggalCetak }}
    </div>

    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal & Jam</th>
                <th>No. Invoice</th>
                <th>Customer</th>
                <th>Pesanan</th>
                <th>Qty</th>
                <th>Ukuran</th>
                <th>Harga Satuan</th>
                <th>Total</th>
                <th>Topping</th>
                <th>Metode Bayar</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($details as $detail)
            @php
                $toppingNames = '';
                if ($detail->toppings && count($detail->toppings) > 0) {
                    $toppingList = [];
                    foreach ($detail->toppings as $topping) {
                        $toppingList[] = $topping->nama_topping;
                    }
                    $toppingNames = implode(', ', $toppingList);
                }
                $tanggal = date('d/m/Y H.i', strtotime($detail->pesanan->tanggal));
                $hargaSatuan = 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.');
                $total = 'Rp ' . number_format($detail->subtotal, 0, ',', '.');
                $metodeBayar = ucfirst($detail->pesanan->metode_bayar ?? '-');
            @endphp
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $tanggal }}</td>
                <td>{{ $detail->pesanan->no_invoice ?? '-' }}</td>
                <td>{{ $detail->pesanan->nama_customer ?? '-' }}</td>
                <td>{{ $detail->menu->nama_menu ?? '-' }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>{{ $detail->ukuran ?? '-' }}</td>
                <td>{{ $hargaSatuan }}</td>
                <td>{{ $total }}</td>
                <td>{{ $toppingNames ?: '-' }}</td>
                <td>{{ $metodeBayar }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="9" style="text-align: right;"><strong>GRAND TOTAL:</strong></td>
                <td><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ $namaUser }}</p>
        <p>&copy; {{ date('Y') }} PIZZAANNA - Sistem Informasi Restoran Terintegrasi</p>
    </div>
</body>
</html>