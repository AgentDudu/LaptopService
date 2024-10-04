@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jasa Servis</title>
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

        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        #disclaimer {
            color: red;
            font-size: 13px;
            margin-left: 10px;
        }

        .tambahButton {
            display: flex;
        }

        td a {
            cursor: pointer;
        }

        table td {
            padding-right: 30px;
            padding-bottom: 20px;
        }

        #namaJasa {
            font-weight: bold;
            margin-bottom: 2px;
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
            <h3><span style="color: grey;">Data Jasa Servis</span></h3>
            <hr>

            <!--💦 READ 💦-->
            <!-- Tambah button -->
            <div class="tambahButton">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#tambahJasaModal">Tambah</button>
                <p id="disclaimer">*Hanya Pemilik LaptopCafe yang dapat Edit-Tambah-Hapus</p>
            </div>

            <!-- Tabel Daftar Harga -->
            <div>
                <table class="service-table">
                    <tr>
                        @foreach ($jasaServis as $jasa)
                            <td>
                                <label id="namaJasa">{{ $jasa->jenis_jasa }}</label><br>
                                <label>Base Price:</label>
                                <input type="text" value="{{ $jasa->harga_jasa }}" disabled>
                                <a data-bs-toggle="modal" data-bs-target="#editJasaModal{{ $jasa->id_jasa }}">
                                    @include('components.icons.svg-edit')
                                </a>
                                <a data-bs-toggle="modal" data-bs-target="#hapusJasaModal{{ $jasa->id_jasa }}">
                                    @include('components.icons.svg-delete')
                                </a>
                            </td>
                            @if ($loop->iteration % 3 == 0)
                    </tr>
                    <tr>
                        @endif
                        @endforeach
                    </tr>
                </table>
            </div>

        </main>
    </div>

    <!-- Modal -->
    <!-- Hanya bisa diakses jika user adalah Pemilik -->
    @if (Auth::user()->status === 'Pemilik')

        <!--💦 Create Form 💦-->
        <div class="modal fade" id="tambahJasaModal" tabindex="-1" aria-labelledby="tambahJasaModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahJasaModalLabel">Tambah Data Jasa Servis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('jasaServis.store') }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label for="jenis_jasa" class="form-label">Nama Jasa Servis</label>
                                <input type="text" class="form-control" id="jenis_jasa" name="jenis_jasa">
                            </div>
                            <div class="mb-2">
                                <label for="harga_jasa" class="form-label">Base Price</label>
                                <input type="text" class="form-control" id="harga_jasa" name="harga_jasa">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--💦 Edit Form 💦-->
        @foreach ($jasaServis as $jasa)
            <div class="modal fade" id="editJasaModal{{ $jasa->id_jasa }}" tabindex="-1"
                aria-labelledby="editJasaModalLabel{{ $jasa->id_jasa }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editJasaModalLabel">Edit Data Jasa Servis</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('jasaServis.update', $jasa->id_jasa) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-2">
                                    <label for="jenis_jasa" class="form-label">Nama Jasa Servis</label>
                                    <input type="text" class="form-control" id="jenis_jasa" name="jenis_jasa"
                                        value="{{ $jasa->jenis_jasa }}">
                                </div>
                                <div class="mb-2">
                                    <label for="harga_jasa" class="form-label">Base Price</label>
                                    <input type="text" class="form-control" id="harga_jasa" name="harga_jasa"
                                        value="{{ $jasa->harga_jasa }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


        <!--💦 Delete Form 💦-->
        @foreach ($jasaServis as $jasa)
            <div class="modal fade" id="hapusJasaModal{{ $jasa->id_jasa }}" tabindex="-1"
                aria-labelledby="hapusJasaModalLabel{{ $jasa->id_jasa }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="hapusJasaModalLabel">Hapus Data Jasa Servis</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus data jasa servis
                                <strong>{{ $jasa->jenis_jasa }}</strong>?
                            </p>
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('jasaServis.destroy', $jasa->id_jasa) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>

</html>
