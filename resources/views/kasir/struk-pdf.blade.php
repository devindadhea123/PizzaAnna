<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Pesanan - {{ $invoice_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            padding: 20px;
            margin: 0;
        }
        .struk {
            max-width: 300px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #D73535;
        }
        .header p {
            margin: 5px 0;
            font-size: 10px;
            color: #666;
        }
        .info {
            margin-bottom: 10px;
            font-size: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .items {
            border-top: 1px dashed #ddd;
            border-bottom: 1px dashed #ddd;
            padding: 10px 0;
            margin-bottom: 10px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-bottom: 5px;
        }
        .total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 12px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            color: #999;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #ddd;
        }
        .metode {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="struk">
        <div class="header">
            <h2>PIZZAANNA</h2>
            <p>Jl. Pengging, Boyolali</p>
            <p>{{ $tanggal }}</p>
            <p><strong>{{ $invoice_number }}</strong></p>
        </div>
        
        <div class="info">
            <div class="info-row">
                <span>Customer:</span>
                <strong>{{ $customer_name }}</strong>
            </div>
            <div class="info-row">
                <span>Tipe Pesanan:</span>
                <strong>{{ $order_type == 'dine_in' ? 'Dine In' : 'Take Away' }}</strong>
            </div>
            @if($order_type == 'dine_in')
            <div class="info-row">
                <span>No Meja:</span>
                <strong>{{ $no_meja }}</strong>
            </div>
            @endif
        </div>
        
        <div class="items">
            @foreach($order_data['items'] as $item)
            <div class="item">
                <span>{{ $item['name'] }} x{{ $item['qty'] }}</span>
                <span>Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
        
        <div class="total">
            <span>TOTAL</span>
            <span style="color: #D73535;">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
        
        <div class="metode">
            <span>Metode Pembayaran:</span>
            <strong>{{ $payment_method == 'tunai' ? 'Tunai' : 'QRIS' }}</strong>
        </div>
        
        <div class="footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>❤️ PizzaAnna - Pizza Lezat untuk Semua ❤️</p>
        </div>
    </div>
</body>
</html>