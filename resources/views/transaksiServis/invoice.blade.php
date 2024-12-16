@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Servis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
        }

        .header p {
            margin: 3px 0;
            font-size: 10px;
        }

        .info-section,
        .service-section,
        .total-section {
            margin-bottom: 15px;
        }

        .info-section table,
        .service-section table,
        .total-section table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .info-section td,
        .service-section td,
        .service-section th,
        .total-section td {
            padding: 5px;
            vertical-align: top;
        }

        .info-section td:first-child {
            width: 100px;
        }

        .service-section th {
            text-align: left;
        }

        .service-section td,
        .service-section th {
            border-bottom: 1px solid #ddd;
        }

        .total-section td {
            padding-top: 5px;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 12px;
        }

        .footer .teknisi {
            margin-top: 10px;
        }

        hr {
            border: none;
            border-top: 1px solid black;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <div class="header">
        <h1>NOTA SERVIS</h1>
        <p>Laptop Cafe Jogjakarta</p>
        <p>Pusat service repair, upgrade, jual beli laptop second jogja dan cafe.</p>
        <p>Jl. Paingan, Krodan, Maguwoharjo - Jogjakarta.</p>
        <p>Telp. WA 085771565199</p>
        <hr>
    </div>

    <!-- Information Section -->
    <div class="info-section">
        <table>
            <tr>
                <td>No. Faktur</td>
                <td>: {{ $transaksiServis->id_service }}</td>
            </tr>
            <tr>
                <td>Tanggal Bayar</td>
                <td>: {{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>Nama User</td>
                <td>: {{ $transaksiServis->pelanggan->nama_pelanggan }}</td>
            </tr>
            <tr>
                <td>No. HP</td>
                <td>: {{ $transaksiServis->pelanggan->nohp_pelanggan }}</td>
            </tr>
        </table>
    </div>

    <!-- Service Data Section -->
    <div class="service-section">
        <h3>Data Servis</h3>
        <table>
            <tr>
                <td>Tanggal Masuk</td>
                <td>: {{ date('d-m-Y', strtotime($transaksiServis->tanggal_masuk)) }}</td>
            </tr>
            <tr>
                <td>Tanggal Keluar</td>
                <td>: {{ date('d-m-Y', strtotime($transaksiServis->tanggal_keluar)) }}</td>
            </tr>
            <tr>
                <td>Merek Laptop</td>
                <td>: {{ $transaksiServis->laptop->merek_laptop }}</td>
            </tr>
            <tr>
                <td>Keluhan</td>
                <td>: {{ $transaksiServis->laptop->deskripsi_masalah }}</td>
            </tr>
            <tr>
                <td>Servis yang dilakukan</td>
                <td>: {{ $transaksiServis->detailTransaksiServis->pluck('jasaServis.jenis_jasa')->join(', ') }}</td>
            </tr>
            <tr>
                <td>Garansi</td>
                <td>: {{ $transaksiServis->detailTransaksiServis->pluck('jangka_garansi_bulan')->join(', ') }}</td>
            </tr>
            <tr>
                <td>Tambahan Sparepart</td>
                <td>:
                    @foreach ($transaksiServis->detailTransaksiServis as $detail)
                        {{ $detail->sparepart ? $detail->sparepart->jenis_sparepart : '' }}
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>Harga Total</td>
                <td>: Rp. {{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Payment and Change Section -->
    <div class="total-section">
        <table>
            <tr>
                <td><strong>Pembayaran</strong></td>
                <td>: Rp. {{ number_format($pembayaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Kembalian</strong></td>
                <td>: Rp. {{ number_format($kembalian, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>Teknisi</p>
        <p class="teknisi">{{ Auth::user()->nama_teknisi }}</p>
    </div>

</body>

</html>
