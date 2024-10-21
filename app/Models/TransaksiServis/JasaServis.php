<?php

namespace App\Models\TransaksiServis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JasaServis extends Model
{
    use HasFactory;

    protected $table = 'jasa_servis';
    protected $primaryKey = 'id_jasa';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jasaServis) {
            $latestJasa = static::latest('id_jasa')->first();

            if (!$latestJasa) {
                $nextIdNumber = 1;
            } else {
                $lastId = (int) str_replace('SV', '', $latestJasa->id_jasa);
                $nextIdNumber = $lastId + 1;
            }

            $jasaServis->id_jasa = 'SV' . $nextIdNumber;
        });
    }
    protected $fillable = [
        'jenis_jasa',
        'harga_jasa'
    ];
}
