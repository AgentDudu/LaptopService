<?php

namespace App\Models\TransaksiServis;
use App\Models\Pelanggan\Pelanggan;
use App\Models\Teknisi;
use App\Models\TransaksiServis\DetailTransaksiServis;
use App\Models\TransaksiServis\JasaServis;
use App\Models\Laptop\Laptop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'service';
    protected $primaryKey = 'id_service';
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
        'tanggal_keluar', 
    ];
    protected $casts = [
        'id_service' => 'integer',
        'tanggal_masuk' => 'datetime',
        'tanggal_keluar' => 'datetime',
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
