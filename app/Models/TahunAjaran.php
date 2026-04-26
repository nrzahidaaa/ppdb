<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $fillable = [
        'nama_tahun_ajaran',
        'is_active',
    ];

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public static function aktif()
    {
        return self::where('is_active', true)->first();
    }
}