<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiServis\TransaksiServis;
use App\Models\TransaksiSparepart\TransaksiJualSparepart;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', 'All');
        $year = $request->get('year', 'All');
        $type = $request->get('type', 'All');
        $startDay = $request->get('start_day');
        $endDay = $request->get('end_day');

        $serviceTransactions = TransaksiServis::query()->where('status_bayar', 'Sudah dibayar');
        if ($month !== 'All') $serviceTransactions->whereMonth('tanggal_masuk', $month);
        if ($year !== 'All') $serviceTransactions->whereYear('tanggal_masuk', $year);
        if ($startDay && $endDay) {
            $serviceTransactions->whereDay('tanggal_masuk', '>=', $startDay)
                                ->whereDay('tanggal_masuk', '<=', $endDay);
        }
        $serviceTransactions = $serviceTransactions->get()->map(function ($transaction) {
            return [
                'id' => $transaction->id_service,
                'type' => 'Servis',
                'date' => $transaction->tanggal_masuk,
                'amount' => $transaction->harga_total_transaksi_servis,
            ];
        });

        $sparepartTransactions = TransaksiJualSparepart::query();
        if ($month !== 'All') $sparepartTransactions->whereMonth('tanggal_jual', $month);
        if ($year !== 'All') $sparepartTransactions->whereYear('tanggal_jual', $year);
        if ($startDay && $endDay) {
            $sparepartTransactions->whereDay('tanggal_jual', '>=', $startDay)
                                  ->whereDay('tanggal_jual', '<=', $endDay);
        }
        $sparepartTransactions = $sparepartTransactions->get()->map(function ($transaction) {
            return [
                'id' => $transaction->id_transaksi_sparepart,
                'type' => 'Penjualan Sparepart',
                'date' => $transaction->tanggal_jual,
                'amount' => $transaction->harga_total_transaksi_sparepart,
            ];
        });

        $transactions = $serviceTransactions->merge($sparepartTransactions);

        if ($type !== 'All') {
            $transactions = $transactions->filter(function ($transaction) use ($type) {
                return $transaction['type'] === $type;
            });
        }

        $transactions = $transactions->sortBy('date');
        $totalProfit = $transactions->sum('amount');

        return view('laporan.index', [
            'transactions' => $transactions,
            'totalProfit' => $totalProfit,
        ]);
    }

    public function cetak(Request $request)
    {
        $month = $request->get('month', 'All');
        $year = $request->get('year', 'All');
        $type = $request->get('type', 'All');
        $startDay = $request->get('start_day');
        $endDay = $request->get('end_day');

        $serviceTransactions = TransaksiServis::query()->where('status_bayar', 'Sudah dibayar');
        if ($month !== 'All') $serviceTransactions->whereMonth('tanggal_masuk', $month);
        if ($year !== 'All') $serviceTransactions->whereYear('tanggal_masuk', $year);
        if ($startDay && $endDay) {
            $serviceTransactions->whereDay('tanggal_masuk', '>=', $startDay)
                                ->whereDay('tanggal_masuk', '<=', $endDay);
        }
        $serviceTransactions = $serviceTransactions->get()->map(function ($transaction) {
            return [
                'id' => $transaction->id_service,
                'type' => 'Servis',
                'date' => $transaction->tanggal_masuk,
                'amount' => $transaction->harga_total_transaksi_servis,
            ];
        });

        $sparepartTransactions = TransaksiJualSparepart::query();
        if ($month !== 'All') $sparepartTransactions->whereMonth('tanggal_jual', $month);
        if ($year !== 'All') $sparepartTransactions->whereYear('tanggal_jual', $year);
        if ($startDay && $endDay) {
            $sparepartTransactions->whereDay('tanggal_jual', '>=', $startDay)
                                ->whereDay('tanggal_jual', '<=', $endDay);
        }
        $sparepartTransactions = $sparepartTransactions->get()->map(function ($transaction) {
            return [
                'id' => $transaction->id_transaksi_sparepart,
                'type' => 'Penjualan Sparepart',
                'date' => $transaction->tanggal_jual,
                'amount' => $transaction->harga_total_transaksi_sparepart,
            ];
        });

        $transactions = $serviceTransactions->merge($sparepartTransactions);

        if ($type !== 'All') {
            $transactions = $transactions->filter(function ($transaction) use ($type) {
                return $transaction['type'] === $type;
            });
        }

        $transactions = $transactions->sortBy('date');
        $totalProfit = $transactions->sum('amount');

        $data = [
            'transactions' => $transactions,
            'totalProfit' => $totalProfit,
            'month' => $month,
            'year' => $year,
            'type' => $type,
            'startDay' => $startDay,
            'endDay' => $endDay,
        ];

        $pdf = Pdf::loadView('laporan.pdf', $data);

        return $pdf->download('Laporan_Transaksi.pdf');
    }
}
