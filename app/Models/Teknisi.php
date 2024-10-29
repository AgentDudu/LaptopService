<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teknisi extends Authenticatable
{
    use Notifiable;

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
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * custom identifier (phone number), override method
     */
    public function getAuthIdentifierName()
    {
        return 'nohp_teknisi';
    }
}