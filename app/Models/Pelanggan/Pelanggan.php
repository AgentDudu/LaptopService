<?php

namespace App\Models\Pelanggan;

use App\Models\Laptop\Laptop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pelanggan) {
            $latestPelanggan = static::latest('id_pelanggan')->first();

            if (!$latestPelanggan) {
                $nextIdNumber = 1;
            } else {
                $lastId = (int) str_replace('PL', '', $latestPelanggan->id_pelanggan);
                $nextIdNumber = $lastId + 1;
            }

            $pelanggan->id_pelanggan = 'PL' . $nextIdNumber;
        });
    }
    protected $fillable = [
        'nama_pelanggan',
        'nohp_pelanggan'
    ];
    public function laptop()
    {
        return $this->hasMany(Laptop::class);
    }
}
