<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi Sparepart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <style>
        body {
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

        .form-control,
        select {
            margin-bottom: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .pelanggan-label,
        .sparepart-label {
            font-size: 1.5rem;
            font-weight: bold;
            color: #6c757d;
        }

        .long-line {
            width: 100%;
            border-top: 2px solid grey;
            margin: 10px 0;
        }

        table.table-bordered th {
            background-color: #D7FFC2;
            color: black;
        }

        .table tbody tr td {
            vertical-align: middle;
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
            <h3>Tambah Transaksi Sparepart</h3>
            <hr>
            < class="container">
                <form action="{{ route('transaksi_sparepart.store') }}" method="POST">
                    @csrf
                    <table class="no-border">
                        <tr>
                            <td>No. Faktur</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="id_transaksi_sparepart"
                                    value="{{$transaksi_sparepart->$newId }}" readonly class="form-control"
                                    placeholder="TSP001">
                            </td>
                            <td width="600px"></td>
                            <td>Total Transaksi</td>
                            <td>
                                <input type="text" name="harga_total_transaksi_sparepart"
                                    value="Rp. {{number_format($transaksi_sparepart->harga_total_transaksi_sparepart, 2, ',', '.')}}"
                                    class="form-control total-harga_sparepart-input" required readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>Teknisi</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{$transaksi_sparepart->teknisi->nama_teknisi}}"
                                    class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td><input type="date" value="{{$transaksi_sparepart->tanggal_jual}}" name="tanggal_jual"
                                    class="form-control" readonly></td>
                        </tr>
                    </table>

                    <div class="long-line"></div>

                    <h5 class="pelanggan-label">Pelanggan</h5>
                    <table class="no-border">
                        <tr>
                            <td>Pelanggan</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{$transaksi_sparepart->pelanggan->nama_pelanggan}}" readonly
                                    class="form-control" readonly>
                            </td>
                        </tr>

                        <tr>
                            <td>No HP</td>
                            <td>:</td>
                            <td><input type="text" value="{{$transaksi_sparepart->pelanggan->nohp_pelanggan}}"
                                    name="nohp_pelanggan" class="form-control" readonly></td>
                        </tr>
                    </table>

                    <div class="long-line"></div>

                    <h5 class="sparepart-label">Sparepart</h5>
                    <table class="table table-bordered" id="sparepartsTable">
                        <thead>
                            <tr>
                                <th>Jenis Sparepart</th>
                                <th>Merek</th>
                                <th>Model</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi_sparepart->detail_transaksi_sparepart as $detail)
                                <tr>
                                    <td>{{ $detail->sparepart->jenis_sparepart }}</td>
                                    <td>{{ $detail->sparepart->merek_sparepart }}</td>
                                    <td>{{ $detail->sparepart->model_sparepart }}</td>
                                    <td>{{ $detail->jumlah_sparepart_terjual }}</td>
                                    <td>Rp {{ number_format($detail->sparepart->harga_sparepart, 2, ',', '.') }}</td>
                                    <td>Rp
                                        {{ number_format($detail->jumlah_sparepart_terjual * $detail->sparepart->harga_sparepart, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <a href="{{ route('transaksi_sparepart.index') }}" class="btn btn-secondary">Kembali</a>

                </form>
                </table>
        </main>
    </div>
</body>

</html>