<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi Sparepart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <style>
        body {
            background-color: #F8F9FA;
        }

        .main-container {
            display: flex;
            background-color: #067D40;
        }

        .sidebar {
            width: 260px;
            background-color: #067D40;
        }

        .content {
            flex: 1;
            padding: 20px;
            margin: 30px 30px 30px 0;
            background-color: #F8F9FA;
            border-radius: 20px;
        }

        .form-control,
        select {
            margin-bottom: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .pelanggan-label,
        .sparepart-label {
            font-size: 1.5rem;
            font-weight: bold;
            color: #6c757d;
        }

        .long-line {
            width: 100%;
            border-top: 2px solid grey;
            margin: 10px 0;
        }

        table.table-bordered th {
            background-color: #D7FFC2;
            color: black;
        }

        .table tbody tr td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="sidebar text-white">
            @include('pemilik.sidebar')
        </div>
        <main class="content">
            <h3>Tambah Transaksi Sparepart</h3>
            <hr>
            <div class="container">
                <form action="{{ route('transaksi_sparepart.store') }}" method="POST">
                    @csrf
                    <table class="no-border">
                        <tr>
                            <td>No. Faktur</td>
                            <td>:</td>
                            <td>
                                <input type="text" name="id_transaksi_sparepart" value="{{ $noFaktur }}" readonly
                                    class="form-control" placeholder="000001">
                            </td>
                            <td width="600px"></td>
                            <td>Total Transaksi</td>
                            <td>
                                <input type="text" name="harga_total_transaksi_sparepart"
                                    id="harga_total_transaksi_sparepart"
                                    class="form-control total-harga_sparepart-input" placeholder="Rp 0,-" required
                                    readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>Teknisi</td>
                            <td>:</td>
                            <td>
                                <select name="id_teknisi" class="form-control" required>
                                    <option value="">Pilih Teknisi</option>
                                    @foreach($teknisi as $teknisiItem)
                                        <option value="{{ $teknisiItem->id_teknisi }}">{{ $teknisiItem->nama_teknisi }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td><input type="date" name="tanggal_jual" class="form-control"></td>
                        </tr>
                    </table>

                    <div class="long-line"></div>

                    <h5 class="pelanggan-label">Pelanggan</h5>
                    <table class="no-border">
                        <tr>
                            <td>Nama Pelanggan</td>
                            <td>:</td>
                            <td>
                                <input list="pelangganList" id="id_pelanggan" name="id_pelanggan" class="form-control"
                                    required>
                                <datalist id="pelangganList">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach($pelanggan as $pelangganItem)
                                        <option value="{{ $pelangganItem->nama_pelanggan }}">
                                            {{ $pelangganItem->nama_pelanggan }}
                                        </option>
                                    @endforeach
                                </datalist>
                            </td>
                        </tr>

                        <tr>
                            <td>No HP</td>
                            <td>:</td>
                            <td><input type="text" id="nohp_pelanggan" name="nohp_pelanggan" class="form-control"
                                    readonly></td>
                        </tr>
                    </table>

                    <div class="long-line"></div>

                    <h5 class="sparepart-label">Sparepart</h5>
                    <table class="table table-bordered" id="sparepartsTable">
                        <thead>
                            <tr>
                                <th>Jenis Sparepart</th>
                                <th>Merek</th>
                                <th>Model</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" class="form-control" id="jenis_sparepart"></td>
                                <td><input type="text" class="form-control" id="merek_sparepart"></td>
                                <td><input type="text" class="form-control" id="model_sparepart"></td>
                                <td><input type="number" class="form-control sparepart-jumlah_sparepart"
                                        id="jumlah_sparepart"></td>
                                <td><input type="text" class="form-control sparepart-harga_sparepart"
                                        id="harga_sparepart"></td>
                                <td>
                                    <button type="button" class="btn btn-success addSparepartButton">Tambah</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div>
                        <button type="reset" class="btn btn-secondary me-2">Reset</button>
                        <button type="button" class="btn btn-warning me-2" id=jualButton>Jual</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('id_pelanggan').addEventListener('input', async function () {
            const namaPelanggan = this.value;
            const nohpField = document.getElementById('nohp_pelanggan');

            if (!namaPelanggan) {
                nohpField.value = "";
                return;
            }

            try {
                const response = await fetch(`/get-nohp-pelanggan?nama_pelanggan=${namaPelanggan}`);
                const data = await response.json();
                nohpField.value = data.nohp_pelanggan || "";
            } catch (error) {
                console.error('Error fetching nohp pelanggan:', error);
                nohpField.value = "";
            }
        });


        $(document).ready(function () {
            $('#id_pelanggan').on('input', function () {
                var nama_pelanggan = $(this).val();

                // Lakukan AJAX request untuk mendapatkan nomor HP pelanggan
                $.ajax({
                    url: '/pelanggan/get/' + nama_pelanggan,
                    method: 'GET',
                    success: function (response) {
                        if (response.nohp_pelanggan) {
                            $('#nohp_pelanggan').val(response.nohp_pelanggan);
                        } else {
                            $('#nohp_pelanggan').val(''); // Kosongkan jika tidak ditemukan
                        }
                    }
                });
            });
        });
        $(document).ready(function () {
            var sparepartIndex = 0;

            // Fungsi untuk menghitung total harga secara dinamis
            function calculateTotalPrice() {
                var total = 0;
                $('#sparepartsTable tbody tr').each(function () {
                    var jumlah_sparepart = parseFloat($(this).find('.sparepart-jumlah_sparepart').val()) || 0;
                    var harga_sparepart = parseFloat($(this).find('.sparepart-harga_sparepart').val().replace(/\./g, '').replace(',', '.')) || 0; // Menghapus format
                    total += jumlah_sparepart * harga_sparepart;
                });
                $('#harga_total_transaksi_sparepart').val(total.toLocaleString('id-ID'));
            }

            // Setiap kali input pada kolom harga atau jumlah berubah, otomatis hitung total transaksi
            $(document).on('input', '.sparepart-jumlah_sparepart, .sparepart-harga_sparepart', function () {
                calculateTotalPrice(); // Memanggil fungsi perhitungan total
            });

            // Tambah baris baru sparepart ke tabel
            $(document).on('click', '.addSparepartButton', function () {
                var jenis_sparepart = $('#jenis_sparepart').val();
                var merek_sparepart = $('#merek_sparepart').val();
                var model_sparepart = $('#model_sparepart').val();
                var jumlah_sparepart = parseFloat($('#jumlah_sparepart').val());
                var harga_sparepart = parseFloat($('#harga_sparepart').val().replace(/\./g, '').replace(',', '.'));

                if (jenis_sparepart && merek_sparepart && model_sparepart && jumlah_sparepart && !isNaN(harga_sparepart)) {
                    var newRow = `
                        <tr>
                            <td><input type="text" name="spareparts[${sparepartIndex}][jenis_sparepart]" class="form-control" value="${jenis_sparepart}" readonly></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][merek_sparepart]" class="form-control" value="${merek_sparepart}" readonly></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][model_sparepart]" class="form-control" value="${model_sparepart}" readonly></td>
                            <td><input type="number" name="spareparts[${sparepartIndex}][jumlah_sparepart]" class="form-control sparepart-jumlah_sparepart" value="${jumlah_sparepart}" readonly></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][harga_sparepart]" class="form-control sparepart-harga_sparepart" value="${harga_sparepart}" readonly></td>
                            <td><button type="button" class="btn btn-danger removeSparepartButton">Hapus</button></td>
                        </tr>`;

                    $('#sparepartsTable tbody').append(newRow);
                    sparepartIndex++;
                    $('#jenis_sparepart, #merek_sparepart, #model_sparepart, #jumlah_sparepart, #harga_sparepart').val('');
                    calculateTotalPrice();
                } else {
                    alert("Semua kolom sparepart harus diisi!");
                }
            });

            // Menghapus baris sparepart
            $(document).on('click', '.removeSparepartButton', function () {
                $(this).closest('tr').remove();
                calculateTotalPrice();
            });

            // Pembersihan data saat submit
            $('form').on('submit', function () {
                // Tambahkan sparepart terakhir jika ada yang belum ditambahkan
                var jenis_sparepart = $('#jenis_sparepart').val();
                var merek_sparepart = $('#merek_sparepart').val();
                var model_sparepart = $('#model_sparepart').val();
                var jumlah_sparepart = parseFloat($('#jumlah_sparepart').val());
                var harga_sparepart = parseFloat($('#harga_sparepart').val().replace(/\./g, '').replace(',', '.'));

                // Jika ada data sparepart yang belum ditambahkan
                if (jenis_sparepart && merek_sparepart && model_sparepart && jumlah_sparepart && !isNaN(harga_sparepart)) {
                    var newRow = `
                        <tr>
                            <td><input type="text" name="spareparts[${sparepartIndex}][jenis_sparepart]" class="form-control" value="${jenis_sparepart}" readonly></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][merek_sparepart]" class="form-control" value="${merek_sparepart}" readonly></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][model_sparepart]" class="form-control" value="${model_sparepart}" readonly></td>
                            <td><input type="number" name="spareparts[${sparepartIndex}][jumlah_sparepart]" class="form-control sparepart-jumlah_sparepart" value="${jumlah_sparepart}" readonly></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][harga_sparepart]" class="form-control sparepart-harga_sparepart" value="${harga_sparepart}" readonly></td>
                        </tr>`;

                    $('#sparepartsTable tbody').append(newRow);
                    sparepartIndex++;
                }

                // Bersihkan format harga untuk total transaksi sebelum disubmit
                var totalTransaksiInput = $('#harga_total_transaksi_sparepart');
                var totalBersih = totalTransaksiInput.val().replace(/\./g, '').replace(',', '.'); // Menghapus titik dan mengganti koma
                totalTransaksiInput.val(totalBersih); // Set nilai yang bersih

                // Bersihkan harga untuk setiap sparepart sebelum disubmit
                $('#sparepartsTable tbody tr').each(function () {
                    var hargaInput = $(this).find('.sparepart-harga_sparepart');
                    var hargaBersih = hargaInput.val().replace(/\./g, '').replace(',', '.'); // Menghapus titik dan mengganti koma
                    hargaInput.val(hargaBersih); // Set nilai yang bersih
                });
            });
        });
    </script>

</body>

</html>