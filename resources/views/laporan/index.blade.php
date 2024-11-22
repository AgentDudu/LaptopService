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
            font-size: 16px;
            width: 300px;
            height: 80px;
        }

        .total-container span {
            font-size: 18px;
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
                        <select id="tipeTransaksi" onchange="filterTransactions()">
                            <option value="All">All</option>
                            <option value="Servis">Servis</option>
                            <option value="Penjualan Sparepart">Penjualan Sparepart</option>
                        </select>
                    </div>
                    <div class="filter-row">
                        <label for="periode">Periode:</label>
                        <select id="bulan">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                        <select id="tahun">
                            @for ($i = 2020; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        <button class="btn btn-primary" onclick="applyFilters()">Cari</button>
                    </div>
                </div>

                <!-- Total Container -->
                <div class="total-container">
                    <div>
                        Total Keuntungan: <br>
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
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function filterTransactions() {
            const typeFilter = document.getElementById('tipeTransaksi').value;
            const rows = document.querySelectorAll('#transactionTable tr');

            rows.forEach(row => {
                const type = row.getAttribute('data-type');
                if (typeFilter === 'All' || type === typeFilter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function applyFilters() {
            const bulan = document.getElementById('bulan').value;
            const tahun = document.getElementById('tahun').value;
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('month', bulan);
            urlParams.set('year', tahun);
            window.location.search = urlParams.toString();
        }
    </script>
</body>

</html>
