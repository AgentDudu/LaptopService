@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop</title>
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
            <h3><span style="color: grey;">Data Laptop</span></h3>
            <hr>

            <!--💦 READ 💦-->
            <!-- Tambah button -->
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                data-bs-target="#tambahLaptopModal">Tambah</button>

            <!-- Tabel -->
            <div id="content-frame" class="container">
                <table class="table table-bordered">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>ID Laptop</th>
                            <th>Merek Laptop</th>
                            <th>Nama Pemilik</th>
                            <th>Deskripsi Masalah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laptop as $index => $lap)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $lap->id_laptop }}</td>
                                <td>{{ $lap->merek_laptop }}</td>
                                <td>{{ $lap->pelanggan->nama_pelanggan }}</td>
                                <td>{{ $lap->deskripsi_masalah }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editLaptopModal{{ $lap->id_laptop }}">Edit</button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#hapusLaptopModal{{ $lap->id_laptop }}">Hapus</button>
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
    <div class="modal fade" id="tambahLaptopModal" tabindex="-1" aria-labelledby="tambahLaptopModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahLaptopModalLabel">Tambah Data Laptop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for adding laptop -->
                    <form action="{{ route('laptop.store') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label for="nama_pemilik" class="form-label">Nama Pemilik</label>
                            <select class="form-select" id="nama_pemilik" name="id_pelanggan">
                                @foreach ($pelanggan as $owner)
                                    <option selected value="{{ $owner->id_pelanggan }}">{{ $owner->nama_pelanggan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <label for="merekLaptop" class="form-label">Merek Laptop</label>
                            <input type="text" class="form-control" id="merekLaptop" name="merek_laptop">
                        </div>
                        <div class="mb-2">
                            <label for="deskripsi_masalah" class="form-label">Deskripsi Masalah</label>
                            <input type="text" class="form-control" id="deskripsi_masalah" name="deskripsi_masalah">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--💦 Edit Form 💦-->
    @foreach ($laptop as $lap)
        <div class="modal fade" id="editLaptopModal{{ $lap->id_laptop }}" tabindex="-1"
            aria-labelledby="editLaptopModalLabel{{ $lap->id_laptop }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLaptopModalLabel">Edit Data Laptop</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('laptop.update', $lap->id_laptop) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <label for="nama_pelanggan" class="form-label">Nama Pemilik</label>
                                <select class="form-select" id="nama_pelanggan" name="nama_pelanggan">
                                    @foreach ($pelanggan as $owner)
                                        <option selected value="{{ $owner->id_pelanggan }}"
                                            {{ $lap->pelanggan->id_pelanggan == $owner->id_pelanggan ? 'selected' : '' }}>
                                            {{ $owner->nama_pelanggan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="merek_laptop" class="form-label">Merek Laptop</label>
                                <input type="text" class="form-control" id="merek_laptop" name="merek_laptop"
                                    value="{{ $lap->merek_laptop }}">
                            </div>
                            <div class="mb-2">
                                <label for="deskripsi_masalah" class="form-label">Deskripsi Masalah</label>
                                <input type="text" class="form-control" id="deskripsi_masalah"
                                    name="deskripsi_masalah" value="{{ $lap->deskripsi_masalah }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!--💦 Delete Form 💦-->
    @foreach ($laptop as $lap)
        <div class="modal fade" id="hapusLaptopModal{{ $lap->id_laptop }}" tabindex="-1"
            aria-labelledby="hapusLaptopModalLabel{{ $lap->id_laptop }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="hapusLaptopModalLabel">Hapus Data Laptop</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data laptop <strong>{{ $lap->merek_laptop }}</strong>
                            milik <strong>{{ $lap->pelanggan->nama_pelanggan }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('laptop.destroy', $lap->id_laptop) }}" method="POST">
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
