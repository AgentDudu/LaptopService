@php
use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan Sparepart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 14px;
            margin: 0;
            font-weight: bold;
        }

        .header p {
            margin: 2px 0;
            font-size: 10px;
        }

        .info-section, .sparepart-section, .total-section {
            margin-bottom: 10px;
        }

        .info-section table, .sparepart-section table, .total-section table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .info-section td, .sparepart-section td, .sparepart-section th, .total-section td {
            padding: 5px;
        }

        .sparepart-section th {
            border-bottom: 1px solid #000;
        }

        .sparepart-section td, .sparepart-section th {
            border-bottom: 1px solid #ddd;
        }

        .footer {
            text-align: right;
            font-size: 12px;
            margin-top: 20px;
        }

        .footer p {
            margin: 2px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>NOTA PENJUALAN SPAREPART</h1>
        <p>Laptop Cafe Jogjakarta</p>
        <p>Pusat sparepart repair, upgrade, jual beli laptop second jogja dan cafe.</p>
        <p>Jl. Paingan, Krodan, Maguwoharjo - Jogjakarta.</p>
        <p>Telp. WA 085771565199</p>
        <hr>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td>No. Faktur</td>
                <td>: {{ $transaksi_sparepart->id_transaksi_sparepart }}</td>
            </tr>
            <tr>
                <td>Tanggal Bayar</td>
                <td>: {{ $transaksi_sparepart->tanggal_jual }}</td>
            </tr>
            <tr>
                <td>Nama Pelanggan</td>
                <td>: {{ $transaksi_sparepart->pelanggan->nama_pelanggan }}</td>
            </tr>
            <tr>
                <td>No. HP</td>
                <td>: {{ $transaksi_sparepart->pelanggan->nohp_pelanggan }}</td>
            </tr>
        </table>
    </div>

    <div class="sparepart-section">
        <table>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi_sparepart->detail_transaksi_sparepart as $detail)
                    <tr>
                        <td>{{ $detail->sparepart->jenis_sparepart }}</td>
                        <td>{{ $detail->jumlah_sparepart_terjual }}</td>
                        <td>Rp {{ number_format($detail->sparepart->harga_sparepart, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->jumlah_sparepart_terjual * $detail->sparepart->harga_sparepart, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total-section">
        <table>
            <tr>
                <td>Harga Total</td>
                <td>: Rp {{ number_format($transaksi_sparepart->harga_total_transaksi_sparepart, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pembayaran</td>
                <td>: Rp {{ number_format($pembayaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td>: Rp {{ number_format($kembalian, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Teknisi: {{ Auth::user()->nama_teknisi }}</p>
    </div>
</body>

</html>
