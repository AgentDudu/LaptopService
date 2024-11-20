@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan Sparepart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        <style>body {
            background-color: #F8F9FA;
        }

        .main-container {
            display: flex;
            background-color: #067D40;
        }

        .sidebar {
            width: 260px;
            background-color: #067D40;
        }

        .content {
            flex: 1;
            padding: 20px;
            margin: 30px 30px 30px 0;
            background-color: #F8F9FA;
            border-radius: 20px;
        }

        /* body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        } */

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
        .sparepart-section,
        .total-section {
            margin-bottom: 15px;
        }

        .info-section table,
        .sparepart-section table,
        .total-section table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .info-section td,
        .sparepart-section td,
        .sparepart-section th,
        .total-section td {
            padding: 5px;
            vertical-align: top;
        }

        .info-section td:first-child {
            width: 100px;
        }

        .sparepart-section th {
            text-align: left;
        }

        .sparepart-section td,
        .sparepart-section th {
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

        /* Style for print */
        @media print {

            /* Hide everything except for the printed content */
            body * {
                visibility: hidden;
            }

            .printable,
            .printable * {
                visibility: visible;
            }

            .header,
            .footer,
            .info-section,
            .sparepart-section,
            .total-section {
                margin: 0;
            }

            .footer {
                text-align: left;
                margin-top: 10px;
            }
              /* Hide the sidebar for print */
              .sidebar {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar text-white">
            @if (Auth::user()->status === 'Pegawai')
                <x-sidebar-nonadmin />
            @else
                <x-sidebar-admin />
            @endif
        </div>

        <main class="content">
            <div class="container">
                <!-- Header Section -->
                <div class="header printable">
                    <h1>NOTA PENJUALAN SPAREPART</h1>
                    <p>Laptop Cafe Jogjakarta</p>
                    <p>Pusat sparepart repair, upgrade, jual beli laptop second jogja dan cafe.</p>
                    <p>Jl. Paingan, Krodan, Maguwoharjo - Jogjakarta.</p>
                    <p>Telp. WA 085771565199</p>
                    <hr>
                </div>

                <!-- Information Section -->
                <div class="info-section printable">
                    <table>
                        <tr>
                            <td>No. Faktur</td>
                            <td>: {{$transaksi_sparepart->id_transaksi_sparepart  }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Bayar</td>
                            <td>: {{$transaksi_sparepart->tanggal_jual }}</td>
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

                <!-- Sparepart Data Section -->
                <div class="sparepart-section printable">
                    <table class="table table-bordered">
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
                                    <td>{{ $detail->jumlah_sparepart_terjual}}</td>
                                    <td>Rp {{ number_format($detail->sparepart->harga_sparepart, 2, ',', '.') }}</td>
                                    <td>Rp
                                        {{ number_format($detail->jumlah_sparepart_terjual * $detail->sparepart->harga_sparepart, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Payment and Change Section -->
                <div class="total-section printable">
                    <table>
                        <tr>
                            <td>Harga Total</td>
                            <td>: Rp.
                                {{ number_format($transaksi_sparepart->harga_total_transaksi_sparepart, 0, ',', '.') }}
                            </td>
                        </tr>
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
                <div class="footer printable">
                    <p>Teknisi</p>
                    <p class="teknisi">{{ Auth::user()->nama_teknisi }}</p>
                </div>

                <!-- Button Home (Hidden in Print) -->
                <div>
                    <a href="{{ route('transaksi_sparepart.index') }}" class="btn btn-success" >Kembali</a>
                </div>
            </div>
        </main>
    </div>

</body>

<script>
    // Trigger print window
    window.print();

    // After print is finished, go to home page
    window.onafterprint = function () {
        window.location.href = "{{ route('transaksi_sparepart.index') }}";
    };
</script>

</html>