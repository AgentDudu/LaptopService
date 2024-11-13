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
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransaksiServisController extends Controller
{
    public function index()
    {
        $lastService = TransaksiServis::latest('id_service')->first();

        if ($lastService) {
            $lastIdNumber = (int) str_replace('TSV', '', $lastService->id_service);
            $nextIdNumber = $lastIdNumber + 1;
        } else {
            $nextIdNumber = 1;
        }

        $newId = 'TSV' . $nextIdNumber;

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

            $transaksiServis = TransaksiServis::create([
                'id_servis' => $request->input('id_servis'),
                'id_laptop' => $laptop->id_laptop,
                'id_teknisi' => Auth::user()->id_teknisi,
                'tanggal_masuk' => $request->input('tanggal_masuk'),
                'tanggal_keluar' => $request->input('tanggal_keluar'),
                'status_bayar' => $request->input('status_bayar'),
                'harga_total_transaksi_servis' => 0,
            ]);

            $totalJasaServis = 0;

            foreach ($request->input('jasa_servis', []) as $jasaId) {
                $jasaServis = JasaServis::find($jasaId);

                if ($jasaServis) {
                    $sparepartTipe = $request->input("sparepart_tipe_1");
                    $sparepartMerek = $request->input("sparepart_merek_1");
                    $sparepartModel = $request->input("sparepart_model_1");
                    $sparepartJumlah = $request->input("sparepart_jumlah_1");
                    $sparepartHarga = $request->input("sparepart_harga_1");
                    $sparepartSubtotal = $request->input("sparepart_subtotal_1");

                    $sparepartId = null;
                    if ($sparepartTipe && $sparepartMerek && $sparepartModel) {
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

                        $sparepartId = $sparepart->id_sparepart;
                    }

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
        $transaksiServis = TransaksiServis::with(['detailTransaksiServis.jasaServis', 'detailTransaksiServis.sparepart'])->findOrFail($id);
        $pelanggan = Pelanggan::all();
        $laptops = Laptop::all();
        $jasaServisList = JasaServis::all();

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

        return view('transaksiServis.show', compact('transaksiServis', 'pelanggan', 'laptops', 'jasaServisList', 'selectedJasaIds', 'selectedCustomPrices', 'selectedSpareparts'));
    }

    public function bayar(Request $request)
    {
        try {
            Log::info('Processing bayar request', $request->all());

            $validatedData = $request->validate([
                'id_service' => 'required|exists:service,id_service',
                'pembayaran' => 'required|numeric|min:0',
            ]);

            Log::info('Validation passed', $validatedData);

            $transaksiServis = TransaksiServis::findOrFail($validatedData['id_service']);
            Log::info('Found transaksiServis', ['id_service' => $validatedData['id_service']]);

            if ($transaksiServis->status_bayar === 'Sudah dibayar') {
                return response()->json(['success' => false, 'message' => 'Transaksi sudah dibayar'], 400);
            }

            $transaksiServis->update(['status_bayar' => 'Sudah dibayar']);
            Log::info('Updated status_bayar to Sudah dibayar');

            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in bayar', ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            Log::error('Error during bayar process', ['exception' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }

    public function cetakNota($id)
    {
        $transaksiServis = TransaksiServis::with(['detailTransaksiServis.jasaServis', 'detailTransaksiServis.sparepart', 'pelanggan', 'laptop'])->findOrFail($id);

        $totalHarga = $transaksiServis->harga_total_transaksi_servis + $transaksiServis->detailTransaksiServis->sum('subtotal_sparepart');

        $pembayaran = request('pembayaran', $totalHarga);
        $kembalian = request('kembalian', $pembayaran - $totalHarga);

        $data = [
            'transaksiServis' => $transaksiServis,
            'totalHarga' => $totalHarga,
            'pembayaran' => $pembayaran,
            'kembalian' => $kembalian,
        ];

        $pdf = \PDF::loadView('transaksiServis.invoice', $data);

        return $pdf->download('invoice_' . $transaksiServis->id_service . '.pdf');
    }

    public function sendInvoiceToWhatsapp(Request $request)
    {
        $request->validate([
            'id_service' => 'required|exists:service,id_service',
            'pembayaran' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric'
        ]);

        $transaksiServis = TransaksiServis::with('pelanggan')->find($request->id_service);
        $whatsappNumber = $transaksiServis->pelanggan->nohp_pelanggan;

        if (substr($whatsappNumber, 0, 1) === '0') {
            $whatsappNumber = '62' . substr($whatsappNumber, 1);
        }

        $totalHarga = $transaksiServis->harga_total_transaksi_servis;
        $messageText = "Halo {$transaksiServis->pelanggan->nama_pelanggan}, berikut adalah nota servis Anda. Total Harga: Rp. " . number_format($totalHarga, 0, ',', '.') . ". Pembayaran: Rp. " . number_format($request->pembayaran, 0, ',', '.') . ". Kembalian: Rp. " . number_format($request->kembalian, 0, ',', '.') . ". Terima kasih!";

        $pdf = Pdf::loadView('transaksiServis.invoice', [
            'transaksiServis' => $transaksiServis,
            'totalHarga' => $totalHarga,
            'pembayaran' => $request->pembayaran,
            'kembalian' => $request->kembalian
        ]);

        Storage::disk('public')->makeDirectory('invoices');

        $pdfPath = 'invoices/Nota_Servis_' . $transaksiServis->id_service . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        $pdfUrl = Storage::disk('public')->url($pdfPath);

        $verifySSL = app()->environment('production');

        $fonnteResponse = Http::withOptions([
            'verify' => $verifySSL
        ])->withHeaders([
            'Authorization' => 'Bearer UEwrMacTPxf167ykGu7R'
        ])->post('https://api.fonnte.com/send', [
            'target' => $whatsappNumber,
            'message' => $messageText,
            'url' => $pdfUrl,
            'filename' => 'Nota_Servis_' . $transaksiServis->id_service . '.pdf',
            'countryCode' => '62'
        ]);

        if ($fonnteResponse->successful()) {
            return response()->json(['success' => true, 'message' => 'Nota berhasil dikirim ke WhatsApp pelanggan.']);
        } else {
            Log::error('Failed to send WhatsApp message:', [
                'status' => $fonnteResponse->status(),
                'response' => $fonnteResponse->body(),
                'number' => $whatsappNumber,
                'message' => $messageText,
                'pdfUrl' => $pdfUrl
            ]);
            return response()->json(['success' => false, 'message' => 'Gagal mengirim nota via WhatsApp.']);
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

            $transaksiServis->update([
                'id_laptop' => $laptop->id_laptop,
                'tanggal_masuk' => $request->input('tanggal_masuk'),
                'tanggal_keluar' => $request->input('tanggal_keluar'),
                'status_bayar' => $request->input('status_bayar'),
            ]);

            DetailTransaksiServis::where('id_service', $transaksiServis->id_service)->delete();

            $totalJasaServis = 0;

            foreach ($request->input('jasa_servis', []) as $jasaId) {
                $jasaServis = JasaServis::find($jasaId);

                if ($jasaServis) {
                    $sparepartTipe = $request->input("sparepart_tipe_{$jasaId}");
                    $sparepartMerek = $request->input("sparepart_merek_{$jasaId}");
                    $sparepartModel = $request->input("sparepart_model_{$jasaId}");
                    $sparepartJumlah = $request->input("sparepart_jumlah_{$jasaId}");
                    $sparepartHarga = $request->input("sparepart_harga_{$jasaId}");
                    $sparepartSubtotal = $request->input("sparepart_subtotal_{$jasaId}");

                    $sparepartId = null;
                    if ($sparepartTipe && $sparepartMerek && $sparepartModel) {
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

                    DetailTransaksiServis::create([
                        'id_service' => $transaksiServis->id_service,
                        'id_jasa' => $jasaServis->id_jasa,
                        'id_sparepart' => $sparepartId,
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

            $transaksiServis = TransaksiServis::findOrFail($id);

            DetailTransaksiServis::where('id_service', $transaksiServis->id_service)->delete();

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
