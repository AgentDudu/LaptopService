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

        .container {
            max-width: 100%;
            margin-left: 0px;
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
            max-height: 94.5vh;
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
            font-size: 1.1rem;
            font-weight: 500;
            color: black;
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

        .content-frame {
            width: 100%;
            height: 35vh;
            max-height: 35vh;
            overflow-y: scroll;
        }

        .allbtn {
            margin-top: 5px;
            /* position: relative; */
            /* height: 90vh; */
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
        <main class="content">
            <h3 style="color: grey">Tambah Transaksi Sparepart</h3>
            <hr>
            <div class="container">
                <form action="{{ route('transaksi_sparepart.store') }}" method="POST" id="form-tambah">
                    @csrf
                    <table class="no-border">
                        <tr>
                            <td>No. Faktur</td>
                            <td width="1%"></td>
                            <td>
                                <input type="text" name="id_transaksi_sparepart" value="{{ $newId }}" readonly
                                    class="form-control" placeholder="TSP001">
                            </td>
                            <td width="20px"></td>

                            <td>Tanggal</td>
                            <td width="1%"></td>
                            <td width="200px"><input type="date" name="tanggal_jual" class="form-control" required>
                            </td>
                            <td width="20%"></td>

                            <td>Total Transaksi</td>
                            <td width="1%"></td>
                            <td width="20%">
                                <input type="text" name="harga_total_transaksi_sparepart"
                                    id="harga_total_transaksi_sparepart"
                                    class="form-control total-harga_sparepart-input" placeholder="Rp 0,-" required
                                    readonly style="border: 1px solid #ccc; padding: 15px; background-color: white;">
                            </td>
                        </tr>
                        <tr>
                            <td>Teknisi</td>
                            <td></td>
                            <td>
                                <input type="text" id="nama_teknisi" name="nama_teknisi" class="form-control"
                                    value="{{ Auth::user()->nama_teknisi }}" readonly>
                            </td>
                        </tr>
                    </table>

                    <div class="long-line"></div>

                    <h6 class="pelanggan-label">User</h6>
                    <table class="no-border">
                        <tr>
                        <tr>
                            <td>User</td>
                            <td width="9%"></td>
                            <td width="210px">
                            <input list="pelanggan-options" name="pelanggan_input" id="pelanggan-input" class="form-control" placeholder="Pilih/Ketik Nama User" required>
                                <datalist id="pelanggan-options">
                                    @foreach ($pelanggan as $pelangganItem)
                                        <option value="{{ $pelangganItem->nama_pelanggan }}"></option>
                                    @endforeach
                                </datalist>
                            </td>
                            <td width="20px"></td>
                            <td>No HP</td>
                            <td width="2%"></td>
                            <td><input type="text" id="nohp_pelanggan" name="nohp_pelanggan" class="form-control">
                            </td>
                        </tr>
                    </table>

                    <div class="long-line"></div>

                    <h6 class="sparepart-label">Sparepart</h6>
                    <div class="content-frame">
                        <table class="table table-bordered" id="sparepartsTable">
                            <thead>
                                <tr>
                                    <th>Jenis Sparepart</th>
                                    <th>Merek</th>
                                    <th>Model</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" id="jenis_sparepart"></td>
                                    <td><input type="text" class="form-control" id="merek_sparepart"></td>
                                    <td><input type="text" class="form-control" id="model_sparepart"></td>
                                    <td><input type="number" class="form-control sparepart-jumlah_sparepart_terjual"
                                            id="jumlah_sparepart_terjual"></td>
                                    <td><input type="text" class="form-control sparepart-harga_sparepart"
                                            id="harga_sparepart"></td>
                                    <td><input type="text" class="form-control sparepart-subtotal" id="subtotal"
                                            readonly>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-success addSparepartButton">Tambah</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="allbtn">
                        <button type="reset" class="btn btn-secondary me-2">Reset</button>
                        <button type="button" class="btn btn-warning me-2" id=jualButton>Jual</button>
                    </div>
            </div>
            <!-- Modal Pembayaran -->
            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="total_amount" class="form-label">Harus Dibayar</label>
                                <p class="form-control-static">Rp. <span id="total_amount_display">0</span></p>
                            </div>
                            <div class="mb-3">
                                <label for="pembayaran" class="form-label">Pembayaran</label>
                                <input type="number" class="form-control" id="pembayaran" name="pembayaran"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="kembalian" class="form-label">Kembalian</label>
                                <p class="form-control-static" id="kembalian">Rp. 0</p>
                            </div>
                            <button form="form-tambah" type="submit" class="btn btn-primary" id="bayarButton"
                                onclick=processPayment()>Bayar & Cetak
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            </form>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('harga_sparepart').addEventListener('input', function() {
            let input = this.value;
            input = input.replace(/\D/g, '');
            let formatted = new Intl.NumberFormat('id-ID').format(input);
            this.value = formatted;
        });

        $(document).ready(function() {
            $('#id_pelanggan').on('input', function() {
                var nama_pelanggan = $(this).val();

                // Lakukan AJAX request untuk mendapatkan nomor HP pelanggan
                $.ajax({
                    url: '/pelanggan/get/' + encodeURIComponent(
                        nama_pelanggan), // encodeURIComponent untuk aman dari karakter spesial
                    method: 'GET',
                    success: function(response) {
                        $('#nohp_pelanggan').val(response.nohp_pelanggan ||
                            ''); // Mengisi nomor HP atau kosong jika tidak ada
                    },
                    error: function() {
                        $('#nohp_pelanggan').val(''); // Kosongkan jika ada error
                    }
                });
            });
        });

        $(document).ready(function() {
            var sparepartIndex = 0;

            // Fungsi untuk menghitung total harga secara dinamis
            function calculateTotalPrice() {
                var total = 0;
                $('#sparepartsTable tbody tr').each(function() {
                    var jumlah_sparepart_terjual = parseFloat($(this).find(
                        '.sparepart-jumlah_sparepart_terjual').val()) || 0;
                    var harga_sparepart = parseFloat($(this).find('.sparepart-harga_sparepart').val()
                        .replace(/\./g, '').replace(',', '.')) || 0; // Menghapus format
                    var subtotal = jumlah_sparepart_terjual * harga_sparepart;

                    // Set nilai subtotal untuk baris saat ini
                    $(this).find('.sparepart-subtotal').val(`${subtotal.toLocaleString('id-ID')}`);

                    // Tambahkan ke total transaksi
                    total += subtotal;
                });
                $('#harga_total_transaksi_sparepart').val(total.toLocaleString('id-ID'));
                $('#total_amount_display').text(total.toLocaleString('id-ID')); // Set total ke modal
            }

            // Setiap kali input pada kolom harga atau jumlah berubah, otomatis hitung total transaksi
            $(document).on('input', '.sparepart-jumlah_sparepart_terjual, .sparepart-harga_sparepart', function() {
                calculateTotalPrice();
            });

            // Event untuk tombol "Jual" membuka modal dan menampilkan total transaksi
            $('#jualButton').on('click', function() {
                calculateTotalPrice(); // Pastikan total diperbarui sebelum membuka modal
                $('#paymentModal').modal('show'); // Tampilkan modal
            });

            // Menghitung kembalian saat input pembayaran diubah
            $('#pembayaran').on('input', function() {
                const total = parseFloat($('#total_amount_display').text().replace(/\./g, '').replace(',',
                    '.')) || 0;
                const payment = parseFloat($(this).val().replace(/[^0-9]/g, '') || 0);
                const change = payment - total;

                // Display the calculated change
                $('#kembalian').text('Rp. ' + (change >= 0 ? change : 0).toLocaleString('id-ID'));
            });


            // Setiap kali input pada kolom harga atau jumlah berubah, otomatis hitung total transaksi
            $(document).on('input', '.sparepart-jumlah_sparepart_terjual, .sparepart-harga_sparepart', function() {
                calculateTotalPrice(); // Memanggil fungsi perhitungan total
            });

            // Tambah baris baru sparepart ke tabel
            $(document).on('click', '.addSparepartButton', function() {
                var jenis_sparepart = $('#jenis_sparepart').val();
                var merek_sparepart = $('#merek_sparepart').val();
                var model_sparepart = $('#model_sparepart').val();
                var jumlah_sparepart_terjual = parseFloat($('#jumlah_sparepart_terjual').val());
                var harga_sparepart = parseFloat($('#harga_sparepart').val().replace(/\./g, '').replace(',',
                    '.'));

                if (jenis_sparepart && merek_sparepart && model_sparepart && jumlah_sparepart_terjual && !
                    isNaN(harga_sparepart)) {
                    var subtotal = jumlah_sparepart_terjual * harga_sparepart;

                    var newRow = `
                        <tr>
                            <td><input type="text" name="spareparts[${sparepartIndex}][jenis_sparepart]" class="form-control" value="${jenis_sparepart}" ></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][merek_sparepart]" class="form-control" value="${merek_sparepart}" ></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][model_sparepart]" class="form-control" value="${model_sparepart}" ></td>
                            <td><input type="number" name="spareparts[${sparepartIndex}][jumlah_sparepart_terjual]" class="form-control sparepart-jumlah_sparepart_terjual" value="${jumlah_sparepart_terjual}" ></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][harga_sparepart]" class="form-control sparepart-harga_sparepart" value="${harga_sparepart}" ></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][subtotal]" class="form-control sparepart-subtotal" value="${subtotal}" ></td>

                            <td><button type="button" class="btn btn-danger removeSparepartButton">Hapus</button></td>
                        </tr>`;

                    $('#sparepartsTable tbody').append(newRow);
                    sparepartIndex++;
                    $('#jenis_sparepart, #merek_sparepart, #model_sparepart, #jumlah_sparepart_terjual, #harga_sparepart')
                        .val('');
                    $('#subtotal')
                    calculateTotalPrice();
                } else {
                    alert("Semua kolom sparepart harus diisi!");
                }
            });

            // Menghapus baris sparepart
            $(document).on('click', '.removeSparepartButton', function() {
                $(this).closest('tr').remove();
                calculateTotalPrice();
            });

            // Pembersihan data saat submit
            $('form').on('submit', function() {
                // Tambahkan sparepart terakhir jika ada yang belum ditambahkan
                var jenis_sparepart = $('#jenis_sparepart').val();
                var merek_sparepart = $('#merek_sparepart').val();
                var model_sparepart = $('#model_sparepart').val();
                var jumlah_sparepart_terjual = parseFloat($('#jumlah_sparepart_terjual').val());
                var harga_sparepart = parseFloat($('#harga_sparepart').val().replace(/\./g, '').replace(',',
                    '.'));

                // Jika ada data sparepart yang belum ditambahkan
                if (jenis_sparepart && merek_sparepart && model_sparepart && jumlah_sparepart_terjual && !
                    isNaN(harga_sparepart)) {
                    var subtotal = jumlah_sparepart_terjual * harga_sparepart;

                    var newRow = `
                        <tr>
                            <td><input type="text" name="spareparts[${sparepartIndex}][jenis_sparepart]" class="form-control" value="${jenis_sparepart}" readonly></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][merek_sparepart]" class="form-control" value="${merek_sparepart}" readonly></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][model_sparepart]" class="form-control" value="${model_sparepart}" readonly></td>
                            <td><input type="number" name="spareparts[${sparepartIndex}][jumlah_sparepart_terjual]" class="form-control sparepart-jumlah_sparepart_terjual" value="${jumlah_sparepart_terjual}" readonly></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][harga_sparepart]" class="form-control sparepart-harga_sparepart" value="${harga_sparepart}" readonly></td>
                            <td><input type="text" name="spareparts[${sparepartIndex}][subtotal]" class="form-control sparepart-subtotal" value="${subtotal}" readonly></td>

                        </tr>`;

                    $('#sparepartsTable tbody').append(newRow);
                    sparepartIndex++;
                }

                // Bersihkan format harga untuk total transaksi sebelum disubmit
                var totalTransaksiInput = $('#harga_total_transaksi_sparepart');
                var totalBersih = totalTransaksiInput.val().replace(/\./g, '').replace(',',
                    '.'); // Menghapus titik dan mengganti koma
                totalTransaksiInput.val(totalBersih); // Set nilai yang bersih

                // Bersihkan harga untuk setiap sparepart sebelum disubmit
                $('#sparepartsTable tbody tr').each(function() {
                    var hargaInput = $(this).find('.sparepart-harga_sparepart');
                    var hargaBersih = hargaInput.val().replace(/\./g, '').replace(',',
                        '.'); // Menghapus titik dan mengganti koma
                    hargaInput.val(hargaBersih); // Set nilai yang bersih
                });
            });

            function processPayment() {
                alert("Pembayaran berhasil!");

                // Mengubah tombol "Bayar" menjadi "Cetak Nota"
                let bayarButton = document.getElementById("bayarButton");
                bayarButton.innerHTML = "Cetak Nota";
                bayarButton.classList.remove("btn-primary"); // Menghapus kelas btn-primary
                bayarButton.classList.add("btn-success"); // Mengubah warna tombol menjadi hijau
                bayarButton.onclick = function() {
                    window.print(); // Fungsi cetak ketika tombol diklik
                };
            }
        });

        document.getElementById('pelanggan-input').addEventListener('input', function() {
            const pelangganInput = this.value;
            const pelangganData = @json($pelanggan);
            const matchedPelanggan = pelangganData.find(item => item.nama_pelanggan === pelangganInput);
            const nohpField = document.getElementById('nohp_pelanggan');
            if (matchedPelanggan) {
                nohpField.value = matchedPelanggan.nohp_pelanggan;
            } else {
                nohpField.value = '';
            }
        });

        paymentModal.addEventListener("hidden.bs.modal", function () {
            window.location.href = "{{ route('transaksi_sparepart.index') }}";
        });
    </script>

</body>

</html>