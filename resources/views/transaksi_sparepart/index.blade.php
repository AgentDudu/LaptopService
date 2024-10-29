<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Sparepart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        table.table-bordered th {
            background-color: #D7FFC2;
            color: black;
        }
    </style>
</head>

<body>
    <div class="main-container" style="display: flex; background-color: #067D40;">
        <div class="sidebar text-white">
            @include('pemilik.sidebar')
        </div>

        <!-- Main Content -->
        <main class="content"
            style="flex: 1; padding: 20px; margin: 30px 30px 30px 0; background-color: #F8F9FA; border-radius: 20px;">
            <h3><span style="color: black;">Transaksi Sparepart</span></h3>
            <hr>

            <a href="{{ route('transaksi_sparepart.create') }}" class="btn btn-primary mb-3">Tambah Transaksi
                Sparepart</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No. Faktur</th>
                        <th>Teknisi</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Jenis Sparepart</th>
                        <th>Harga Sparepart</th>
                        <th>Jumlah</th>
                        <th>Harga Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi_sparepart as $transaksi_sp)
                        @foreach($transaksi_sp->detail_transaksi_sparepart as $detail)
                            <tr data-transaction-id="{{ $transaksi_sp->id_transaksi_sparepart }}">
                                <td>{{ $transaksi_sp->id_transaksi_sparepart }}</td>
                                <td>{{ $transaksi_sp->teknisi->nama_teknisi }}</td>
                                <td>{{ $transaksi_sp->pelanggan->nama_pelanggan }}</td>
                                <td>{{ $transaksi_sp->tanggal_jual }}</td>
                                <td>{{ $detail->sparepart->jenis_sparepart ?? 'N/A' }}</td>
                                <td>Rp {{ number_format($detail->sparepart->harga_sparepart ?? 0, 2, ',', '.') }}</td>
                                <td>{{ $detail->jumlah_sparepart_terjual }}</td>
                                <td>Rp {{ number_format($transaksi_sp->harga_total_transaksi_sparepart, 2, ',', '.') }}</td>
                                <td class="actions-column">
                                    @if($transaksi_sp->status == 'pending')
                                        <a href="{{ route('transaksi_sparepart.edit', $transaksi_sp->id_transaksi_sparepart) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <button class="btn btn-sm btn-success pay-button" data-bs-toggle="modal"
                                            data-bs-target="#payModal"
                                            data-transaction="{{ json_encode($transaksi_sp) }}">Pay</button>
                                    @elseif($transaksi_sp->status == 'completed')
                                        <a href="{{ route('transaksi_sparepart.show', $transaksi_sp->id_transaksi_sparepart) }}"
                                            class="btn btn-sm btn-info">View</a>
                                    @endif
                                    <form action="{{ route('transaksi_sparepart.destroy', $transaksi_sp->id_transaksi_sparepart) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>

            </table>
        </main>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payModalLabel">Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm" action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Harus Dibayar</label>
                            <p class="form-control-static" id="total_amount">Rp. 0</p>
                        </div>
                        <div class="mb-3">
                            <label for="payment_amount" class="form-label">Pembayaran</label>
                            <input type="text" class="form-control" id="payment_amount" name="payment_amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="change_amount" class="form-label">Kembalian</label>
                            <p class="form-control-static" id="change_amount">Rp. 0</p>
                        </div>
                        <div class="alert alert-danger d-none" id="warning-message">Pembayaran kurang dari total yang
                            harus dibayar!</div>
                        <button type="submit" class="btn btn-primary" id="pay_button">Bayar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#payModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var transaction = button.data('transaction');

            var modal = $(this);
            modal.find('.modal-body #total_amount').text('Rp. ' + parseInt(transaction.total_price).toLocaleString('id-ID'));
            modal.find('.modal-body #payment_amount').val('');
            modal.find('.modal-body #change_amount').text('Rp. 0');
            modal.find('.modal-body #warning-message').addClass('d-none');

            var formAction = "{{ url('transaksi_sparepart') }}/" + transaction.id + "/pay";
            modal.find('.modal-body #paymentForm').attr('action', formAction);
            modal.find('.modal-body #paymentForm').data('transaction-id', transaction.id);
        });

        $('#payment_amount').on('input', function () {
            var total = parseInt($('#total_amount').text().replace(/[^0-9]/g, ''));
            var payment = $(this).val().replace(/[^0-9]/g, '');
            var change = payment - total;

            if (payment < total) {
                $('#warning-message').removeClass('d-none');
                $('#change_amount').text('Rp. 0');
            } else {
                $('#warning-message').addClass('d-none');
                $('#change_amount').text('Rp. ' + change.toLocaleString('id-ID'));
            }
        });

        $('#paymentForm').on('submit', function (event) {
            event.preventDefault();

            var form = $(this);
            var transactionId = form.data('transaction-id');
            var paymentAmount = $('#payment_amount').val().replace(/[^0-9]/g, '');
            var totalAmount = parseInt($('#total_amount').text().replace(/[^0-9]/g, ''));

            if (paymentAmount >= totalAmount) {
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        $('#payModal').modal('hide');
                        var row = $('tr[data-transaction-id="' + transactionId + '"]');
                        row.find('.actions-column').html(
                            '<a href="{{ url("transaksi_sparepart") }}/view/' + transactionId + '" class="btn btn-sm btn-info">View</a>' +
                            '<form action="{{ url("transaksi_sparepart") }}/' + transactionId + '" method="POST" style="display:inline-block;">' +
                            '@csrf' +
                            '@method("DELETE")' +
                            '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm("Are you sure?")">Delete</button>' +
                            '</form>'
                        );
                        row.find('td:nth-child(7)').text('completed');
                    },
                    error: function (xhr, status, error) {
                        alert('Payment failed. Please try again.');
                    }
                });
            } else {
                $('#warning-message').removeClass('d-none');
            }
        });

        $('#payModal').on('hidden.bs.modal', function () {
            window.location.href = "{{ route('transaksi_sparepart.index') }}";
        });
    </script>
</body>

</html>