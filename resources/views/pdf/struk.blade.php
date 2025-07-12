<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pemesanan Pempek</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        
        .receipt-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px dashed #e0e0e0;
        }
        
        h2 {
            color: #d35400;
            margin: 0;
            font-size: 24px;
        }
        
        .shop-info {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
        }
        
        .customer-info {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 120px;
            color: #555;
        }
        
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            background-color: white;
        }
        
        th {
            background-color: #d35400;
            color: white;
            padding: 10px;
            text-align: left;
        }
        
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .total-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f7ff;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px dashed #e0e0e0;
            font-size: 12px;
            color: #777;
        }
        
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
        }
        
        .status-paid {
            background-color: #2ecc71;
            color: white;
        }
        
        .status-unpaid {
            background-color: #e74c3c;
            color: white;
        }
        
        .status-pending {
            background-color: #f39c12;
            color: white;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h2>Struk Pemesanan Pempek</h2>
            <div class="shop-info">Pempek Delicious • Jl. Pempek No. 123 • 08123456789</div>
        </div>
        
        <div class="customer-info">
            <div class="info-row">
                <span class="info-label">Nama Customer:</span>
                <span>{{ $user->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal:</span>
                <span>
                    {{ $transaksi->created_at ? $transaksi->created_at->format('d-m-Y H:i') : 'N/A' }}
                </span>

            </div>
            <div class="info-row">
                <span class="info-label">Pengiriman:</span>
                <span>{{ $transaksi->pengiriman ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode Pembayaran:</span>
                <span>{{ $transaksi->metode_pembayaran ?? 'N/A' }}</span>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($pemesanans as $pesanan)
                    <tr>
                        <td>{{ $pesanan->menu->nama ?? 'N/A' }}</td>
                        <td>{{ $pesanan->jumlah ?? 0 }}</td>
                        <td>Rp {{ number_format($pesanan->harga_satuan ?? 0, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @php $total += $pesanan->total_harga ?? 0; @endphp
                @endforeach
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td colspan="3" style="text-align: right;">Total Pembayaran:</td>
                    <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="info-row" style="margin-top: 15px;">
            <span class="info-label">Status Bayar:</span>
            <span class="status status-{{ strtolower($transaksi->status_bayar ?? 'unpaid') }}">
                {{ ucfirst($transaksi->status_bayar ?? 'Belum dibayar') }}
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Status Pembayaran:</span>
            <span class="status status-{{ strtolower($pembayaran->status ?? 'unpaid') }}">
                {{ $pembayaran->status ?? 'Belum dibayar' }}
            </span>
        </div>

        @if($pembayaran && $pembayaran->bukti_bayar)
            <div class="info-row">
                <span class="info-label">Bukti Bayar:</span>
                <span>Terlampir</span>
            </div>
        @endif
        
        <div class="footer">
            Terima kasih telah memesan di Pempek Delicious<br>
            * Struk ini sah sebagai bukti pembayaran *
        </div>
    </div>
</body>
</html>