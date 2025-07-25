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
                            <th>Nama User</th>
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
                                    @if ($servis->detailTransaksiServis->isNotEmpty())
                                        @foreach ($servis->detailTransaksiServis as $detail)
                                            {{ $detail->jasaServis->jenis_jasa }}
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if ($servis->status_bayar == 'Sudah dibayar')
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
                                    @if ($servis->status_bayar != 'Sudah dibayar')
                                        <a href="{{ route('transaksiServis.edit', $servis->id_service) }}"
                                        class="btn btn-warning">Edit</a>
                                    @else
                                        <a href="{{ route('transaksiServis.edit', $servis->id_service) }}"
                                        class="btn btn-warning disabled">Edit</a>
                                    @endif

                                    <!-- Delete Button triggers the modal -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        onclick="setDeleteAction('{{ route('transaksiServis.destroy', $servis->id_service) }}')">
                                        Delete
                                    </button>

                                    <!-- Cetak Nota Sementara -->
                                    @if ($servis->status_bayar == 'Pending')
                                        <button class="btn btn-info temp-invoice-btn"
                                            data-id="{{ $servis->id_service }}">
                                            Nota Sementara
                                        </button>
                                    @elseif ($servis->status_bayar == 'Sudah dibayar')
                                        <a href="{{ route('transaksiServis.cetakNota', $servis->id_service) }}"
                                            class="btn btn-dark" target="_blank">
                                            Nota Transaksi
                                        </a>
                                    @endif
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
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus transaksi servis ini? Tindakan ini tidak dapat ditarik ulang.
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

    <!-- Modal for Sending Temp Invoice to Whatsapp Feedback -->
    <div class="modal fade" id="tempInvoiceModal" tabindex="-1" aria-labelledby="tempInvoiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tempInvoiceModalLabel">Status Pengiriman Nota Sementara</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="tempInvoiceMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setDeleteAction(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tempInvoiceButtons = document.querySelectorAll('.temp-invoice-btn');

            tempInvoiceButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const serviceId = button.getAttribute('data-id');

                    window.open(`/transaksiServis/tempInvoice/${serviceId}`, '_blank');

                    axios.post('/transaksiServis/sendTempInvoiceToWhatsapp', {
                            id_service: serviceId
                        })
                        .then(response => {
                            const modalMessage = document.getElementById('tempInvoiceMessage');
                            const modal = new bootstrap.Modal(document.getElementById(
                                'tempInvoiceModal'));

                            modalMessage.textContent = response.data.message;
                            modal.show();
                        })
                        .catch(error => {
                            console.error('Error sending WhatsApp message:', error);

                            const modalMessage = document.getElementById('tempInvoiceMessage');
                            const modal = new bootstrap.Modal(document.getElementById(
                                'tempInvoiceModal'));

                            modalMessage.textContent =
                                'Terjadi kesalahan saat mengirim nota sementara ke WhatsApp.';
                            modal.show();
                        });
                });
            });
        });
    </script>

</body>

</html>
