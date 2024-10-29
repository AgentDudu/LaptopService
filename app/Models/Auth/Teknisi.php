<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\TransaksiServis\Service;
use App\Models\TransaksiSparepart\TransaksiJualSparepart; // Namespace untuk DetailTransaksiSparepart

class Teknisi extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'teknisi';
    protected $primaryKey = 'id_teknisi';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($teknisi) {
            $latestTeknisi = static::latest('id_teknisi')->first();

            if (!$latestTeknisi) {
                $nextIdNumber = 1;
            } else {
                $lastId = (int) str_replace('TEK', '', $latestTeknisi->id_teknisi);
                $nextIdNumber = $lastId + 1;
            }

            $teknisi->id_teknisi = 'TEK' . $nextIdNumber;
        });
    }
    protected $fillable = [
        'nama_teknisi',
        'nohp_teknisi',
        'status',
        'password'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'password' => 'hashed'
    ];

    public function service()
    {
        return $this->hasMany(Service::class, 'id_teknisi');
    }

    public function jualSparepart()
    {
        return $this->hasMany(TransaksiJualSparepart::class, 'id_teknisi');
    }


}
