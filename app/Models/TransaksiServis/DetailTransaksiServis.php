<?php

namespace App\Models\TransaksiServis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sparepart\Sparepart; // Bisa dihapus jika tidak ada relasi langsung

class DetailTransaksiServis extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi_servis';

    // Biarkan Laravel mengelola primary key (default: 'id', auto-increment)
    // dan timestamps (created_at, updated_at).
    public $timestamps = true;

    protected $fillable = [
        'id_service',
        'id_jasa',
        'harga_transaksi_jasa_servis',
        'jangka_garansi_bulan',
        'akhir_garansi'
        // Kolom 'subtotal_servis' sudah dihapus dari sini.
    ];

    public function transaksiServis()
    {
        return $this->belongsTo(TransaksiServis::class, 'id_service', 'id_service');
    }

    public function jasaServis()
    {
        return $this->belongsTo(JasaServis::class, 'id_jasa', 'id_jasa');
    }
}