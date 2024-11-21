<?php

namespace App\Models\TransaksiSparepart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pelanggan\Pelanggan; // Pastikan namespace benar untuk model Pelanggan
use App\Models\TransaksiSparepart\DetailTransaksiSparepart; // Namespace untuk DetailTransaksiSparepart
use App\Models\Auth\Teknisi; // Namespace untuk DetailTransaksiSparepart
use Illuminate\Support\Str;

class TransaksiJualSparepart extends Model
{
    use HasFactory;

    protected $table = 'jual_sparepart';
    protected $primaryKey = 'id_transaksi_sparepart';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksiSparepart) {
            // Get the latest ID and generate the next one
            $latestTransaksiSP = static::latest('id_transaksi_sparepart')->value('id_transaksi_sparepart');

            if (!$latestTransaksiSP) {
                $nextIdNumber = 1; 
            } else {
                $lastId = (int) substr($latestTransaksiSP, 3); // Extract numeric part of ID
                $nextIdNumber = $lastId + 1;
            }

            // Format the ID with leading zeros (e.g., TSP001)
            $transaksiSparepart->id_transaksi_sparepart = 'TSP' . str_pad($nextIdNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    protected $fillable = [
        'id_pelanggan',
        'id_teknisi',
        'tanggal_jual',
        'harga_total_transaksi_sparepart'
    ];

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }


    public function detail_transaksi_sparepart()
    {
        return $this->hasMany(DetailTransaksiSparepart::class, 'id_transaksi_sparepart', 'id_transaksi_sparepart');
    }
}
