@php
use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
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
            <h3><span style="color: grey;">Transaksi Servis</span></h3>
            <hr>

            <!--💦 READ TABLE💦-->
            <!-- Tambah button -->
            <button type="button" class="btn btn-primary mb-3"
                onclick="window.location.href='{{ route('transaksiServis.create') }}'">Tambah Transaksi Servis</button>

            <!-- Tabel -->
            <div id="content-frame" class="container">
                <table class="table table-bordered">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>ID Servis</th>
                            <th>Teknisi</th>
                            <th>Nama Pelanggan</th>
                            <th>Laptop</th>
                            <th>Tanggal Masuk</th>
                            <th>Jasa Servis</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jasaServis as $index => $servis)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $servis->id_service }}</td>
                            <td>{{ $servis->teknisi ? $servis->teknisi->nama_teknisi : 'N/A' }}</td>
                            <td>{{ $servis->pelanggan ? $servis->pelanggan->nama_pelanggan : 'N/A' }}</td>
                            <td>{{ $servis->laptop ? $servis->laptop->merek_laptop : 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($servis->tanggal_masuk)->format('d-m-Y') }}</td>
                            <td>
                                @if($servis->detailTransaksiServis->isNotEmpty())
                                @foreach($servis->detailTransaksiServis as $detail)
                                {{ $detail->jasaServis->jenis_jasa }}
                                @endforeach
                                @else
                                N/A
                                @endif
                            </td>
                            <td>
                                @if($servis->status_bayar == 'Sudah dibayar')
                                <span class="badge bg-success">Sudah dibayar</span>
                                @else
                                <span class="badge bg-danger">Belum dibayar</span>
                                @endif
                            </td>
                            <td>
                                <!-- Lihat Button -->
                                <a href="{{ route('transaksiServis.show', $servis->id_service) }}"
                                    class="btn btn-success">Lihat</a>

                                <!-- Edit Button -->
                                <a href="{{ route('transaksiServis.edit', $servis->id_service) }}"
                                    class="btn btn-warning">Edit</a>

                                <!-- Delete Button triggers the modal -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    onclick="setDeleteAction('{{ route('transaksiServis.destroy', $servis->id_service) }}')">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this transaction service? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function setDeleteAction(actionUrl) {
        document.getElementById('deleteForm').action = actionUrl;
    }
    </script>

</body>

</html>
