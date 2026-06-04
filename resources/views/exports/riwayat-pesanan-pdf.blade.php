<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan - PIZZAANNA</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
            padding: 8px;
            text-align: left;
            font-size: 9px;
        }
        td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 9px;
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
        <p>Laporan Riwayat Pesanan</p>
    </div>

    <div class="periode">
        Periode: {{ $periode }}
        <br>
        Tanggal Cetak: {{ $tanggal_cetak }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal & Jam</th>
                <th>No. Invoice</th>
                <th>Customer</th>
                <th>Meja</th>
                <th>Total</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($orders as $order)
            @php
                $date = new DateTime($order->tanggal);
                $formattedDate = $date->format('d/m/Y H:i:s');
                $mejaText = $order->no_meja ? 'Meja '.$order->no_meja : 'Take Away';
                $metodeText = $order->metode_bayar == 'tunai' ? 'Tunai' : 'QRIS';
            @endphp
            <tr>
                <td>{{ $no }}</td>
                <td>{{ $formattedDate }}</td>
                <td>{{ $order->no_invoice }}</td>
                <td>{{ $order->nama_customer }}</td>
                <td>{{ $mejaText }}</td>
                <td>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                <td>{{ $metodeText }}</td>
            </tr>
            @php $no++; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" style="text-align: right;"><strong>GRAND TOTAL:</strong></td>
                <td><strong>Rp {{ number_format($orders->sum('total_bayar'), 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->nama_lengkap }}</p>
        <p>&copy; {{ date('Y') }} PIZZAANNA - Sistem Informasi Restoran Terintegrasi</p>
    </div>
</body>
<script>// ==================== EXPORT FUNCTIONS ====================
function exportToExcel() {
    const month = document.getElementById('filterMonth').value;
    const year = document.getElementById('filterYear').value;
    
    let url = `/api/admin/orders/export/excel`;
    let params = [];
    
    if (month) params.push(`month=${month}`);
    if (year) params.push(`year=${year}`);
    
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    
    window.location.href = url;
}

function exportToPDF() {
    const month = document.getElementById('filterMonth').value;
    const year = document.getElementById('filterYear').value;
    
    let url = `/api/admin/orders/export/pdf`;
    let params = [];
    
    if (month) params.push(`month=${month}`);
    if (year) params.push(`year=${year}`);
    
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    
    window.location.href = url;
}
</script
</html>