<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\TransaksiServis\TransaksiServis;
use App\Models\TransaksiServis\JasaServis;
use App\Models\TransaksiServis\DetailTransaksiServis;
use App\Models\Sparepart\Sparepart;
use App\Models\Pelanggan\Pelanggan;
use App\Models\TransaksiServis\Service;
use App\Models\Auth\Teknisi;
use App\Models\Laptop\Laptop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiServisController extends Controller
{
    public function index()
    {
        // Fetch latest TransaksiServis for ID generation
        $lastService = TransaksiServis::latest('id_service')->first();

        if ($lastService) {
            $lastIdNumber = (int) str_replace('TSV', '', $lastService->id_service);
            $nextIdNumber = $lastIdNumber + 1;
        } else {
            $nextIdNumber = 1;
        }

        $newId = 'TSV' . $nextIdNumber;

        // Fetch all necessary data for the view
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

        if ($lastService) {
            $lastIdNumber = (int) str_replace('TSV', '', $lastService->id_service);
            $nextIdNumber = $lastIdNumber + 1;
        } else {
            $nextIdNumber = 1;
        }

        $newId = 'TSV' . $nextIdNumber;

        $pelanggan = Pelanggan::all();
        $laptops = Laptop::all();
        $jasaServisList = JasaServis::all();

        return view('transaksiServis.create', compact('newId', 'pelanggan', 'laptops', 'jasaServisList'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_servis' => 'required',
            'tanggal_masuk' => 'required|date',
            'status_bayar' => 'required',
            'jasa_servis' => 'required|array',
            'nama_pelanggan' => 'required',
            'nohp_pelanggan' => 'required',
            'merek_laptop' => 'required',
            'keluhan' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Handle Pelanggan
            $pelanggan = Pelanggan::firstOrCreate(
                ['nohp_pelanggan' => $request->input('nohp_pelanggan')],
                ['nama_pelanggan' => $request->input('nama_pelanggan')]
            );

            // Handle Laptop
            $laptop = Laptop::firstOrCreate(
                [
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'merek_laptop' => $request->input('merek_laptop'),
                ],
                ['deskripsi_masalah' => $request->input('keluhan')]
            );

            // Create Transaksi Servis
            $transaksiServis = TransaksiServis::create([
                'id_servis' => $request->input('id_servis'),
                'id_laptop' => $laptop->id_laptop,
                'id_teknisi' => Auth::user()->id_teknisi,
                'tanggal_masuk' => $request->input('tanggal_masuk'),
                'tanggal_keluar' => $request->input('tanggal_keluar'),
                'status_bayar' => $request->input('status_bayar'),
                'harga_total_transaksi_servis' => 0, // Updated later
            ]);

            // Initialize total price
            $totalJasaServis = 0;

            // Process jasa_servis and spareparts
            foreach ($request->input('jasa_servis', []) as $jasaId) {
                $jasaServis = JasaServis::find($jasaId);

                if ($jasaServis) {
                    // Check for sparepart details in the request
                    $sparepartTipe = $request->input("sparepart_tipe_1");
                    $sparepartMerek = $request->input("sparepart_merek_1");
                    $sparepartModel = $request->input("sparepart_model_1");
                    $sparepartJumlah = $request->input("sparepart_jumlah_1");
                    $sparepartHarga = $request->input("sparepart_harga_1");
                    $sparepartSubtotal = $request->input("sparepart_subtotal_1");

                    $sparepartId = null;
                    if ($sparepartTipe && $sparepartMerek && $sparepartModel) {
                        // Create or find the sparepart in the sparepart table
                        $sparepart = Sparepart::firstOrCreate(
                            [
                                'jenis_sparepart' => $sparepartTipe,
                                'merek_sparepart' => $sparepartMerek,
                                'model_sparepart' => $sparepartModel
                            ],
                            [
                                'harga_sparepart' => $sparepartHarga,
                            ]
                        );

                        // Get the ID of the sparepart
                        $sparepartId = $sparepart->id_sparepart;
                    }

                    // Save detail transaksi servis
                    DetailTransaksiServis::create([
                        'id_service' => $transaksiServis->id_service,
                        'id_jasa' => $jasaServis->id_jasa,
                        'id_sparepart' => $sparepartId, // Save sparepart ID if available
                        'harga_transaksi_jasa_servis' => $request->input('custom_price')[$jasaId],
                        'jangka_garansi_bulan' => $request->input("jangka_garansi_{$jasaServis->jenis_jasa}", 1),
                        'akhir_garansi' => $request->input("tgl_mulai_{$jasaServis->jenis_jasa}"),
                        'subtotal_servis' => $request->input('custom_price')[$jasaId],
                        'subtotal_sparepart' => $sparepartSubtotal ? $sparepartSubtotal : 0,
                        'jumlah_sparepart_terpakai' => $sparepartJumlah ? $sparepartJumlah : null,
                    ]);

                    // Increment the total jasa servis price
                    $totalJasaServis += $request->input('custom_price')[$jasaId];
                }
            }

            // Update the total transaction price
            $transaksiServis->update(['harga_total_transaksi_servis' => $totalJasaServis]);

            DB::commit();

            return redirect()->route('transaksiServis.index')->with('success', 'Transaksi Servis created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating Transaksi Servis: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error creating Transaksi Servis: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Fetch the TransaksiServis details, including jasaServis and sparepart details
        $transaksiServis = TransaksiServis::with(['detailTransaksiServis.jasaServis', 'detailTransaksiServis.sparepart'])->findOrFail($id);
        $pelanggan = Pelanggan::all();
        $laptops = Laptop::all();
        $jasaServisList = JasaServis::all();

        // Extract selected jasa services and custom prices
        $selectedJasaIds = $transaksiServis->detailTransaksiServis->pluck('id_jasa')->toArray();
        $selectedCustomPrices = [];
        $selectedSpareparts = [];

        foreach ($transaksiServis->detailTransaksiServis as $detail) {
            $selectedCustomPrices[$detail->id_jasa] = $detail->harga_transaksi_jasa_servis;

            if ($detail->id_sparepart) {
                $selectedSpareparts[$detail->id_jasa] = [
                    'jenis_sparepart' => $detail->sparepart->jenis_sparepart ?? null,
                    'merek_sparepart' => $detail->sparepart->merek_sparepart ?? null,
                    'model_sparepart' => $detail->sparepart->model_sparepart ?? null,
                    'jumlah_sparepart' => $detail->jumlah_sparepart ?? null,
                    'harga_sparepart' => $detail->sparepart->harga_sparepart ?? null,
                    'subtotal_sparepart' => $detail->subtotal_sparepart ?? 0,
                ];
            }
        }

        // Return the view for displaying the details in read-only mode
        return view('transaksiServis.show', compact('transaksiServis', 'pelanggan', 'laptops', 'jasaServisList', 'selectedJasaIds', 'selectedCustomPrices', 'selectedSpareparts'));
    }

    public function bayar(Request $request)
    {
        try {
            // Find the service transaction
            $transaksiServis = TransaksiServis::findOrFail($request->id_service);

            // Check if it's already paid
            if ($transaksiServis->status_bayar === 'Sudah dibayar') {
                return response()->json(['success' => false, 'message' => 'Transaksi sudah dibayar'], 400);
            }

            // Update status to "Sudah dibayar"
            $transaksiServis->update(['status_bayar' => 'Sudah dibayar']);

            // Return a success response
            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil']);
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    public function edit($id)
    {
        $transaksiServis = TransaksiServis::with(['detailTransaksiServis.jasaServis', 'detailTransaksiServis.sparepart'])->find($id);
        $pelanggan = Pelanggan::all();
        $laptops = Laptop::all();
        $jasaServisList = JasaServis::all();

        $selectedJasaIds = $transaksiServis->detailTransaksiServis->pluck('id_jasa')->toArray();
        $selectedCustomPrices = [];
        $selectedSpareparts = [];

        foreach ($transaksiServis->detailTransaksiServis as $detail) {
            $selectedCustomPrices[$detail->id_jasa] = $detail->harga_transaksi_jasa_servis;
            if ($detail->id_sparepart) {
                $selectedSpareparts = DetailTransaksiServis::where('id_service', $transaksiServis->id_service)
                    ->with('sparepart')
                    ->get()
                    ->mapWithKeys(function ($detail) {
                        return [
                            $detail->id_jasa => [
                                'jenis_sparepart' => $detail->sparepart->jenis_sparepart ?? null,
                                'merek_sparepart' => $detail->sparepart->merek_sparepart ?? null,
                                'model_sparepart' => $detail->sparepart->model_sparepart ?? null,
                                'jumlah_sparepart' => $detail->jumlah_sparepart ?? null,
                                'harga_sparepart' => $detail->sparepart->harga_sparepart ?? null,
                                'subtotal_sparepart' => $detail->subtotal_sparepart ?? 0,
                            ],
                        ];
                    });
            }
        }

        return view('transaksiServis.edit', compact('transaksiServis', 'pelanggan', 'laptops', 'jasaServisList', 'selectedJasaIds', 'selectedCustomPrices', 'selectedSpareparts'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tanggal_masuk' => 'required|date',
            'status_bayar' => 'required',
            'jasa_servis' => 'required|array',
            'nama_pelanggan' => 'required',
            'nohp_pelanggan' => 'required',
            'merek_laptop' => 'required',
            'keluhan' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $transaksiServis = TransaksiServis::findOrFail($id);

            // Update Pelanggan and Laptop data
            $pelanggan = Pelanggan::firstOrCreate(
                ['nohp_pelanggan' => $request->input('nohp_pelanggan')],
                ['nama_pelanggan' => $request->input('nama_pelanggan')]
            );

            $laptop = Laptop::firstOrCreate(
                [
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'merek_laptop' => $request->input('merek_laptop'),
                ],
                ['deskripsi_masalah' => $request->input('keluhan')]
            );

            // Update TransaksiServis data
            $transaksiServis->update([
                'id_laptop' => $laptop->id_laptop,
                'tanggal_masuk' => $request->input('tanggal_masuk'),
                'tanggal_keluar' => $request->input('tanggal_keluar'),
                'status_bayar' => $request->input('status_bayar'),
            ]);

            // Delete old details
            DetailTransaksiServis::where('id_service', $transaksiServis->id_service)->delete();

            $totalJasaServis = 0;

            foreach ($request->input('jasa_servis', []) as $jasaId) {
                $jasaServis = JasaServis::find($jasaId);

                if ($jasaServis) {
                    // Check for sparepart details in the request
                    $sparepartTipe = $request->input("sparepart_tipe_{$jasaId}");
                    $sparepartMerek = $request->input("sparepart_merek_{$jasaId}");
                    $sparepartModel = $request->input("sparepart_model_{$jasaId}");
                    $sparepartJumlah = $request->input("sparepart_jumlah_{$jasaId}");
                    $sparepartHarga = $request->input("sparepart_harga_{$jasaId}");
                    $sparepartSubtotal = $request->input("sparepart_subtotal_{$jasaId}");

                    $sparepartId = null;
                    if ($sparepartTipe && $sparepartMerek && $sparepartModel) {
                        // Create or find the sparepart in the sparepart table
                        $sparepart = Sparepart::firstOrCreate(
                            [
                                'jenis_sparepart' => $sparepartTipe,
                                'merek_sparepart' => $sparepartMerek,
                                'model_sparepart' => $sparepartModel,
                            ],
                            [
                                'harga_sparepart' => $sparepartHarga,
                            ]
                        );

                        $sparepartId = $sparepart->id_sparepart;
                    }

                    // Save detail transaksi servis with or without sparepart
                    DetailTransaksiServis::create([
                        'id_service' => $transaksiServis->id_service,
                        'id_jasa' => $jasaServis->id_jasa,
                        'id_sparepart' => $sparepartId, // Save sparepart ID if available
                        'harga_transaksi_jasa_servis' => $request->input('custom_price')[$jasaId],
                        'jangka_garansi_bulan' => $request->input("jangka_garansi_{$jasaServis->jenis_jasa}", 1),
                        'akhir_garansi' => $request->input("tgl_mulai_{$jasaServis->jenis_jasa}"),
                        'subtotal_servis' => $request->input('custom_price')[$jasaId],
                        'subtotal_sparepart' => $sparepartSubtotal ? $sparepartSubtotal : 0,
                        'jumlah_sparepart_terpakai' => $sparepartJumlah ? $sparepartJumlah : null,
                    ]);

                    $totalJasaServis += $request->input('custom_price')[$jasaId];
                }
            }

            // Update the total transaction price
            $transaksiServis->update(['harga_total_transaksi_servis' => $totalJasaServis]);

            DB::commit();

            return redirect()->route('transaksiServis.index')->with('success', 'Transaksi Servis updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating Transaksi Servis: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error updating Transaksi Servis: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Find the TransaksiServis by ID
            $transaksiServis = TransaksiServis::findOrFail($id);

            // Delete related DetailTransaksiServis records
            DetailTransaksiServis::where('id_service', $transaksiServis->id_service)->delete();

            // Finally, delete the TransaksiServis record
            $transaksiServis->delete();

            DB::commit();

            return redirect()->route('transaksiServis.index')->with('success', 'Transaksi Servis deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting Transaksi Servis: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error deleting Transaksi Servis: ' . $e->getMessage());
        }
    }

}
