<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pelanggan\Pelanggan;
use App\Models\Sparepart\Sparepart;
use App\Models\Auth\Teknisi;
use App\Models\TransaksiSparepart\TransaksiJualSparepart;
use App\Models\TransaksiSparepart\DetailTransaksiSparepart;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiSparepartController extends Controller
{
    public function index()
    {
        $transaksi_sparepart = TransaksiJualSparepart::with(['pelanggan', 'teknisi', 'detail_transaksi_sparepart.sparepart'])->get();
        $pelanggan = Pelanggan::all();
        $teknisi = Teknisi::all();
        $sparepart = Sparepart::all();

        return view('transaksi_sparepart.index', compact('pelanggan', 'transaksi_sparepart', 'teknisi', 'sparepart'));
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
        try {
            DB::beginTransaction();

            // Step 1: Find or create Pelanggan
            $pelanggan = null; // Default to null if no pelanggan input is provided

            if (!empty($request->pelanggan_input)) {
                // Check if pelanggan exists based on the input name
                $pelanggan = Pelanggan::firstOrCreate(
                    ['nama_pelanggan' => $request->pelanggan_input],
                    ['nohp_pelanggan' => $request->nohp_pelanggan]
                );
            }

            // Step 2: Validate input data
            $validatedData = $request->validate([
                'tanggal_jual' => 'required|date',
                'harga_total_transaksi_sparepart' => 'required|numeric|min:1',
                'spareparts' => 'required|array|min:1',
                'spareparts.*.jenis_sparepart' => 'required|string',
                'spareparts.*.merek_sparepart' => 'required|string',
                'spareparts.*.model_sparepart' => 'required|string',
                'spareparts.*.jumlah_sparepart_terjual' => 'required|integer|min:1',
                'spareparts.*.harga_sparepart' => 'required|numeric|min:1',
            ]);

            // Step 3: Save the main transaction
            $jualSparepart = TransaksiJualSparepart::create([
                'id_pelanggan' => $pelanggan ? $pelanggan->id_pelanggan : null, // Set null if no pelanggan
                'id_teknisi' => Auth::user()->id_teknisi,
                'tanggal_jual' => $validatedData['tanggal_jual'],
                'harga_total_transaksi_sparepart' => $validatedData['harga_total_transaksi_sparepart'],
            ]);

            // Step 4: Save sparepart details
            foreach ($validatedData['spareparts'] as $sparepartData) {
                $sparepart = Sparepart::firstOrCreate(
                    [
                        'jenis_sparepart' => $sparepartData['jenis_sparepart'],
                        'merek_sparepart' => $sparepartData['merek_sparepart'],
                        'model_sparepart' => $sparepartData['model_sparepart'],
                    ],
                    ['harga_sparepart' => $sparepartData['harga_sparepart']]
                );

                DetailTransaksiSparepart::create([
                    'id_transaksi_sparepart' => $jualSparepart->id_transaksi_sparepart,
                    'id_sparepart' => $sparepart->id_sparepart,
                    'jumlah_sparepart_terjual' => $sparepartData['jumlah_sparepart_terjual'],
                ]);
            }

            DB::commit();

            // Step 5: Redirect with payment details
            $pembayaran = $request->input('pembayaran');
            $kembalian = $pembayaran - $jualSparepart->harga_total_transaksi_sparepart;

            return redirect()->route('transaksi_sparepart.nota', [
                'id_transaksi_sparepart' => $jualSparepart->id_transaksi_sparepart,
                'pembayaran' => $pembayaran,
                'kembalian' => $kembalian
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

    public function jual($id_transaksi_sparepart)
    {
        $jual_sparepart = TransaksiJualSparepart::with(['pelanggan', 'teknisi', 'detail_transaksi_sparepart.sparepart'])
            ->findOrFail($id_transaksi_sparepart);

        return view('transaksi_sparepart.jual', compact('jual_sparepart'));
    }

    public function show($id_transaksi_saprepart)
    {
        $transaksi_sparepart = TransaksiJualSparepart::with(['pelanggan', 'teknisi', 'detail_transaksi_sparepart.sparepart'])
            ->findOrFail($id_transaksi_saprepart);
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
        $pembayaran = $request->query('pembayaran', $transaksi_sparepart->harga_total_transaksi_sparepart);
        $kembalian = $request->query('kembalian', $pembayaran - $transaksi_sparepart->harga_total_transaksi_sparepart);

        // Menyiapkan data untuk ditampilkan di view
        $data = [
            'transaksi_sparepart' => $transaksi_sparepart,
            'totalHarga' => $totalHarga,
            'pembayaran' => $pembayaran,
            'kembalian' => $kembalian,
        ];

        $pdf = PDF::loadView('transaksi_sparepart.nota', $data);
        return $pdf->download('invoice_' . $transaksi_sparepart->id_transaksi_sparepart . '.pdf');
        // return view('transaksi_sparepart.nota', compact('transaksi_sparepart', 'pembayaran', 'kembalian'));
    }
}
