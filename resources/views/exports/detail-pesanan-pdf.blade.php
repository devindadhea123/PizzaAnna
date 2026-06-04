<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Detail Pesanan - PIZZAANNA</title>
    <style>
        body { font-family: "Arial", sans-serif; font-size: 11px; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #D73535; padding-bottom: 10px; }
        .header h1 { color: #D73535; margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; font-size: 11px; }
        .periode { margin-bottom: 15px; font-size: 11px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #D73535; color: white; padding: 8px; text-align: center; font-size: 10px; border: 1px solid #000; }
        td { border: 1px solid #000; padding: 6px; font-size: 10px; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
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
                <th class="text-center">No</th>
                <th class="text-center">Tanggal & Jam</th>
                <th class="text-center">No. Invoice</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Pesanan</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Ukuran</th>
                <th class="text-center">Harga Satuan</th>
                <th class="text-center">Total</th>
                <th class="text-center">Topping</th>
                <th class="text-center">Metode Bayar</th>
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
                $metodeBayar = $detail->pesanan->metode_bayar == 'tunai' ? 'Tunai' : 'QRIS';
                $ukuran = $detail->ukuran ?: '-';
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $tanggal }}</td>
                <td class="text-center">{{ $detail->pesanan->no_invoice ?? '-' }}</td>
                <td class="text-left">{{ $detail->pesanan->nama_customer ?? '-' }}</td>
                <td class="text-left">{{ $detail->menu->nama_menu ?? '-' }}</td>
                <td class="text-center">{{ $detail->jumlah }}</td>
                <td class="text-center">{{ $ukuran }}</td>
                <td class="text-right">{{ $hargaSatuan }}</td>
                <td class="text-right">{{ $total }}</td>
                <td class="text-left">{{ $toppingNames ?: '-' }}</td>
                <td class="text-center">{{ $metodeBayar }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="9" class="text-right"><strong>GRAND TOTAL:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                <td class="text-center"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ $namaUser }}</p>
        <p>&copy; {{ date('Y') }} PIZZAANNA - Sistem Informasi Restoran Terintegrasi</p>
    </div>
</body>
</html>