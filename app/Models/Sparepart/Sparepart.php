<?php

namespace App\Models\Sparepart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransaksiSparepart\TransaksiJualSparepart; // Namespace untuk DetailTransaksiSparepart
use Illuminate\Support\Str;

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
            $latestSparepart = static::pluck('id_sparepart')->toArray();
            if (!$latestSparepart) {
                $nextIdNumber = 1;
            } else {
                $lastId = max(array_map(function($idSparepart){
                    return (int)Str::replaceFirst('SP', '', $idSparepart);
                }, $latestSparepart));
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

    
    public function TransaksiJualSparepart()
    {
        return $this->hasMany(TransaksiJualSparepart::class, 'id_sparepart');
    }
    
}
