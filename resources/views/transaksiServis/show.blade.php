@php
use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Transaksi Servis</title>
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
        max-height: 78vh;
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
            <h3><span style="color: grey;">View Transaksi Servis</span></h3>
            <hr>

            <div id="content-frame" class="container">
                <!-- FORM - read-only mode -->
                <form>
                    @csrf

                    <!-- Service Details Section -->
                    <div class="row mb-3">
                        <!-- Left side: Service Form Fields -->
                        <div class="col-md-7">
                            <div class="row">
                                <!-- ID Servis and Status Bayar -->
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="id_servis" class="form-label" style="width: 200px;">ID Servis</label>
                                    <input type="text" id="id_servis" name="id_servis" class="form-control"
                                        value="{{ $transaksiServis->id_service }}" readonly>
                                </div>
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="status_bayar" class="form-label" style="width: 200px;">Status
                                        Bayar</label>
                                    <input type="text" class="form-control" value="{{ $transaksiServis->status_bayar }}"
                                        readonly>
                                </div>

                                <!-- Tanggal Masuk -->
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="tanggal_masuk" class="form-label" style="width: 200px;">Tanggal
                                        Masuk</label>
                                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control"
                                        value="{{ date('Y-m-d', strtotime($transaksiServis->tanggal_masuk)) }}"
                                        readonly>
                                </div>

                                <!-- Tanggal Keluar -->
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="tanggal_keluar" class="form-label" style="width: 200px;">Tanggal
                                        Keluar</label>
                                    <input type="date" id="tanggal_keluar" name="tanggal_keluar" class="form-control"
                                        value="{{ $transaksiServis->tanggal_keluar ? date('Y-m-d', strtotime($transaksiServis->tanggal_keluar)) : '' }}"
                                        readonly>
                                </div>

                                <!-- Teknisi Section -->
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="nama_teknisi" class="form-label" style="width: 200px;">Nama
                                        Teknisi</label>
                                    <input type="text" id="nama_teknisi" name="nama_teknisi" class="form-control"
                                        value="{{ Auth::user()->nama_teknisi }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Right side: Total Transaksi -->
                        <div class="col-md-5 d-flex align-items-center justify-content-center">
                            <div
                                style="border: 1px solid #ccc; padding: 10px; text-align: center; width: 100%; margin-top: -50px; margin-left:50px;">
                                <label>Total Transaksi:</label>
                                <h3><strong class="total-transaksi-amount">Rp.
                                        {{ number_format($transaksiServis->harga_total_transaksi_servis + $transaksiServis->detailTransaksiServis->sum('subtotal_sparepart'), 0, ',', '.') }}</strong>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <hr style="border: none; height: 0.5px;">

                    <!-- Pelanggan and Laptop Details -->
                    <div class="row mb-3">
                        <!-- Pelanggan Section -->
                        <div class="col-md-5">
                            <h5>Pelanggan</h5>
                            <div class="mb-2 d-flex align-items-center">
                                <label for="nama_pelanggan" class="form-label" style="width: 80px;">Nama</label>
                                <input type="text" name="nama_pelanggan" class="form-control"
                                    value="{{ $transaksiServis->pelanggan->nama_pelanggan }}" readonly>
                            </div>

                            <div class="mb-2 d-flex align-items-center">
                                <label for="nohp_pelanggan" class="form-label" style="width: 80px;">No. HP</label>
                                <input type="text" name="nohp_pelanggan" class="form-control"
                                    value="{{ $transaksiServis->pelanggan->nohp_pelanggan }}" readonly>
                            </div>
                        </div>

                        <!-- Laptop Section -->
                        <div class="col-md-5">
                            <h5>Laptop</h5>
                            <div class="mb-2 d-flex align-items-center">
                                <label for="merek_laptop" class="form-label" style="width: 80px;">Merek</label>
                                <input type="text" name="merek_laptop" class="form-control"
                                    value="{{ $transaksiServis->laptop->merek_laptop }}" readonly>
                            </div>

                            <div class="mb-4 d-flex align-items-center">
                                <label for="keluhan" class="form-label" style="width: 80px;">Keluhan</label>
                                <input type="text" name="keluhan" class="form-control"
                                    value="{{ $transaksiServis->laptop->deskripsi_masalah }}" readonly>
                            </div>
                        </div>

                        <!-- Jasa Servis Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5>Jasa Servis</h5>
                                <div class="d-flex flex-wrap">
                                    @foreach ($jasaServisList as $jasa)
                                    <div class="form-check form-check-inline jasa-servis-item" style="width: 370px;">
                                        <input class="form-check-input jasa-servis-checkbox" type="checkbox"
                                            name="jasa_servis[]" value="{{ $jasa->id_jasa }}"
                                            data-nama="{{ $jasa->jenis_jasa }}" data-price="{{ $jasa->harga_jasa }}"
                                            id="jasaCheckbox_{{ $jasa->id_jasa }}"
                                            {{ in_array($jasa->id_jasa, $selectedJasaIds) ? 'checked' : '' }} disabled>

                                        <label for="jasaCheckbox_{{ $jasa->id_jasa }}" class="form-check-label"
                                            style="width: 350px;">
                                            {{ $jasa->jenis_jasa }} - Base Price: Rp.
                                            {{ number_format($jasa->harga_jasa, 0, ',', '.') }}
                                        </label>

                                        <!-- Input for custom price -->
                                        <input type="number" class="form-control mt-2 jasa-servis-price-input"
                                            name="custom_price[{{ $jasa->id_jasa }}]" placeholder="Enter price"
                                            id="customPrice_{{ $jasa->id_jasa }}"
                                            data-default-price="{{ $jasa->harga_jasa }}"
                                            value="{{ isset($selectedCustomPrices[$jasa->id_jasa]) ? $selectedCustomPrices[$jasa->id_jasa] : '' }}"
                                            readonly>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Garansi Section (dynamically generated) -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Garansi</h5>
                                <div id="garansi-container">
                                    @foreach ($transaksiServis->detailTransaksiServis as $index => $detail)
                                    <div class="row mb-2 align-items-center garansi-item">
                                        <div class="col-md-4">
                                            <strong>{{ $index + 1 }}. {{ $detail->jasaServis->jenis_jasa }}</strong>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tgl_mulai_{{ $detail->jasaServis->jenis_jasa }}"
                                                class="form-label">Tgl. Mulai Garansi</label>
                                            <input type="date" class="form-control" value="{{ $detail->akhir_garansi }}"
                                                readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="jangka_garansi_{{ $detail->jasaServis->jenis_jasa }}"
                                                class="form-label">Jangka Waktu (Bulan)</label>
                                            <input type="number" class="form-control"
                                                value="{{ $detail->jangka_garansi_bulan }}" readonly>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Sparepart Section -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Sparepart</h5>
                                <div id="sparepart-container">
                                    @php
                                    $sparepartCount = 0;
                                    @endphp
                                    @if (!empty($selectedSpareparts))
                                    @foreach ($selectedSpareparts as $jasaId => $sparepart)
                                    @php $sparepartCount++; @endphp
                                    <div class="row mb-2 align-items-center sparepart-item">
                                        <div class="col-md-2">
                                            <label for="sparepart_tipe_{{ $sparepartCount }}"
                                                class="form-label">Tipe</label>
                                            <input type="text" id="sparepart_tipe_{{ $sparepartCount }}"
                                                name="sparepart_tipe_{{ $jasaId }}" class="form-control"
                                                value="{{ $sparepart['jenis_sparepart'] ?? '-' }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="sparepart_merek_{{ $sparepartCount }}"
                                                class="form-label">Merek</label>
                                            <input type="text" id="sparepart_merek_{{ $sparepartCount }}"
                                                name="sparepart_merek_{{ $jasaId }}" class="form-control"
                                                value="{{ $sparepart['merek_sparepart'] ?? '-' }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="sparepart_model_{{ $sparepartCount }}"
                                                class="form-label">Model</label>
                                            <input type="text" id="sparepart_model_{{ $sparepartCount }}"
                                                name="sparepart_model_{{ $jasaId }}" class="form-control"
                                                value="{{ $sparepart['model_sparepart'] ?? '-' }}" readonly>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="sparepart_jumlah_{{ $sparepartCount }}"
                                                class="form-label">Jumlah</label>
                                            <input type="number" id="sparepart_jumlah_{{ $sparepartCount }}"
                                                name="sparepart_jumlah_{{ $jasaId }}" class="form-control"
                                                value="{{ $sparepart['jumlah_sparepart'] ?? 1 }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="sparepart_harga_{{ $sparepartCount }}" class="form-label">Harga
                                                Sparepart</label>
                                            <input type="number" id="sparepart_harga_{{ $sparepartCount }}"
                                                name="sparepart_harga_{{ $jasaId }}" class="form-control"
                                                value="{{ $sparepart['harga_sparepart'] ?? 0 }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="sparepart_subtotal_{{ $sparepartCount }}" class="form-label">Sub
                                                Total Sparepart</label>
                                            <input type="number" id="sparepart_subtotal_{{ $sparepartCount }}"
                                                name="sparepart_subtotal_{{ $jasaId }}"
                                                class="form-control subtotal-sparepart"
                                                value="{{ $sparepart['subtotal_sparepart'] ?? 0 }}" readonly>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>


                        <div class="modal-footer">
                            <!-- Bayar Button -->
                            @if ($transaksiServis->status_bayar === 'Pending')
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#paymentModal">Bayar</button>
                            @else
                            <button type="button" class="btn btn-success" disabled>Sudah Dibayar</button>
                            @endif
                            <!-- Back Button -->
                            <a href="{{ route('transaksiServis.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>

                </form>
            </div>
        </main>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm">
                        <div class="mb-3">
                            <label for="harusDibayar" class="form-label">Harus Dibayar (Rp)</label>
                            <input type="text" class="form-control" id="harusDibayar"
                                value="{{ $transaksiServis->harga_total_transaksi_servis + $transaksiServis->detailTransaksiServis->sum('subtotal_sparepart') }}"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label for="pembayaran" class="form-label">Pembayaran (Rp)</label>
                            <input type="number" class="form-control" id="pembayaran" required>
                        </div>
                        <div class="mb-3">
                            <label for="kembalian" class="form-label">Kembalian (Rp)</label>
                            <input type="text" class="form-control" id="kembalian" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="bayarButton" disabled>Bayar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Payment Calculation -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const pembayaranInput = document.getElementById('pembayaran');
        const harusDibayarInput = document.getElementById('harusDibayar');
        const kembalianInput = document.getElementById('kembalian');
        const bayarButton = document.getElementById('bayarButton');
        const modalElement = document.getElementById('paymentModal');
        const modal = new bootstrap.Modal(modalElement);

        pembayaranInput.addEventListener('input', function() {
            const pembayaran = parseFloat(pembayaranInput.value) || 0;
            const harusDibayar = parseFloat(harusDibayarInput.value) || 0;

            if (pembayaran >= harusDibayar) {
                kembalianInput.value = (pembayaran - harusDibayar).toLocaleString('id-ID');
                bayarButton.disabled = false;
            } else {
                kembalianInput.value = '0';
                bayarButton.disabled = true;
            }
        });

        bayarButton.addEventListener('click', function handlePayment() {
        const pembayaran = parseFloat(pembayaranInput.value) || 0;
        const harusDibayar = parseFloat(harusDibayarInput.value) || 0;
        const kembalian = pembayaran - harusDibayar;

        if (pembayaran >= harusDibayar) {
            axios.post('/transaksiServis/bayar', {
                id_service: '{{ $transaksiServis->id_service }}',
                pembayaran: pembayaran
            }).then(response => {
                if (response.data.success) {
                    bayarButton.innerHTML = 'Cetak dan Kirim Nota';
                    bayarButton.classList.remove('btn-primary');
                    bayarButton.classList.add('btn-warning');

                    bayarButton.removeEventListener('click', handlePayment);
                    bayarButton.addEventListener('click', () => {
                        // Open PDF and send WhatsApp message
                        window.open(
                            '{{ route('transaksiServis.cetakNota', $transaksiServis->id_service) }}?pembayaran=' + encodeURIComponent(pembayaran) + '&kembalian=' + encodeURIComponent(kembalian),
                            '_blank'
                        );

                        axios.post('/transaksiServis/sendInvoiceToWhatsapp', {
                            id_service: '{{ $transaksiServis->id_service }}',
                            pembayaran: pembayaran,
                            kembalian: kembalian
                        }).then(fonnteResponse => {
                            alert(fonnteResponse.data.message);
                        }).catch(error => {
                            console.error('Error sending WhatsApp message:', error);
                            alert('Terjadi kesalahan saat mengirim nota ke WhatsApp.');
                        });
                    });
                } else {
                    alert('Pembayaran gagal, coba lagi.');
                }
            }).catch(error => {
                console.error("Error during payment request: ", error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }
    });

        modalElement.addEventListener('hidden.bs.modal', function() {
            location.reload();
        });
    });
    </script>
</body>

</html>
