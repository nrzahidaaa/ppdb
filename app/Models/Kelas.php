<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'jurusan',
        'wali_kelas',
        'kuota',
        'tahun_ajaran',
    ];

    // ===== RELATIONSHIPS =====
    public function siswa()
    {
        return $this->hasMany(Pendaftaran::class, 'id_kelas');
    }

    // ===== ACCESSORS =====
    public function getTerisiAttribute()
    {
        return $this->siswa()->count();
    }

    public function getSisaAttribute()
    {
        return $this->kuota - $this->terisi;
    }

    public function getPersentaseAttribute()
    {
        return $this->kuota > 0
            ? round(($this->terisi / $this->kuota) * 100)
            : 0;
    }
}
