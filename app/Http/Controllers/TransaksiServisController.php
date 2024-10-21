<?php

namespace App\Http\Controllers;

use App\Models\TransaksiServis\TransaksiServis;
use App\Models\TransaksiServis\JasaServis;
use App\Models\TransaksiServis\DetailTransaksiServis;
use App\Models\Sparepart\Sparepart;
use App\Models\Pelanggan\Pelanggan;
use App\Models\Auth\Teknisi;
use App\Models\Laptop\Laptop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiServisController extends Controller
{
    public function index()
    {
        $lastService = TransaksiServis::latest('id_service')->first();
        $nextId = $lastService ? $lastService->id_service + 1 : 1;
        $newId = 'TSV' . ($nextId);

        $jasaServis = TransaksiServis::with(['teknisi', 'pelanggan', 'laptop', 'detailTransaksiServis.jasaServis'])->get();
        $pelanggan = Pelanggan::all();
        $laptops = Laptop::all();
        $teknisi = Teknisi::all();
        $jasaServisList = JasaServis::all();
        $spareparts = Sparepart::all();

        return view('transaksiServis.index', compact('newId', 'jasaServis', 'pelanggan', 'teknisi', 'laptops', 'jasaServisList', 'spareparts'));
    }
    public function create()
    {
        $lastService = TransaksiServis::latest('id_service')->first();
        $nextId = $lastService ? $lastService->id_service + 1 : 1;
        $newId = 'TSV' . ($nextId);
        $pelanggan = Pelanggan::all();
        $laptops = Laptop::all();
        $jasaServisList = JasaServis::all();
        return view('transaksiServis.create',compact('newId','pelanggan', 'laptops', 'jasaServisList'));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
                'id_teknisi' => 'required|exists:teknisi,id_teknisi',
                'id_laptop' => 'required|exists:laptop,id_laptop',
                'tanggal_masuk' => 'required|date',
                'tanggal_keluar' => 'required|date|after_or_equal:tanggal_masuk',
                'status_bayar' => 'required|string',
                'jasa_servis' => 'required|array',
                'jasa_servis.*' => 'exists:jasa_servis,id_jasa',
            ]);

            $service = TransaksiServis::create([
                'id_laptop' => $request->input('id_laptop'),
                'id_teknisi' => $request->input('id_teknisi'),
                'tanggal_masuk' => $request->input('tanggal_masuk'),
                'tanggal_keluar' => $request->input('tanggal_keluar'),
                'status_bayar' => $request->input('status_bayar'),
                'harga_total_transaksi_servis' => 0,
            ]);

            $totalJasaServis = 0;
            $totalSparepart = 0;
            $sparepartData = [];

            foreach ($request->all() as $key => $value) {
                if (str_contains($key, 'sparepart_tipe_')) {
                    $index = str_replace('sparepart_tipe_', '', $key);

                    $sparepart = Sparepart::create([
                        'jenis_sparepart' => $request->input("sparepart_tipe_{$index}"),
                        'merek_sparepart' => $request->input("sparepart_merek_{$index}"),
                        'model_sparepart' => $request->input("sparepart_model_{$index}"),
                        'harga_sparepart' => $request->input("sparepart_harga_{$index}"),
                    ]);

                    $subtotal = $request->input("sparepart_jumlah_{$index}") * $sparepart->harga_sparepart;
                    $totalSparepart += $subtotal;

                    $sparepartData[] = [
                        'id_sparepart' => $sparepart->id_sparepart,
                        'jumlah_sparepart_terpakai' => $request->input("sparepart_jumlah_{$index}"),
                        'subtotal_sparepart' => $subtotal,
                    ];
                }
            }

            foreach ($request->input('jasa_servis') as $jasaId) {
                $jasaServis = JasaServis::find($jasaId);
                if ($jasaServis) {
                    $sparepart = !empty($sparepartData) ? $sparepartData[0] : null;

                    DetailTransaksiServis::create([
                        'id_service' => $service->id_service,
                        'id_jasa' => $jasaServis->id_jasa,
                        'harga_transaksi_jasa_servis' => $jasaServis->harga_jasa,
                        'id_sparepart' => $sparepart['id_sparepart'] ?? null,
                        'jumlah_sparepart_terpakai' => $sparepart['jumlah_sparepart_terpakai'] ?? 0,
                        'jangka_garansi_bulan' => $request->input("jangka_garansi_{$jasaServis->jenis_jasa}") ?? 1,
                        'akhir_garansi' => $request->input("tgl_mulai_{$jasaServis->jenis_jasa}"),
                        'subtotal_servis' => $jasaServis->harga_jasa,
                        'subtotal_sparepart' => $sparepart['subtotal_sparepart'] ?? 0,
                    ]);
                    $totalJasaServis += $jasaServis->harga_jasa;
                }
            }

            $service->update(['harga_total_transaksi_servis' => $totalJasaServis + $totalSparepart]);

            DB::commit();
            return redirect()->route('servis.index')->with('success', 'Transaksi Servis berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors('Terjadi kesalahan ketika membuat Transaksi Servis: ' . $e->getMessage());
        }
    }
}
