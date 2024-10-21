<?php

namespace App\Models\TransaksiServis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pelanggan\Pelanggan;
use App\Models\Teknisi;
use App\Models\TransaksiServis\DetailTransaksiServis;
use App\Models\Laptop\Laptop;

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
            $latestTransaksiServis = static::latest('id_service')->first();

            if (!$latestTransaksiServis) {
                $nextIdNumber = 1;
            } else {
                $lastId = (int) str_replace('TSV', '', $latestTransaksiServis->id_service);
                $nextIdNumber = $lastId + 1;
            }

            $transaksiServis->id_jasa = 'TSV' . $nextIdNumber;
        });
    }
    protected $fillable = [
        'id_laptop',
        'id_teknisi',
        'tanggal_masuk',
        'tanggal_keluar',
        'status_bayar',
        'harga_total_transaksi_servis'
    ];
    protected $dates = [
        'tanggal_masuk',
    ];
    protected $casts = [
        'id_service' => 'integer',
        'tanggal_masuk' => 'datetime',
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
            'id_laptop',
            'id_pelanggan',
            'id_laptop',
            'id_pelanggan'
        );
    }

    public function laptop()
    {
        return $this->belongsTo(Laptop::class, 'id_laptop', 'id_laptop');
    }
    
    public function detailTransaksiServis()
    {
        return $this->hasMany(DetailTransaksiServis::class, 'id_service', 'id_service');
    }
}
