<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelanggan\Pelanggan;
use App\Models\Sparepart\Sparepart;
use App\Models\Auth\Teknisi;
use App\Models\TransaksiSparepart\TransaksiJualSparepart;
use App\Models\TransaksiSparepart\DetailTransaksiSparepart;
use Illuminate\Support\Facades\DB;

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

        // try {
        // 1. Simpan data ke tabel TransaksiJualSparepart
        $jualSparepart = TransaksiJualSparepart::create([
            'id_pelanggan' => $request->id_pelanggan,
            'id_teknisi' => Auth::user()->id_teknisi,
            'tanggal_jual' => $request->tanggal_jual,
            'harga_total_transaksi_sparepart' => $request->harga_total_transaksi_sparepart,
        ]);

        // 2. Simpan setiap sparepart baru dan detail transaksi
        foreach ($request->spareparts as $sparepartData) {
            // dd('berhasil');
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

        // Commit transaksi jika semua berhasil
        DB::commit();

        return redirect()->route('transaksi_sparepart.index')->with('success', 'Transaksi berhasil disimpan.');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        // }
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

}
