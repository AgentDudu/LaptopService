<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan\Pelanggan;
use App\Models\Sparepart\Sparepart;
use App\Models\Auth\Teknisi;
use App\Models\TransaksiSparepart\TransaksiJualSparepart;
use App\Models\TransaksiSparepart\DetailTransaksiSparepart;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiSparepartController extends Controller
{
    public function index()
    {
        $lastSparepart = TransaksiJualSparepart::latest('id_transaksi_sparepart')->first();
        $nextId = $lastSparepart ? intval(substr($lastSparepart->id_transaksi_sparepart, 3)) + 1 : 1;
        $newId = 'TSP' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $transaksi_sparepart = TransaksiJualSparepart::with(['pelanggan', 'teknisi', 'detail_transaksi_sparepart.sparepart'])->get();
        $pelanggan = Pelanggan::all();
        $teknisi = Teknisi::all();
        $sparepart = Sparepart::all();

        return view('transaksi_sparepart.index', compact('newId', 'pelanggan', 'transaksi_sparepart', 'teknisi', 'sparepart'));
    }

    public function create()
    {
        $lastSparepart = TransaksiJualSparepart::latest('id_transaksi_sparepart')->first();
        $nextId = $lastSparepart ? intval(substr($lastSparepart->id_transaksi_sparepart, 3)) + 1 : 1;
        $newId = 'TSP' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $pelanggan = Pelanggan::all();
        $sparepart = Sparepart::all();
        $teknisi = Teknisi::all();

        return view('transaksi_sparepart.create', compact('pelanggan', 'sparepart', 'teknisi', 'newId'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            // 1. Simpan data ke tabel TransaksiJualSparepart
            $jualSparepart = TransaksiJualSparepart::create([
                'id_pelanggan' => $request->id_pelanggan,
                'id_teknisi' => $request->id_teknisi,
                'tanggal_jual' => $request->tanggal_jual,
                'harga_total_transaksi_sparepart' => $request->harga_total_transaksi_sparepart,
            ]);

            // 2. Simpan setiap sparepart baru dan detail transaksi
            foreach ($request->spareparts as $sparepartData) {
                $sparepart = Sparepart::firstOrCreate(
                    [
                        'jenis_sparepart' => $sparepartData['jenis_sparepart'],
                        'merek_sparepart' => $sparepartData['merek_sparepart'],
                        'model_sparepart' => $sparepartData['model_sparepart'],
                    ],
                    ['harga_sparepart' => $sparepartData['harga_sparepart']]
                );

                // Simpan detail transaksi
                DetailTransaksiSparepart::create([
                    'id_transaksi_sparepart' => $jualSparepart->id_transaksi_sparepart,
                    'id_sparepart' => $sparepart->id_sparepart,
                    'jumlah_sparepart_terjual' => $sparepartData['jumlah_sparepart_terjual'],
                ]);
            }
            // Mengambil data dari form POST
            $pembayaran = $request->input('pembayaran');  // Mengambil nilai dari input 'pembayaran'
            $kembalian = $pembayaran - $jualSparepart->harga_total_transaksi_sparepart;

            (['pembayaran' => $pembayaran, 'kembalian' => $kembalian]);


            // Commit transaksi jika semua berhasil
            DB::commit();
            // Redirect ke halaman nota dengan data yang diperlukan
            return redirect()->route('transaksi_sparepart.nota', [
                'id_transaksi_sparepart' => $jualSparepart->id_transaksi_sparepart,
                'pembayaran' => $pembayaran,
                'kembalian' => $kembalian
            ]);


            // return redirect()->route('transaksi_sparepart.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            // Rollback jika ada kesalahan
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        }
    }


    public function destroy($id_transaksi_sparepart)
    {
        DB::beginTransaction();

        try {
            DetailTransaksiSparepart::where('id_transaksi_sparepart', $id_transaksi_sparepart)->delete();
            $transaksiSparepart = TransaksiJualSparepart::findOrFail($id_transaksi_sparepart);
            $transaksiSparepart->delete();

            DB::commit();
            return redirect()->route('transaksi_sparepart.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage());
        }
    }

    public function show($id_transaksi_sparepart)
    {
        $transaksi_sparepart = TransaksiJualSparepart::with(['pelanggan', 'teknisi', 'detail_transaksi_sparepart.sparepart'])
            ->findOrFail($id_transaksi_sparepart);
        $pelanggan = Pelanggan::all();
        $teknisi = Teknisi::all();
        $sparepart = Sparepart::all();

        $lastSparepart = TransaksiJualSparepart::latest('id_transaksi_sparepart')->first();
        $nextId = $lastSparepart ? intval(substr($lastSparepart->id_transaksi_sparepart, 3)) + 1 : 1;
        $newId = 'TSP' . str_pad($nextId, 3, '0', STR_PAD_LEFT);


        // Return the view for displaying the details in read-only mode
        return view('transaksi_sparepart.show', compact('transaksi_sparepart', 'pelanggan', 'teknisi', 'sparepart', 'newId'));
    }

    public function nota($id_transaksi_sparepart, Request $request)
    {
        $transaksi_sparepart = TransaksiJualSparepart::with(['pelanggan', 'teknisi', 'detail_transaksi_sparepart.sparepart'])
            ->findOrFail($id_transaksi_sparepart);

        $totalHarga = $transaksi_sparepart->harga_total_transaksi_sparepart;
        // Mengambil pembayaran dan kembalian dari request (URL)
        $pembayaran = $request->query('pembayaran', $transaksi_sparepart->harga_total_transaksi_sparepart); // Default to total if not set
        $kembalian = $request->query('kembalian', $pembayaran - $transaksi_sparepart->harga_total_transaksi_sparepart); // Default to calculated difference


        // Menyiapkan data untuk ditampilkan di view
        $data = [
            'transaksi_sparepart' => $transaksi_sparepart,
            'totalHarga' => $totalHarga,
            'pembayaran' => $pembayaran,
            'kembalian' => $kembalian,
        ];

        // Generate the PDF or display the invoice view
        $pdf = PDF::loadView('transaksi_sparepart.nota', $data);
        return view('transaksi_sparepart.nota', compact('transaksi_sparepart', 'pembayaran', 'kembalian'));

    }
}