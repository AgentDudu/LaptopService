@php
use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <style>
    body {
        height: 100vh;
        overflow: hidden;
    }
    .main-container {
        display: flex;
        background-color: #067D40;
        height: 100%;
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
        display: flex;
        flex-direction: column;
    }
    #content-frame {
        flex-grow: 1;
        overflow-y: auto; /* Changed to auto for better scrolling */
    }
    .jasa-servis-item {
        margin-bottom: 1rem;
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
            <h3><span style="color: grey;">Tambah Transaksi Servis</span></h3>
            <hr>

            <div id="content-frame" class="container-fluid">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!--💦 FORM 💦-->
                <form action="{{ route('transaksiServis.store') }}" method="POST">
                    @csrf

                    <!-- Service Details Section -->
                    <div class="row mb-3">
                        <!-- Left side: Service Form Fields -->
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="id_servis" class="form-label me-2" style="width: 120px;">ID Servis</label>
                                    <input type="text" id="id_servis" name="id_servis" class="form-control" value="{{$newId}}" readonly>
                                </div>
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="status_bayar" class="form-label me-2" style="width: 120px;">Status Bayar</label>
                                    <select name="status_bayar" id="status_bayar" class="form-control">
                                        <option value="Pending">Pending</option>
                                        <option value="Sudah dibayar">Sudah dibayar</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="tanggal_masuk" class="form-label me-2" style="width: 120px;">Tgl Masuk</label>
                                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="tanggal_keluar" class="form-label me-2" style="width: 120px;">Tgl Keluar</label>
                                    <input type="date" id="tanggal_keluar" name="tanggal_keluar" class="form-control">
                                </div>
                                <div class="col-md-6 mb-2 d-flex align-items-center">
                                    <label for="nama_teknisi" class="form-label me-2" style="width: 120px;">Teknisi</label>
                                    <input type="text" id="nama_teknisi" class="form-control" value="{{ Auth::user()->nama_teknisi }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Right side: Total Transaksi -->
                        <div class="col-md-5 d-flex align-items-center justify-content-center">
                            <div class="card p-3 text-center w-100">
                                <label class="fs-5">Total Transaksi</label>
                                <h2 class="fw-bold total-transaksi-amount">Rp. 0</h2>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <!-- Pelanggan and Laptop Details -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Pelanggan</h5>
                            <div class="mb-2 d-flex align-items-center">
                                <label for="nama_pelanggan" class="form-label" style="width: 80px;">Nama</label>
                                <input list="pelanggan-options" name="nama_pelanggan" id="pelanggan-input" class="form-control" placeholder="Pilih atau ketik nama pelanggan" required>
                                <datalist id="pelanggan-options">
                                    @foreach ($pelanggan as $pel)
                                        <option value="{{ $pel->nama_pelanggan }}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="mb-2 d-flex align-items-center">
                                <label for="nohp_pelanggan" class="form-label" style="width: 80px;">No. HP</label>
                                <input list="nohp-options" name="nohp_pelanggan" id="nohp-pelanggan-input" class="form-control" placeholder="Pilih atau ketik no. HP" required>
                                <datalist id="nohp-options"></datalist>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Laptop</h5>
                            <div class="mb-2 d-flex align-items-center">
                                <label for="merek_laptop" class="form-label" style="width: 80px;">Merek</label>
                                <input list="laptop-options" name="merek_laptop" id="merek_laptop-input" class="form-control" placeholder="Pilih atau ketik merek laptop" required>
                                <datalist id="laptop-options"></datalist>
                            </div>
                            <div class="mb-2 d-flex align-items-center">
                                <label for="keluhan" class="form-label" style="width: 80px;">Keluhan</label>
                                <input list="keluhan-options" name="keluhan" id="keluhan-input" class="form-control" placeholder="Pilih atau ketik keluhan" required>
                                <datalist id="keluhan-options"></datalist>
                            </div>
                        </div>
                    </div>
                    <hr>

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
                                        id="jasaCheckbox_{{ $jasa->id_jasa }}">
                                    <label for="jasaCheckbox_{{ $jasa->id_jasa }}" class="form-check-label">
                                        {{ $jasa->jenis_jasa }} - (Rp {{ number_format($jasa->harga_jasa, 0, ',', '.') }})
                                    </label>
                                    <input type="number" class="form-control form-control-sm mt-1"
                                        name="custom_price[{{ $jasa->id_jasa }}]" placeholder="Harga Transaksi"
                                        id="customPrice_{{ $jasa->id_jasa }}"
                                        data-default-price="{{ $jasa->harga_jasa }}">
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
                                <!-- This container will hold dynamically generated garansi fields -->
                            </div>
                        </div>
                    </div>
                    <hr>

                    <!-- Sparepart Section -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5>Sparepart <small class="text-danger">*Isi "-" jika tidak ada</small></h5>
                            <div id="sparepart-container">
                                <!-- Dynamically generated sparepart fields will be added here -->
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" id="add-sparepart-btn">Tambah Sparepart</button>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="modal-footer mt-4">
                        <a href="{{ route('transaksiServis.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- SCRIPTS -->
    <script>
        // Set minimum dates
        document.addEventListener('DOMContentLoaded', function () {
            const tanggalMasuk = document.getElementById('tanggal_masuk');
            const tanggalKeluar = document.getElementById('tanggal_keluar');
            const today = new Date().toISOString().split('T')[0];
            tanggalMasuk.setAttribute('min', today);
            tanggalMasuk.addEventListener('change', function () {
                tanggalKeluar.min = tanggalMasuk.value;
            });
        });

        // Filter Pelanggan Data
        document.addEventListener('DOMContentLoaded', function () {
            const pelangganInput = document.getElementById('pelanggan-input');
            const noHpInput = document.getElementById('nohp_pelanggan-input');
            const merekInput = document.getElementById('merek_laptop-input');
            const keluhanInput = document.getElementById('keluhan-input');
            const laptopsData = @json($laptops);
            const pelangganData = @json($pelanggan);

            function resetAndPopulateDatalist(dataListId, options) {
                const dataList = document.getElementById(dataListId);
                dataList.innerHTML = '';
                options.forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option;
                    dataList.appendChild(optionElement);
                });
            }

            pelangganInput.addEventListener('input', function () {
                const selectedCustomer = pelangganData.find(pel => pel.nama_pelanggan === pelangganInput.value);
                if (selectedCustomer) {
                    noHpInput.value = selectedCustomer.nohp_pelanggan;
                    const filteredLaptops = laptopsData.filter(laptop => laptop.id_pelanggan === selectedCustomer.id_pelanggan);
                    resetAndPopulateDatalist('laptop-options', filteredLaptops.map(l => l.merek_laptop));
                    resetAndPopulateDatalist('keluhan-options', filteredLaptops.map(l => l.deskripsi_masalah));
                } else {
                    noHpInput.value = '';
                    resetAndPopulateDatalist('laptop-options', []);
                    resetAndPopulateDatalist('keluhan-options', []);
                }
            });
        });
    </script>
    
    <!-- Generate Garansi per Jasa -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const jasaCheckboxes = document.querySelectorAll('.jasa-servis-checkbox');
        const garansiContainer = document.getElementById('garansi-container');
        const tanggalMasukInput = document.getElementById('tanggal_masuk');

        function createGaransiField(jasaId, jasaNama) {
            const garansiField = document.createElement('div');
            garansiField.classList.add('garansi-item', 'row', 'mb-2', 'align-items-center');
            garansiField.dataset.jasaId = jasaId;

            // Note the name attributes: jangka_garansi[jasaId]
            garansiField.innerHTML = `
                <div class="col-md-3">
                    <label><strong>${jasaNama}</strong></label>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jangka (Bulan)</label>
                    <input type="number" name="jangka_garansi[${jasaId}]" class="form-control jangka-garansi-input" value="0" min="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Akhir Garansi</label>
                    <input type="date" name="akhir_garansi[${jasaId}]" class="form-control akhir-garansi-input" readonly>
                </div>
            `;

            // Add event listener to calculate end date
            const jangkaInput = garansiField.querySelector('.jangka-garansi-input');
            const akhirInput = garansiField.querySelector('.akhir-garansi-input');

            function updateAkhirGaransi() {
                const tanggalMasuk = tanggalMasukInput.value;
                const jangkaBulan = parseInt(jangkaInput.value, 10);

                if (tanggalMasuk && jangkaBulan > 0) {
                    let date = new Date(tanggalMasuk);
                    date.setMonth(date.getMonth() + jangkaBulan);
                    date.setDate(date.getDate() - 1);
                    akhirInput.value = date.toISOString().split('T')[0];
                } else {
                    akhirInput.value = '';
                }
            }

            jangkaInput.addEventListener('input', updateAkhirGaransi);
            tanggalMasukInput.addEventListener('change', updateAkhirGaransi);
            updateAkhirGaransi(); // Initial calculation

            return garansiField;
        }

        function handleCheckboxChange() {
            const checkedCheckboxes = document.querySelectorAll('.jasa-servis-checkbox:checked');
            const currentJasaIds = Array.from(checkedCheckboxes).map(cb => cb.value);
            
            // Remove fields for unchecked services
            const existingGaransiFields = garansiContainer.querySelectorAll('.garansi-item');
            existingGaransiFields.forEach(field => {
                if (!currentJasaIds.includes(field.dataset.jasaId)) {
                    field.remove();
                }
            });

            // Add fields for newly checked services
            currentJasaIds.forEach(jasaId => {
                if (!garansiContainer.querySelector(`.garansi-item[data-jasa-id="${jasaId}"]`)) {
                    const checkbox = document.getElementById(`jasaCheckbox_${jasaId}`);
                    const jasaNama = checkbox.getAttribute('data-nama');
                    const garansiField = createGaransiField(jasaId, jasaNama);
                    garansiContainer.appendChild(garansiField);
                }
            });
        }

        jasaCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', handleCheckboxChange);
        });
    });
    </script>
    
    <!-- Generate Sparepart and Calculate Totals -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const jasaCheckboxes = document.querySelectorAll('.jasa-servis-checkbox');
        const totalTransaksiAmount = document.querySelector('.total-transaksi-amount');
        const sparepartContainer = document.getElementById('sparepart-container');
        const addSparepartBtn = document.getElementById('add-sparepart-btn');

        let sparepartIndex = 0; 

        function calculateTotalTransaksi() {
            let totalJasa = 0;
            let totalSparepart = 0;

            document.querySelectorAll('.jasa-servis-checkbox:checked').forEach(checkbox => {
                const jasaId = checkbox.value;
                const priceInput = document.getElementById(`customPrice_${jasaId}`);
                totalJasa += parseFloat(priceInput.value) || 0;
            });

            document.querySelectorAll('.subtotal-sparepart').forEach(subtotalField => {
                totalSparepart += parseFloat(subtotalField.value) || 0;
            });

            const totalTransaksi = totalJasa + totalSparepart;
            totalTransaksiAmount.textContent = `Rp. ${totalTransaksi.toLocaleString('id-ID')}`;
        }

        jasaCheckboxes.forEach(checkbox => {
            const jasaId = checkbox.value;
            const priceInput = document.getElementById(`customPrice_${jasaId}`);

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    if (!priceInput.value) priceInput.value = priceInput.dataset.defaultPrice;
                } else {
                    priceInput.value = '';
                }
                calculateTotalTransaksi();
            });
            priceInput.addEventListener('input', calculateTotalTransaksi);
        });

        function createSparepartFields(index) {
            const sparepartField = document.createElement('div');
            sparepartField.classList.add('row', 'mb-2', 'align-items-center', 'sparepart-item');
            sparepartField.dataset.index = index;

            sparepartField.innerHTML = `
                <div class="col-md-2"><input type="text" name="spareparts[${index}][jenis_sparepart]" class="form-control form-control-sm" placeholder="Tipe" required></div>
                <div class="col-md-2"><input type="text" name="spareparts[${index}][merek_sparepart]" class="form-control form-control-sm" placeholder="Merek" required></div>
                <div class="col-md-2"><input type="text" name="spareparts[${index}][model_sparepart]" class="form-control form-control-sm" placeholder="Model" required></div>
                <div class="col-md-1"><input type="number" name="spareparts[${index}][jumlah]" class="form-control form-control-sm sparepart-jumlah" value="1" min="1" required></div>
                <div class="col-md-2"><input type="number" name="spareparts[${index}][harga]" class="form-control form-control-sm sparepart-harga" value="0" min="0" required></div>
                <div class="col-md-2"><input type="number" name="spareparts[${index}][subtotal]" class="form-control form-control-sm subtotal-sparepart" value="0" readonly></div>
                <div class="col-md-1 text-end"><button type="button" class="btn btn-danger btn-sm remove-sparepart-btn">×</button></div>
            `;

            sparepartField.querySelector('.remove-sparepart-btn').addEventListener('click', () => {
                sparepartField.remove();
                calculateTotalTransaksi();
            });

            const jumlahInput = sparepartField.querySelector('.sparepart-jumlah');
            const hargaInput = sparepartField.querySelector('.sparepart-harga');
            const subtotalInput = sparepartField.querySelector('.subtotal-sparepart');

            function updateSubtotal() {
                const jumlah = parseFloat(jumlahInput.value) || 0;
                const harga = parseFloat(hargaInput.value) || 0;
                subtotalInput.value = jumlah * harga;
                calculateTotalTransaksi();
            }

            jumlahInput.addEventListener('input', updateSubtotal);
            hargaInput.addEventListener('input', updateSubtotal);

            return sparepartField;
        }

        addSparepartBtn.addEventListener('click', function() {
            const newSparepartFields = createSparepartFields(sparepartIndex);
            sparepartContainer.appendChild(newSparepartFields);
            sparepartIndex++;
        });

        calculateTotalTransaksi(); // Initial calculation
    });
    </script>

</body>

</html>