<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
        }

        .header p {
            margin: 3px 0;
            font-size: 10px;
        }

        hr {
            border: none;
            border-top: 1px solid black;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .summary {
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <div class="header">
        <h1>LAPORAN TRANSAKSI</h1>
        <p>Laptop Cafe Jogjakarta</p>
        <p>Pusat service repair, upgrade, jual beli laptop second jogja dan cafe.</p>
        <p>Jl. Paingan, Krodan, Maguwoharjo - Jogjakarta.</p>
        <p>Telp. WA 085771565199</p>
        <hr>
    </div>

    <!-- Summary Section -->
    <div class="summary">
        <p><strong>Tipe Transaksi:</strong> {{ $type }}</p>
        <p><strong>Periode:</strong> {{ $month === 'All' ? 'Semua Bulan' : $month }} / {{ $year === 'All' ? 'Semua Tahun' : $year }}</p>
        @if ($startDay && $endDay)
            <p><strong>Rentang Tanggal:</strong> {{ $startDay }} - {{ $endDay }}</p>
        @endif
        <p><strong>Total Pemasukan:</strong> Rp. {{ number_format($totalProfit, 0, ',', '.') }}</p>
    </div>

    <!-- Transaction Table -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Transaksi</th>
                <th>Tipe Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Harga Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $transaction['id'] }}</td>
                    <td>{{ $transaction['type'] }}</td>
                    <td>{{ $transaction['date'] }}</td>
                    <td>Rp. {{ number_format($transaction['amount'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
