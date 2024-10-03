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
            width: 100%;
            background-color: #F8F9FA;
            height: fit-content;
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
            <h3><span style="color: grey;">Data Teknisi</span></h3>
            <hr>

            <!--💦 READ 💦-->
            <!-- Tambah button -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                data-bs-target="#tambahTeknisiModal">Tambah</button>

            <!-- Tabel -->
            <div id="content-frame" class="container">
                <table class="table table-bordered">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>ID Teknisi</th>
                            <th>Status Teknisi</th>
                            <th>Nama</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teknisi as $index => $tek)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $tek->id_teknisi }}</td>
                                <td>{{ $tek->status }}</td>
                                <td>{{ $tek->nama_teknisi }}</td>
                                <td>{{ $tek->nohp_teknisi }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editTeknisiModal{{ $tek->id_teknisi }}">Edit</button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#hapusTeknisiModal{{ $tek->id_teknisi }}">Hapus</button>
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
    <div class="modal fade" id="tambahTeknisiModal" tabindex="-1" aria-labelledby="tambahTeknisiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahTeknisiModalLabel">Tambah Data Teknisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for adding technician -->
                    <form action="{{ route('teknisi.store') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option selected value="Pegawai">Pegawai</option>
                                <option value="Pemilik">Pemilik</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="nama_teknisi" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama_teknisi" name="nama_teknisi">
                        </div>
                        <div class="mb-2">
                            <label for="nohp_teknisi" class="form-label">No. HP</label>
                            <input type="text" class="form-control" id="nohp_teknisi" name="nohp_teknisi">
                        </div>
                        <div class="mb-2">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--💦 Edit Form 💦-->
    @foreach ($teknisi as $tek)
        <div class="modal fade" id="editTeknisiModal{{ $tek->id_teknisi }}" tabindex="-1"
            aria-labelledby="editTeknisiModalLabel{{ $tek->id_teknisi }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTeknisiModalLabel">Edit Data Teknisi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('teknisi.update', $tek->id_teknisi) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="Pegawai" {{ $tek->status == 'Pegawai' ? 'selected' : '' }}>Pegawai
                                    </option>
                                    <option value="Pemilik" {{ $tek->status == 'Pemilik' ? 'selected' : '' }}>Pemilik
                                    </option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="nama_teknisi" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama_teknisi" name="nama_teknisi"
                                    value="{{ $tek->nama_teknisi }}">
                            </div>
                            <div class="mb-2">
                                <label for="nohp_teknisi" class="form-label">No. HP</label>
                                <input type="text" class="form-control" id="nohp_teknisi" name="nohp_teknisi"
                                    value="{{ $tek->nohp_teknisi }}">
                            </div>
                            <div class="mb-2">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    value="{{ $tek->password }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!--💦 Delete Form 💦-->
    @foreach ($teknisi as $tek)
        <div class="modal fade" id="hapusTeknisiModal{{ $tek->id_teknisi }}" tabindex="-1"
            aria-labelledby="hapusTeknisiModalLabel{{ $tek->id_teknisi }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="hapusTeknisiModalLabel">Hapus Data Teknisi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data teknisi
                            <strong>{{ $tek->nama_teknisi }}</strong>
                            dengan No. HP <strong>{{ $tek->nohp_teknisi }}</strong>?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('teknisi.destroy', $tek->id_teknisi) }}" method="POST">
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
