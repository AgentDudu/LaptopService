<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Sparepart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        table.table-bordered th {
            background-color: #D7FFC2;
            color: black;
        }
    </style>
</head>

<body>
    <div class="main-container" style="display: flex; background-color: #067D40;">
        <!-- Sidebar -->
        <div class="sidebar text-white">
            @if (Auth::user()->status === 'Pegawai')
                <x-sidebar-nonadmin />
            @else
                <x-sidebar-admin />
            @endif
        </div>

        <!-- Main Content -->
        <main class="content"
            style="flex: 1; padding: 20px; margin: 30px 30px 30px 0; background-color: #F8F9FA; border-radius: 20px;">
            <h3><span style="color: black;">Transaksi Sparepart</span></h3>
            <hr>



            <a href="{{ route('transaksi_sparepart.create') }}" class="btn btn-primary mb-3">Tambah Transaksi Sparepart</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No. Faktur</th>
                        <th>Teknisi</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Harga Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi_sparepart as $transaksi_sp)
                            <tr data-transaction-id="{{ $transaksi_sp->id_transaksi_sparepart }}">
                                <td>{{ $transaksi_sp->id_transaksi_sparepart }}</td>
                                <td>{{ $transaksi_sp->teknisi->nama_teknisi }}</td>
                                <td>{{ $transaksi_sp->pelanggan->nama_pelanggan }}</td>
                                <td>{{ $transaksi_sp->tanggal_jual }}</td>
                                <td>{{ $transaksi_sp->detail_transaksi_sparepart->sum('jumlah_sparepart_terjual') }}</td>
                                <td>Rp {{ number_format($transaksi_sp->harga_total_transaksi_sparepart, 2, ',', '.') }}</td>
                                <td class="actions-column">
                                    <form action="{{ route('transaksi_sparepart.destroy', $transaksi_sp->id_transaksi_sparepart) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    </div>
</body>

</html>
