<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\TransaksiServis\TransaksiServis;
use App\Models\TransaksiServis\JasaServis;
use App\Models\TransaksiServis\DetailTransaksiServis;
use App\Models\TransaksiServis\DetailTransaksiServisSparepart;
use App\Models\Sparepart\Sparepart;
use App\Models\Pelanggan\Pelanggan;
use App\Models\Auth\Teknisi;
use App\Models\Laptop\Laptop;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
        // KEMBALIKAN LOGIKA INI HANYA UNTUK DITAMPILKAN DI VIEW
        $lastService = TransaksiServis::latest('id_service')->first();
        $nextIdNumber = $lastService ? (int) str_replace('TSV', '', $lastService->id_service) + 1 : 1;
        $newId = 'TSV' . $nextIdNumber;

        $pelanggan = Pelanggan::all();
        $laptops = Laptop::all();
        $jasaServisList = JasaServis::all();

        // Kirim $newId kembali ke view
        return view('transaksiServis.create', compact('newId', 'pelanggan', 'laptops', 'jasaServisList'));
    }

public function store(Request $request)
{
    $validatedData = $request->validate([
        // Hapus validasi untuk 'id_servis' karena akan dibuat otomatis
        'tanggal_masuk' => 'required|date',
        'status_bayar' => 'required|string',
        'nama_pelanggan' => 'required|string',
        'nohp_pelanggan' => 'required|string',
        'merek_laptop' => 'required|string',
        'keluhan' => 'required|string',
        'jasa_servis' => 'required|array|min:1',
        'jangka_garansi' => 'required|array',
        'akhir_garansi' => 'required|array',
        'spareparts' => 'nullable|array',
    ]);

    try {
        DB::beginTransaction();

        // 1. Urus Pelanggan dan Laptop
        $pelanggan = Pelanggan::firstOrCreate(
            ['nohp_pelanggan' => $request->nohp_pelanggan],
            ['nama_pelanggan' => $request->nama_pelanggan]
        );
        $laptop = Laptop::firstOrCreate(
            ['id_pelanggan' => $pelanggan->id_pelanggan, 'merek_laptop' => $request->merek_laptop],
            ['deskripsi_masalah' => $request->keluhan]
        );

        // 2. BUAT record TransaksiServis SEKALI SAJA di awal
        // Model akan otomatis membuat ID unik (misal: TSV6)
        $transaksiServis = TransaksiServis::create([
            'id_laptop' => $laptop->id_laptop,
            'id_teknisi' => Auth::user()->id_teknisi,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_keluar' => $request->tanggal_keluar,
            'status_bayar' => $request->status_bayar,
            'subtotal_servis' => 0, // Nilai sementara
            'subtotal_sparepart' => 0, // Nilai sementara
            'harga_total_transaksi_servis' => 0, // Nilai sementara
        ]);

        // Variabel untuk menampung total
        $totalHargaJasa = 0;
        $totalHargaSparepart = 0;

        // 3. Simpan Detail Jasa Servis dan hitung totalnya
        foreach ($request->jasa_servis as $jasaId) {
            $hargaJasa = $request->custom_price[$jasaId] ?? JasaServis::find($jasaId)->harga_jasa;
            $jangkaGaransi = $request->jangka_garansi[$jasaId] ?? 0;
            $akhirGaransi = $request->akhir_garansi[$jasaId] ?? null;

            DetailTransaksiServis::create([
                'id_service' => $transaksiServis->id_service, // Gunakan ID yang baru dibuat
                'id_jasa' => $jasaId,
                'harga_transaksi_jasa_servis' => $hargaJasa,
                'jangka_garansi_bulan' => $jangkaGaransi,
                'akhir_garansi' => $akhirGaransi,
            ]);
            $totalHargaJasa += $hargaJasa;
        }

        // 4. Simpan Detail Sparepart dan hitung totalnya
        if ($request->has('spareparts')) {
            foreach ($request->spareparts as $part) {
                if (empty($part['jenis_sparepart']) || $part['jenis_sparepart'] === '-') continue;

                $sparepart = Sparepart::firstOrCreate(
                    ['jenis_sparepart' => $part['jenis_sparepart'], 'merek_sparepart' => $part['merek_sparepart'], 'model_sparepart' => $part['model_sparepart']],
                    ['harga_sparepart' => $part['harga']]
                );
                $subtotalPart = ($part['jumlah'] ?? 1) * ($part['harga'] ?? 0);

                DetailTransaksiServisSparepart::create([
                    'id_service' => $transaksiServis->id_service, // Gunakan ID yang baru dibuat
                    'id_sparepart' => $sparepart->id_sparepart,
                    'jumlah_sparepart_terpakai' => $part['jumlah'],
                    'harga_per_unit' => $part['harga'],
                    'subtotal_sparepart' => $subtotalPart,
                ]);
                $totalHargaSparepart += $subtotalPart;
            }
        }

        // 5. UPDATE record TransaksiServis dengan total yang benar
        $transaksiServis->subtotal_servis = $totalHargaJasa;
        $transaksiServis->subtotal_sparepart = $totalHargaSparepart;
        $transaksiServis->harga_total_transaksi_servis = $totalHargaJasa + $totalHargaSparepart;
        $transaksiServis->save();

        DB::commit();

        return redirect()->route('transaksiServis.index')->with('success', 'Transaksi Servis berhasil dibuat!');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error creating Transaksi Servis: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
        return redirect()->back()->withInput()->withErrors('Gagal membuat transaksi: ' . $e->getMessage());
    }
}

    public function show($id)
    {
        // 1. Ganti relasi yang di-load
        $transaksiServis = TransaksiServis::with([
            'pelanggan',
            'laptop',
            'teknisi',
            'detailTransaksiServis.jasaServis', // Relasi untuk Jasa
            'detailServisSpareparts.sparepart' // Relasi BARU untuk Sparepart
        ])->findOrFail($id);
        
        $jasaServisList = JasaServis::all();

        // 2. Siapkan data untuk view dari sumber yang benar
        $selectedJasaIds = $transaksiServis->detailTransaksiServis->pluck('id_jasa')->toArray();
        $selectedCustomPrices = $transaksiServis->detailTransaksiServis->pluck('harga_transaksi_jasa_servis', 'id_jasa');

        // $selectedSpareparts sekarang diambil dari relasi detailServisSpareparts
        // Kita tidak perlu lagi membuat array ini secara manual di sini, karena kita bisa loop langsung di view.

        return view('transaksiServis.show', compact(
            'transaksiServis',
            'jasaServisList',
            'selectedJasaIds',
            'selectedCustomPrices'
        ));
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

    public function tempInvoice($id)
    {
        $transaksiServis = TransaksiServis::with(['pelanggan', 'laptop', 'teknisi'])->findOrFail($id);

        $data = [
            'id_service' => $transaksiServis->id_service,
            'tanggal_masuk' => $transaksiServis->tanggal_masuk,
            'tanggal_keluar' => $transaksiServis->tanggal_keluar,
            'nama_pelanggan' => $transaksiServis->pelanggan->nama_pelanggan,
            'nohp_pelanggan' => $transaksiServis->pelanggan->nohp_pelanggan,
            'merek_laptop' => $transaksiServis->laptop->merek_laptop,
            'keluhan' => $transaksiServis->laptop->deskripsi_masalah,
            'nama_teknisi' => $transaksiServis->teknisi->nama_teknisi ?? 'N/A',
        ];

        $pdf = Pdf::loadView('transaksiServis.tempInvoice', $data);

        return $pdf->download('Nota_Sementara_' . $transaksiServis->id_service . '.pdf');
    }

    public function sendTempInvoiceToWhatsapp(Request $request)
    {
        $request->validate([
            'id_service' => 'required|exists:service,id_service',
        ]);

        $transaksiServis = TransaksiServis::with('pelanggan')->findOrFail($request->id_service);
        $whatsappNumber = $transaksiServis->pelanggan->nohp_pelanggan;

        if (substr($whatsappNumber, 0, 1) === '0') {
            $whatsappNumber = '62' . substr($whatsappNumber, 1);
        }

        $data = [
            'id_service' => $transaksiServis->id_service,
            'tanggal_masuk' => $transaksiServis->tanggal_masuk,
            'tanggal_keluar' => $transaksiServis->tanggal_keluar,
            'nama_pelanggan' => $transaksiServis->pelanggan->nama_pelanggan,
            'nohp_pelanggan' => $transaksiServis->pelanggan->nohp_pelanggan,
            'merek_laptop' => $transaksiServis->laptop->merek_laptop,
            'keluhan' => $transaksiServis->laptop->deskripsi_masalah,
            'nama_teknisi' => $transaksiServis->teknisi->nama_teknisi ?? 'N/A',
        ];

        $pdf = Pdf::loadView('transaksiServis.tempInvoice', $data);

        $pdfContent = base64_encode($pdf->output());

        $messageText = "Halo {$transaksiServis->pelanggan->nama_pelanggan}, berikut adalah nota sementara Anda.";

        $fonnteToken = config('services.fonnte.token');

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$fonnteToken}"
        ])->withOptions(['verify' => false])->post('https://api.fonnte.com/send', [
            'target' => $whatsappNumber,
            'message' => $messageText,
            'file' => $pdfContent,
            'filename' => 'Nota_Sementara_' . $transaksiServis->id_service . '.pdf',
            'countryCode' => '62'
        ]);

        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Nota sementara berhasil dikirim ke WhatsApp.']);
        } else {
            Log::error('Failed to send temporary invoice:', [
                'status' => $response->status(),
                'response' => $response->body(),
                'number' => $whatsappNumber,
                'message' => $messageText
            ]);

            return response()->json(['success' => false, 'message' => 'Gagal mengirim nota sementara ke WhatsApp.']);
        }
    }

    public function cetakNota($id)
    {
        // 1. Ganti relasi yang di-load
        $transaksiServis = TransaksiServis::with([
            'pelanggan',
            'laptop',
            'teknisi', // Tambahkan teknisi jika belum ada
            'detailTransaksiServis.jasaServis',
            'detailServisSpareparts.sparepart' // Muat relasi yang BENAR
        ])->findOrFail($id);

        // Kalkulasi total sekarang lebih sederhana
        $totalHarga = $transaksiServis->harga_total_transaksi_servis;

        $pembayaran = request('pembayaran', $totalHarga);
        $kembalian = request('kembalian', $pembayaran - $totalHarga);

        $data = [
            'transaksiServis' => $transaksiServis,
            'totalHarga' => $totalHarga,
            'pembayaran' => $pembayaran,
            'kembalian' => $kembalian,
        ];

        $pdf = Pdf::loadView('transaksiServis.invoice', $data);

        return $pdf->download('Nota_Servis_' . $transaksiServis->id_service . '.pdf');
    }
    
    public function edit($id)
    {
        // 1. Muat relasi yang BENAR
        $transaksiServis = TransaksiServis::with([
            'pelanggan',
            'laptop',
            'teknisi',
            'detailTransaksiServis.jasaServis',     // Untuk detail jasa
            'detailServisSpareparts.sparepart' // Untuk detail sparepart
        ])->findOrFail($id);

        // 2. Siapkan data yang dibutuhkan oleh view
        $pelanggan = Pelanggan::all();
        $laptops = Laptop::all();
        $jasaServisList = JasaServis::all();

        // Data untuk pre-fill form Jasa Servis
        $selectedJasaIds = $transaksiServis->detailTransaksiServis->pluck('id_jasa')->toArray();
        $selectedCustomPrices = $transaksiServis->detailTransaksiServis->pluck('harga_transaksi_jasa_servis', 'id_jasa');

        // Kita tidak perlu lagi membuat $selectedSpareparts secara manual.
        // View akan langsung mengaksesnya melalui $transaksiServis->detailServisSpareparts.

        return view('transaksiServis.edit', compact(
            'transaksiServis',
            'pelanggan',
            'laptops',
            'jasaServisList',
            'selectedJasaIds',
            'selectedCustomPrices'
        ));
    }

    public function update(Request $request, $id)
    {
        // Validasi mirip dengan store, tapi id_servis tidak perlu unik
        $validatedData = $request->validate([
            'tanggal_masuk' => 'required|date',
            'status_bayar' => 'required|string',
            'nama_pelanggan' => 'required|string',
            'nohp_pelanggan' => 'required|string',
            'merek_laptop' => 'required|string',
            'keluhan' => 'required|string',
            'jasa_servis' => 'required|array|min:1',
            // ... (validasi lainnya sama seperti store) ...
        ]);

        try {
            DB::beginTransaction();

            $transaksiServis = TransaksiServis::findOrFail($id);

            // 1. Urus Pelanggan dan Laptop
            $pelanggan = Pelanggan::firstOrCreate(
                ['nohp_pelanggan' => $request->nohp_pelanggan],
                ['nama_pelanggan' => $request->nama_pelanggan]
            );

            $laptop = Laptop::firstOrCreate(
                ['id_pelanggan' => $pelanggan->id_pelanggan, 'merek_laptop' => $request->merek_laptop],
                ['deskripsi_masalah' => $request->keluhan]
            );

            // 2. Update Header Transaksi
            $akhirGaransi = null;
            $jangkaGaransi = $request->jangka_garansi_bulan ?? 0;
            if ($jangkaGaransi > 0) {
                $akhirGaransi = Carbon::parse($request->tanggal_masuk)->addMonths($jangkaGaransi)->subDay()->format('Y-m-d');
            }

            $transaksiServis->update([
                'id_laptop' => $laptop->id_laptop,
                'tanggal_masuk' => $request->tanggal_masuk,
                'tanggal_keluar' => $request->tanggal_keluar,
                'status_bayar' => $request->status_bayar,
                'jangka_garansi_bulan' => $jangkaGaransi,
                'akhir_garansi' => $akhirGaransi,
            ]);

            // 3. Hapus Detail Lama
            $transaksiServis->detailTransaksiServis()->delete();
            $transaksiServis->detailServisSpareparts()->delete();

            $totalHargaJasa = 0;
            $totalHargaSparepart = 0;

            // 4. Buat Ulang Detail Jasa (seperti di store)
            foreach ($request->jasa_servis as $jasaId) {
                $hargaJasa = $request->custom_price[$jasaId] ?? JasaServis::find($jasaId)->harga_jasa;
                DetailTransaksiServis::create([
                    'id_service' => $transaksiServis->id_service,
                    'id_jasa' => $jasaId,
                    'harga_transaksi_jasa_servis' => $hargaJasa,
                ]);
                $totalHargaJasa += $hargaJasa;
            }

            // 5. Buat Ulang Detail Sparepart (seperti di store)
            if ($request->has('spareparts')) {
                foreach ($request->spareparts as $part) {
                    if (empty($part['jenis_sparepart']) || $part['jenis_sparepart'] === '-') continue;

                    $sparepart = Sparepart::firstOrCreate(
                        ['jenis_sparepart' => $part['jenis_sparepart'], 'merek_sparepart' => $part['merek_sparepart'], 'model_sparepart' => $part['model_sparepart']],
                        ['harga_sparepart' => $part['harga']]
                    );
                    $subtotalSparepart = ($part['jumlah'] ?? 1) * ($part['harga'] ?? 0);
                    DetailTransaksiServisSparepart::create([
                        'id_service' => $transaksiServis->id_service,
                        'id_sparepart' => $sparepart->id_sparepart,
                        'jumlah_sparepart_terpakai' => $part['jumlah'],
                        'harga_per_unit' => $part['harga'],
                        'subtotal_sparepart' => $subtotalSparepart,
                    ]);
                    $totalHargaSparepart += $subtotalSparepart;
                }
            }

            // 6. Update Harga Total
            $transaksiServis->harga_total_transaksi_servis = $totalHargaJasa + $totalHargaSparepart;
            $transaksiServis->save();

            DB::commit();

            return redirect()->route('transaksiServis.index')->with('success', 'Transaksi Servis berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Transaksi Servis: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return redirect()->back()->withInput()->withErrors('Gagal memperbarui transaksi: ' . $e->getMessage());
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
