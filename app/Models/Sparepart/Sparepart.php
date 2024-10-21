<?php

namespace App\Models\Sparepart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $table = 'sparepart';
    protected $primaryKey = 'id_sparepart';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sparepart) {
            $latestSparepart = static::latest('id_sparepart')->first();

            if (!$latestSparepart) {
                $nextIdNumber = 1;
            } else {
                $lastId = (int) str_replace('SP', '', $latestSparepart->id_sparepart);
                $nextIdNumber = $lastId + 1;
            }

            $sparepart->id_sparepart = 'SP' . $nextIdNumber;
        });
    }
    protected $fillable = [
        'jenis_sparepart',
        'merek_sparepart',
        'model_sparepart',
        'harga_sparepart'
    ];
}
