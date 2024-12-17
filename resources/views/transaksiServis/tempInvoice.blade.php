<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Sementara</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .info-section {
            margin-top: 20px;
        }

        .info-section th {
            width: 40%;
        }

        hr {
            border: none;
            border-top: 1px solid black;
            margin: 10px 0;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <h1>NOTA SEMENTARA</h1>
        <p>Laptop Cafe Jogjakarta</p>
        <p>Pusat service repair, upgrade, jual beli laptop second jogja dan cafe.</p>
        <p>Jl. Paingan, Krodan, Maguwoharjo - Jogjakarta.</p>
        <p>Telp. WA 085771565199</p>
        <hr>
    </div>

    <!-- Information Section -->
    <div class="info-section">
        <table>
            <tr>
                <th>ID Servis</th>
                <td>{{ $id_service }}</td>
            </tr>
            <tr>
                <th>Nama Teknisi</th>
                <td>{{ $nama_teknisi }}</td>
            </tr>
            <tr>
                <th>Tanggal Masuk</th>
                <td>{{ $tanggal_masuk }}</td>
            </tr>
            <tr>
                <th>Tanggal Keluar</th>
                <td>{{ $tanggal_keluar ?? '-' }}</td>
            </tr>
            <tr>
                <th>Nama User</th>
                <td>{{ $nama_pelanggan }}</td>
            </tr>
            <tr>
                <th>No. HP</th>
                <td>{{ $nohp_pelanggan }}</td>
            </tr>
            <tr>
                <th>Merek Laptop</th>
                <td>{{ $merek_laptop }}</td>
            </tr>
            <tr>
                <th>Keluhan</th>
                <td>{{ $keluhan }}</td>
            </tr>
        </table>
    </div>

</body>
</html>
