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
        /* Main container with sidebar and content */
        .main-container {
            display: flex;
            background-color: #067D40;
        }

        /* Fixed sidebar to keep it static during scroll */
        .sidebar {
            width: 245px;
            background-color: #067D40;
        }

        .content {
            flex: 1;
            padding: 20px;
            margin: 20px 20px 20px 0;
            background-color: #F8F9FA;
            border-radius: 20px;
            max-height: 85%;
        }

        #content-frame {
            max-width: 100%;
            background-color: #F8F9FA;
            max-height: 500px;
            overflow-y: scroll;
        }

        /* Container for the table with scrollable functionality */
        .table-container {
            max-height: 650px;
            overflow-y: auto;
            border: 1px solid #ccc;
        }

        /* Table styling */
        table.table-bordered th {
            background-color: #D7E8D8;
            color: black;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            @if (Auth::user()->status === 'Pegawai')
                <x-sidebar-nonadmin />
            @else
                <x-sidebar-admin />
            @endif
        </div>

        <!-- Main Content -->
        <main class="content">
            <h3><span style="color: grey;">Transaksi Sparepart</span></h3>
            <hr>

            <a href="{{ route('transaksi_sparepart.create') }}" class="btn btn-primary mb-3">Tambah Transaksi
                Sparepart</a>

            <!-- Table container for scrollable content -->
            <div class="table-container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No. Faktur</th>
                            <th>Teknisi</th>
                            <th>Nama User</th>
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
                                    <!-- Lihat Button -->
                                    <a href="{{ route('transaksi_sparepart.show', $transaksi_sp->id_transaksi_sparepart) }}"
                                        class="btn btn-success">Lihat</a>

                                    <!-- Delete Button as a form -->
                                    <form
                                        action="{{ route('transaksi_sparepart.destroy', $transaksi_sp->id_transaksi_sparepart) }}"
                                        method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">Delete</button>

                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>