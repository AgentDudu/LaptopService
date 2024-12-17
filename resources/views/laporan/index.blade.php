<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .filter-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filter-fields {
            display: flex;
            flex-direction: column;
        }

        .filter-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            gap: 10px;
        }

        .filter-fields label {
            font-weight: bold;
        }

        .filter-fields select,
        .filter-fields button {
            padding: 5px;
            font-size: 14px;
        }

        .total-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            font-weight: bold;
            font-size: 24px;
            width: 500px;
            height: 80px;
        }

        .total-container span {
            font-size: 24px;
            font-weight: bold;
            margin-left: 10px;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        table thead th {
            background-color: #D7FFC2;
            font-weight: bold;
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

        <!-- Main Content -->
        <main class="content">
            <h3 style="color: grey;">Laporan Transaksi</h3>
            <hr>

            <!-- Filter and Total Keuntungan -->
            <div class="filter-container">
                <!-- Filter Fields -->
                <div class="filter-fields">
                    <div class="filter-row">
                        <label for="tipeTransaksi">Tipe Transaksi:</label>
                        <select id="tipeTransaksi" style="width: 175px; margin-left: 21px;">
                            <option value="All">All</option>
                            <option value="Servis">Servis</option>
                            <option value="Penjualan Sparepart">Penjualan Sparepart</option>
                        </select>
                    </div>
                    <div class="filter-row">
                        <label for="periode">Periode:</label>
                        <select id="bulan" style="margin-left: 69px;">
                            <option value="All" selected>All</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == request('month') ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                        <select id="tahun">
                            <option value="All" selected>All</option>
                            @for ($i = 2020; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $i == request('year') ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-row">
                    <label for="dayRange">Rentang Tanggal:</label>
                        <input type="number" id="startDay" min="1" max="31" placeholder="Start Day" style="width: 80px;">
                        <span>-</span>
                        <input type="number" id="endDay" min="1" max="31" placeholder="End Day" style="width: 80px;">
                        <button class="btn btn-primary" onclick="applyFilters()" style="width: 70px;">Cari</button>
                        <button class="btn btn-secondary" onclick="resetFilters()" style="width: 70px;">Reset</button>
                        <button class="btn btn-success" onclick="cetakLaporan()">Cetak Laporan</button>
                    </div>
                </div>

                <!-- Total Container -->
                <div class="total-container">
                    <div>
                        Total Pemasukan: <br>
                        <span id="totalProfit">Rp. {{ number_format($totalProfit, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Transaction Table -->
            <div class="table-container">
                <table class="table table-bordered">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>No Transaksi</th>
                            <th>Tipe Transaksi</th>
                            <th>Tanggal Transaksi</th>
                            <th>Harga Transaksi</th>
                        </tr>
                    </thead>
                    <tbody id="transactionTable">
                        @if ($transactions->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data untuk periode dan tipe transaksi
                                    yang dipilih.</td>
                            </tr>
                        @else
                            @php $no = 1; @endphp
                            @foreach ($transactions as $transaction)
                                <tr data-type="{{ $transaction['type'] }}">
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $transaction['id'] }}</td>
                                    <td>{{ $transaction['type'] }}</td>
                                    <td>{{ $transaction['date'] }}</td>
                                    <td>Rp. {{ number_format($transaction['amount'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script>
    function updateDayRange() {
        const month = document.getElementById('bulan').value;
        const year = document.getElementById('tahun').value;
        const startDayInput = document.getElementById('startDay');
        const endDayInput = document.getElementById('endDay');

        if (month === 'All' || year === 'All') {
            startDayInput.disabled = true;
            endDayInput.disabled = true;
            return;
        } else {
            startDayInput.disabled = false;
            endDayInput.disabled = false;
        }

        const daysInMonth = new Date(year, month, 0).getDate();

        startDayInput.max = daysInMonth;
        endDayInput.max = daysInMonth;

        if (startDayInput.value > daysInMonth) startDayInput.value = '';
        if (endDayInput.value > daysInMonth) endDayInput.value = '';

        enforceEndDayLimit();
    }

    function enforceEndDayLimit() {
        const endDayInput = document.getElementById('endDay');
        const maxDay = parseInt(endDayInput.max);

        endDayInput.addEventListener('input', function () {
            const value = parseInt(this.value) || 0;

            if (value > maxDay) {
                this.value = maxDay;
            }
        });
    }

    function applyFilters() {
        const type = document.getElementById('tipeTransaksi').value;
        const month = document.getElementById('bulan').value;
        const year = document.getElementById('tahun').value;
        const startDay = document.getElementById('startDay').value;
        const endDay = document.getElementById('endDay').value;

        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('type', type);
        urlParams.set('month', month);
        urlParams.set('year', year);

        if (startDay && endDay) {
            urlParams.set('start_day', startDay);
            urlParams.set('end_day', endDay);
        } else {
            urlParams.delete('start_day');
            urlParams.delete('end_day');
        }

        window.location.search = urlParams.toString();
    }

    function setFilterValues() {
        const urlParams = new URLSearchParams(window.location.search);
        document.getElementById('tipeTransaksi').value = urlParams.get('type') || 'All';
        document.getElementById('bulan').value = urlParams.get('month') || 'All';
        document.getElementById('tahun').value = urlParams.get('year') || 'All';
    }

    document.getElementById('bulan').addEventListener('change', updateDayRange);
    document.getElementById('tahun').addEventListener('change', updateDayRange);
    document.getElementById('endDay').addEventListener('input', enforceEndDayLimit);

    document.addEventListener('DOMContentLoaded', function () {
        updateDayRange();
        setFilterValues();
        enforceEndDayLimit();
    });

    function resetFilters() {
    window.location.href = window.location.pathname;
    }

    function cetakLaporan() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = `/laporan/cetak?${params.toString()}`;
    }
</script>
</body>
</html>
