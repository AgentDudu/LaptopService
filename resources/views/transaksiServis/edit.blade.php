@php
use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi Servis</title>
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
        max-height: 80vh;
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
            <h3><span style="color: grey;">Edit Transaksi Servis</span></h3>
            <hr>

            <div id="content-frame" class="container">
                <!--💦 FORM 💦-->
                <form action="{{ route('transaksiServis.update', $transaksiServis->id_service) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Use PUT method for updates -->

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
                                    <select name="status_bayar" id="status_bayar" class="form-control">
                                        <option value="Pending"
                                            {{ $transaksiServis->status_bayar === 'Pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="Sudah dibayar"
                                            {{ $transaksiServis->status_bayar === 'Sudah dibayar' ? 'selected' : '' }}>
                                            Sudah dibayar</option>
                                    </select>
                                </div>

                                <!-- Tanggal Masuk -->
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="tanggal_masuk" class="form-label" style="width: 200px;">Tanggal
                                        Masuk</label>
                                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control"
                                        value="{{ date('Y-m-d', strtotime($transaksiServis->tanggal_masuk)) }}"
                                        required>
                                </div>

                                <!-- Tanggal Keluar -->
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="tanggal_keluar" class="form-label" style="width: 200px;">Tanggal
                                        Keluar</label>
                                    <input type="date" id="tanggal_keluar" name="tanggal_keluar" class="form-control"
                                        value="{{ $transaksiServis->tanggal_keluar ? date('Y-m-d', strtotime($transaksiServis->tanggal_keluar)) : '' }}">
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
                                        {{ number_format($transaksiServis->harga_total_transaksi_servis, 0, ',', '.') }}</strong>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <hr style="border: none; height: 0.5px;">

                    <!-- Pelanggan and Laptop Details -->
                    <div class="row mb-3">
                        <!-- Pelanggan Section -->
                        <div class="col-md-5">
                            <h5>User</h5>
                            <div class="mb-2 d-flex align-items-center">
                                <label for="nama_pelanggan" class="form-label" style="width: 80px;">Nama</label>
                                <input list="pelanggan-options" name="nama_pelanggan" id="pelanggan-input"
                                    class="form-control" value="{{ $transaksiServis->pelanggan->nama_pelanggan }}"
                                    placeholder="Pilih atau ketik nama pelanggan">
                                <datalist id="pelanggan-options">
                                    @foreach ($pelanggan as $pel)
                                    <option value="{{ $pel->nama_pelanggan }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <div class="mb-2 d-flex align-items-center">
                                <label for="nohp_pelanggan" class="form-label" style="width: 80px;">No. HP</label>
                                <input list="nohp-options" name="nohp_pelanggan" id="nohp_pelanggan-input"
                                    class="form-control" value="{{ $transaksiServis->pelanggan->nohp_pelanggan }}"
                                    placeholder="Pilih atau ketik no. HP">
                                <datalist id="nohp-options">
                                    @foreach ($pelanggan as $pel)
                                    <option value="{{ $pel->nohp_pelanggan }}">
                                        @endforeach
                                </datalist>
                            </div>
                        </div>

                        <!-- Laptop Section -->
                        <div class="col-md-5">
                            <h5>Laptop</h5>
                            <div class="mb-2 d-flex align-items-center">
                                <label for="merek_laptop" class="form-label" style="width: 80px;">Merek</label>
                                <input list="laptop-options" name="merek_laptop" id="merek_laptop-input"
                                    class="form-control" value="{{ $transaksiServis->laptop->merek_laptop }}"
                                    placeholder="Pilih atau ketik merek laptop">
                                <datalist id="laptop-options">
                                    @foreach ($laptops as $laptop)
                                    <option value="{{ $laptop->merek_laptop }}">
                                        @endforeach
                                </datalist>
                            </div>

                            <div class="mb-4 d-flex align-items-center">
                                <label for="keluhan" class="form-label" style="width: 80px;">Keluhan</label>
                                <input list="keluhan-options" name="keluhan" id="keluhan-input" class="form-control"
                                    value="{{ $transaksiServis->laptop->deskripsi_masalah }}"
                                    placeholder="Pilih atau ketik keluhan">
                                <datalist id="keluhan-options">
                                    @foreach ($laptops as $laptop)
                                    <option value="{{ $laptop->deskripsi_masalah }}">
                                        @endforeach
                                </datalist>
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
                                            {{ in_array($jasa->id_jasa, $selectedJasaIds) ? 'checked' : '' }}>

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
                                            value="{{ isset($selectedCustomPrices[$jasa->id_jasa]) ? $selectedCustomPrices[$jasa->id_jasa] : '' }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Garansi Section (dynamically generated) -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Garansi</h5>
                                <div id="garansi-container"></div>
                                <!-- This container will hold dynamically generated garansi fields -->
                            </div>
                        </div>

                        <!-- Sparepart Section -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Sparepart <small style="font-size: 13px; color:red">*beri "-" jika kosong</small>
                                </h5>
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
                                                value="{{ $sparepart['jenis_sparepart'] ?? '-' }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="sparepart_merek_{{ $sparepartCount }}"
                                                class="form-label">Merek</label>
                                            <input type="text" id="sparepart_merek_{{ $sparepartCount }}"
                                                name="sparepart_merek_{{ $jasaId }}" class="form-control"
                                                value="{{ $sparepart['merek_sparepart'] ?? '-' }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="sparepart_model_{{ $sparepartCount }}"
                                                class="form-label">Model</label>
                                            <input type="text" id="sparepart_model_{{ $sparepartCount }}"
                                                name="sparepart_model_{{ $jasaId }}" class="form-control"
                                                value="{{ $sparepart['model_sparepart'] ?? '-' }}" required>
                                        </div>
                                        <div class="col-md-1">
                                            <label for="sparepart_jumlah_{{ $sparepartCount }}"
                                                class="form-label">Jumlah</label>
                                            <input type="number" id="sparepart_jumlah_{{ $sparepartCount }}"
                                                name="sparepart_jumlah_{{ $jasaId }}" class="form-control"
                                                value="{{ $sparepart['jumlah_sparepart'] ?? 1 }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="sparepart_harga_{{ $sparepartCount }}" class="form-label">Harga
                                                Sparepart</label>
                                            <input type="number" id="sparepart_harga_{{ $sparepartCount }}"
                                                name="sparepart_harga_{{ $jasaId }}" class="form-control"
                                                value="{{ $sparepart['harga_sparepart'] ?? 0 }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="sparepart_subtotal_{{ $sparepartCount }}" class="form-label">Sub
                                                Total Sparepart</label>
                                            <input type="number" id="sparepart_subtotal_{{ $sparepartCount }}"
                                                name="sparepart_subtotal_{{ $jasaId }}"
                                                class="form-control subtotal-sparepart"
                                                value="{{ $sparepart['subtotal_sparepart'] ?? 0 }}" readonly>
                                        </div>
                                        <div class="col-md-1 mt-4 text-right">
                                            <button type="button"
                                                class="btn btn-danger btn-sm remove-sparepart-btn">&times;</button>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-secondary mt-2" id="add-sparepart-btn">Tambah
                                    Sparepart</button>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-danger">Reset</button>
                            <a href="{{ route('transaksiServis.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>

                </form>
            </div>
        </main>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Minimum Tanggal Keluar -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalMasuk = document.getElementById('tanggal_masuk');
        const tanggalKeluar = document.getElementById('tanggal_keluar');

        tanggalMasuk.addEventListener('change', function() {
            const masukDate = tanggalMasuk.value;
            tanggalKeluar.min = masukDate;
        });
    });
    </script>

    <!-- Filter Data Pelanggan -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const pelangganSelect = document.getElementById('pelanggan-input');
        const noHpSelect = document.getElementById('nohp_pelanggan-input');
        const merekSelect = document.getElementById('merek_laptop-input');
        const keluhanSelect = document.getElementById('keluhan-input');

        const laptopsData = @json($laptops);
        const pelangganData = @json($pelanggan);

        function resetAndPopulate(selectElement, options, placeholder) {
            selectElement.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
            options.forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option.value;
                optionElement.textContent = option.text;
                selectElement.appendChild(optionElement);
            });
        }

        pelangganSelect.addEventListener('change', function() {
            const selectedPelangganId = parseInt(pelangganSelect.value);

            const filteredPelanggan = pelangganData.find(pelanggan => pelanggan.id_pelanggan ===
                selectedPelangganId);
            if (filteredPelanggan) {
                resetAndPopulate(noHpSelect, [{
                    value: filteredPelanggan.nohp_pelanggan,
                    text: filteredPelanggan.nohp_pelanggan
                }], 'Pilih No. HP');
            }

            const filteredLaptops = laptopsData.filter(laptop => laptop.id_pelanggan ===
                selectedPelangganId);
            const merekOptions = filteredLaptops.map(laptop => ({
                value: laptop.merek_laptop,
                text: laptop.merek_laptop
            }));
            const keluhanOptions = filteredLaptops.map(laptop => ({
                value: laptop.deskripsi_masalah,
                text: laptop.deskripsi_masalah
            }));

            resetAndPopulate(merekSelect, merekOptions, 'Pilih Merek Laptop');
            resetAndPopulate(keluhanSelect, keluhanOptions, 'Pilih Keluhan');
        });
    });
    </script>

    <!-- Generate Garansi -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const jasaCheckboxes = document.querySelectorAll('.jasa-servis-checkbox');
        const garansiContainer = document.getElementById('garansi-container');

        function createGaransiField(serviceName, index) {
            const garansiField = document.createElement('div');
            garansiField.classList.add('garansi-item', 'row', 'mb-2', 'align-items-center');
            garansiField.innerHTML = `
                    <div class="col-md-3">
                        <label><strong>${index}. ${serviceName}</strong></label>
                    </div>
                    <div class="col-md-3">
                        <label for="tgl_mulai_${serviceName}" class="form-label">Tgl. Mulai</label>
                        <input type="date" id="tgl_mulai_${serviceName}" name="tgl_mulai_${serviceName}" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="jangka_garansi_${serviceName}" class="form-label">Jangka (Bulan)</label>
                        <input type="number" id="jangka_garansi_${serviceName}" name="jangka_garansi_${serviceName}" class="form-control" value="1" required>
                    </div>
                `;
            return garansiField;
        }

        function handleCheckboxChange() {
            garansiContainer.innerHTML = '';

            let index = 1;
            jasaCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const serviceName = checkbox.getAttribute('data-nama');
                    const garansiField = createGaransiField(serviceName, index++);
                    garansiContainer.appendChild(garansiField);
                }
            });
        }

        jasaCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', handleCheckboxChange);
        });

        handleCheckboxChange();
    });
    </script>

    <!-- Generate Sparepart, Calculate Subtotal and Total Transaksi-->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const jasaCheckboxes = document.querySelectorAll('.jasa-servis-checkbox');
        const totalTransaksiAmount = document.querySelector('.total-transaksi-amount');
        const sparepartContainer = document.getElementById('sparepart-container');
        const addSparepartBtn = document.getElementById('add-sparepart-btn');

        let sparepartCount = 0;

        // Function to calculate the total transaction amount
        function calculateTotalTransaksi() {
            let totalJasa = 0;
            let totalSparepart = 0;

            jasaCheckboxes.forEach(checkbox => {
                const jasaId = checkbox.value;
                const priceInput = document.getElementById(`customPrice_${jasaId}`);
                if (checkbox.checked) {
                    const customPrice = parseFloat(priceInput.value) || parseFloat(priceInput
                        .getAttribute('data-default-price'));
                    totalJasa += customPrice;
                }
            });

            const sparepartSubtotals = document.querySelectorAll('.subtotal-sparepart');
            sparepartSubtotals.forEach(subtotalField => {
                totalSparepart += parseFloat(subtotalField.value) || 0;
            });

            const totalTransaksi = totalJasa + totalSparepart;
            totalTransaksiAmount.textContent = `Rp. ${totalTransaksi.toLocaleString('id-ID')}`;
        }

        // Function to auto-fill custom price with default price from database
        function autoFillPrice(checkbox) {
            const jasaId = checkbox.value;
            const priceInput = document.getElementById(`customPrice_${jasaId}`);
            const defaultPrice = checkbox.getAttribute('data-price');

            if (checkbox.checked) {
                // Auto-fill the custom price field with the default price if the user hasn't manually entered a price
                if (!priceInput.value || priceInput.value === '0') {
                    priceInput.value = defaultPrice;
                }
            } else {
                priceInput.value = ''; // Clear the field if the checkbox is unchecked
            }
        }

        // Event listeners for checkbox and custom price inputs
        jasaCheckboxes.forEach(checkbox => {
            const jasaId = checkbox.value;
            const priceInput = document.getElementById(`customPrice_${jasaId}`);

            checkbox.addEventListener('change', function() {
                autoFillPrice(checkbox);
                calculateTotalTransaksi();
            });

            priceInput.addEventListener('input', function() {
                if (checkbox.checked) {
                    calculateTotalTransaksi();
                }
            });
        });

        // Function to create spare part fields dynamically
        function createSparepartFields(index) {
            const sparepartField = document.createElement('div');
            sparepartField.classList.add('row', 'mb-2', 'align-items-center', 'sparepart-item');
            sparepartField.innerHTML = `
                    <div class="col-md-2">
                        <label for="sparepart_tipe_${index}" class="form-label">Tipe</label>
                        <input type="text" id="sparepart_tipe_${index}" name="sparepart_tipe_${index}" class="form-control" placeholder="Tipe" required>
                    </div>
                    <div class="col-md-2">
                        <label for="sparepart_merek_${index}" class="form-label">Merek</label>
                        <input type="text" id="sparepart_merek_${index}" name="sparepart_merek_${index}" class="form-control" placeholder="Merek" required>
                    </div>
                    <div class="col-md-2">
                        <label for="sparepart_model_${index}" class="form-label">Model</label>
                        <input type="text" id="sparepart_model_${index}" name="sparepart_model_${index}" class="form-control" placeholder="Model" required>
                    </div>
                    <div class="col-md-1">
                        <label for="sparepart_jumlah_${index}" class="form-label">Jumlah</label>
                        <input type="number" id="sparepart_jumlah_${index}" name="sparepart_jumlah_${index}" class="form-control" value="1" required>
                    </div>
                    <div class="col-md-2">
                        <label for="sparepart_harga_${index}" class="form-label">Harga Sparepart</label>
                        <input type="number" id="sparepart_harga_${index}" name="sparepart_harga_${index}" class="form-control" value="0" required>
                    </div>
                    <div class="col-md-2">
                        <label for="sparepart_subtotal_${index}" class="form-label">Sub Total Sparepart</label>
                        <input type="number" id="sparepart_subtotal_${index}" name="sparepart_subtotal_${index}" class="form-control subtotal-sparepart" value="0" readonly>
                    </div>
                    <div class="col-md-1 mt-4 text-right">
                        <button type="button" class="btn btn-danger btn-sm remove-sparepart-btn">&times;</button>
                    </div>
                `;

            sparepartField.querySelector('.remove-sparepart-btn').addEventListener('click', function() {
                sparepartField.remove();
                calculateTotalTransaksi();
            });

            return sparepartField;
        }

        // Calculate subtotal for spare parts
        function calculateSubtotal(index) {
            const jumlahInput = document.getElementById(`sparepart_jumlah_${index}`);
            const hargaInput = document.getElementById(`sparepart_harga_${index}`);
            const subtotalInput = document.getElementById(`sparepart_subtotal_${index}`);

            function updateSubtotal() {
                const jumlah = parseFloat(jumlahInput.value) || 0;
                const harga = parseFloat(hargaInput.value) || 0;
                subtotalInput.value = jumlah * harga;
                calculateTotalTransaksi();
            }

            jumlahInput.addEventListener('input', updateSubtotal);
            hargaInput.addEventListener('input', updateSubtotal);
        }

        // Add spare part button functionality
        addSparepartBtn.addEventListener('click', function() {
            sparepartCount += 1;

            const newSparepartFields = createSparepartFields(sparepartCount);
            sparepartContainer.appendChild(newSparepartFields);

            calculateSubtotal(sparepartCount);
        });

        // Event listeners for checkbox and custom price inputs
        jasaCheckboxes.forEach(checkbox => {
            const jasaId = checkbox.value;
            const priceInput = document.getElementById(`customPrice_${jasaId}`);

            checkbox.addEventListener('change', calculateTotalTransaksi);
            priceInput.addEventListener('input', function() {
                if (checkbox.checked) {
                    calculateTotalTransaksi();
                }
            });
        });

        // Initial calculation
        calculateTotalTransaksi();
    });
    </script>

</body>

</html>
