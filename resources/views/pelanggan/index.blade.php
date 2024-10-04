@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"rel="stylesheet">
    <style>
        .main-container {
            display: flex;
            background-color: #067D40;
        }

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

        table {
            margin-left: -12px;
        }

        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
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

        <!-- Main Content Frame -->
        <main class="content">
            <h3><span style="color: grey;">Data Pelanggan</span></h3>
            <hr>

            <!--💦 READ 💦-->
            <!-- Tambah button -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                data-bs-target="#tambahPelangganModal">Tambah</button>

            <!-- Tabel -->
            <div id="content-frame" class="container">
                <table class="table table-bordered">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>ID Pelanggan</th>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pelanggan as $index => $customer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $customer->id_pelanggan }}</td>
                                <td>{{ $customer->nama_pelanggan }}</td>
                                <td>{{ $customer->nohp_pelanggan }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editPelangganModal{{ $customer->id_pelanggan }}">Edit</button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#hapusPelangganModal{{ $customer->id_pelanggan }}">Hapus</button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal -->
    <!--💦 Create Form 💦-->
    <div class="modal fade" id="tambahPelangganModal" tabindex="-1" aria-labelledby="tambahPelangganModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPelangganModalLabel">Tambah Data Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for adding customer -->
                    <form action="{{ route('pelanggan.store') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama_pelanggan">
                        </div>
                        <div class="mb-2">
                            <label for="nohp_pelanggan" class="form-label">No. HP</label>
                            <input type="text" class="form-control" id="nohp_pelanggan" name="nohp_pelanggan">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--💦 Edit Form 💦-->
    @foreach ($pelanggan as $customer)
        <div class="modal fade" id="editPelangganModal{{ $customer->id_pelanggan }}" tabindex="-1"
            aria-labelledby="editPelangganModalLabel{{ $customer->id_pelanggan }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPelangganModalLabel">Edit Data Pelanggan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('pelanggan.update', $customer->id_pelanggan) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <label for="nama_pelanggan" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan"
                                    value="{{ $customer->nama_pelanggan }}">
                            </div>
                            <div class="mb-2">
                                <label for="nohp_pelanggan" class="form-label">No. HP</label>
                                <input type="text" class="form-control" id="nohp_pelanggan" name="nohp_pelanggan"
                                    value="{{ $customer->nohp_pelanggan }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!--💦 Delete Form 💦-->
    @foreach ($pelanggan as $customer)
        <div class="modal fade" id="hapusPelangganModal{{ $customer->id_pelanggan }}" tabindex="-1"
            aria-labelledby="hapusPelangganModalLabel{{ $customer->id_pelanggan }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="hapusPelangganModalLabel">Hapus Data Pelanggan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data pelanggan
                            <strong>{{ $customer->nama_pelanggan }}</strong>
                            dengan No. HP <strong>{{ $customer->nohp_pelanggan }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('pelanggan.destroy', $customer->id_pelanggan) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>

</html>
