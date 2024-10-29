<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Sparepart;
use App\Models\Teknisi;
use App\Models\JualSparepart;
use App\Models\DetailTransaksiSparepart;

class TransaksiSparepartController extends Controller
{
    public function index()
    {
        $transaksi_sparepart = JualSparepart::with(['pelanggan', 'teknisi', 'detail_transaksi_sparepart.sparepart'])->get();
        return view('transaksi_sparepart.index', compact('transaksi_sparepart'));
    }

    public function create()
    {
        // Get data needed for form
        $pelanggan = Pelanggan::all();
        $sparepart = Sparepart::all();
        $teknisi = Teknisi::all();

        // Generate the new invoice number
        $lastInvoice = JualSparepart::orderBy('id_transaksi_sparepart', 'desc')->first();
        $newInvoiceNumber = $lastInvoice ? (int) $lastInvoice->id_transaksi_sparepart + 1 : 1;
        $noFaktur = str_pad($newInvoiceNumber, 6, '0', STR_PAD_LEFT);

        return view('transaksi_sparepart.create', compact('pelanggan', 'sparepart', 'teknisi', 'noFaktur'));
    }

    public function store(Request $request)
    {
        // Validate the request
        dd($request->all());
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'id_teknisi' => 'required|exists:teknisi,id_teknisi',
            'tanggal_jual' => 'required|date',
            'harga_total_transaksi_sparepart' => 'required|numeric',
            'spareparts' => 'required|array',
            'spareparts.*.id_sparepart' => 'required|exists:spareparts,id_sparepart',
            'spareparts.*.jumlah_sparepart' => 'required|numeric|min:1',
            'spareparts.*.harga_sparepart' => 'required|numeric|min:0',
        ]);

        // Create the main JualSparepart transaction
        $transaksi_sparepart = JualSparepart::create([
            'id_transaksi_sparepart' => $request->id_transaksi_sparepart,
            'id_pelanggan' => $request->id_pelanggan,
            'id_teknisi' => $request->id_teknisi,
            'tanggal_jual' => $request->tanggal_jual,
            'harga_total_transaksi_sparepart' => $request->harga_total_transaksi_sparepart,
        ]);

        // Save the related spare parts for the transaction
        foreach ($request->spareparts as $sparepart) {
            DetailTransaksiSparepart::create([
                'id_transaksi_sparepart' => $transaksi_sparepart->id_transaksi_sparepart,
                'id_sparepart' => $sparepart->id_sparepart, // Auto-increment handled in DB
                'jumlah_sparepart_terjual' => $sparepart['jumlah_sparepart'],
                'harga_sparepart' => $sparepart['harga_sparepart'],
            ]);
        }

        return redirect()->route('transaksi_sparepart.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function jual($id_transaksi_sparepart)
    {
        // Retrieve the JualSparepart by ID
        $jual_sparepart = JualSparepart::with(['pelanggan', 'teknisi', 'detail_transaksi_sparepart.sparepart'])
            ->findOrFail($id_transaksi_sparepart);

        return view('transaksi_sparepart.jual', compact('jual_sparepart'));
    }
}