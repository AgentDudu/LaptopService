<?php

namespace App\Models\TransaksiServis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pelanggan\Pelanggan;
use App\Models\Auth\Teknisi;
use App\Models\Laptop\Laptop;
use App\Models\TransaksiServis\DetailTransaksiServis;
use App\Models\TransaksiServis\DetailTransaksiServisSparepart;

class TransaksiServis extends Model
{
    use HasFactory;

    protected $table = 'service';
    protected $primaryKey = 'id_service';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksiServis) {
            if (!$transaksiServis->id_service) {
                $latestTransaksiServis = static::latest('id_service')->first();
                if (!$latestTransaksiServis) {
                    $nextIdNumber = 1;
                } else {
                    $lastId = (int) str_replace('TSV', '', $latestTransaksiServis->id_service);
                    $nextIdNumber = $lastId + 1;
                }
                $transaksiServis->id_service = 'TSV' . $nextIdNumber;
            }
        });
    }

    protected $fillable = [
        'id_service',
        'id_laptop',
        'id_teknisi',
        'tanggal_masuk',
        'tanggal_keluar',
        'status_bayar',
        'subtotal_servis',                // Menyimpan total harga semua jasa
        'subtotal_sparepart',             // Menyimpan total harga semua sparepart
        'harga_total_transaksi_servis'    // Menyimpan grand total
    ];

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi', 'id_teknisi');
    }

    public function pelanggan()
    {
        return $this->hasOneThrough(
            Pelanggan::class,
            Laptop::class,
            'id_laptop',      // Foreign key on laptops table
            'id_pelanggan',   // Foreign key on pelanggan table
            'id_laptop',      // Local key on service table
            'id_pelanggan'    // Local key on laptops table
        );
    }

    public function laptop()
    {
        return $this->belongsTo(Laptop::class, 'id_laptop', 'id_laptop');
    }

    /**
     * Relasi untuk detail JASA
     */
    public function detailTransaksiServis()
    {
        return $this->hasMany(DetailTransaksiServis::class, 'id_service', 'id_service');
    }

    /**
     * Relasi untuk detail SPAREPART
     */
    public function detailServisSpareparts()
    {
        return $this->hasMany(DetailTransaksiServisSparepart::class, 'id_service', 'id_service');
    }
}