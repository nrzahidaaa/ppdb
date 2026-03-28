<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiTes extends Model
{
    protected $table = 'nilai_tes';
    protected $primaryKey = 'id_nilai';

    protected $fillable = [
        'id_siswa',
        'bhs_indonesia',
        'matematika',
        'ipa',
        'ips',
        'agama',
        'doa_iftitah',
        'tahiyat_awal',
        'qunut',
        'membaca_al_quran',
        'fatihah_4',
        'surah_pendek',
        'doa',
        'menulis',
        'tanggal_input',
    ];

    protected $casts = [
        'tanggal_input' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_siswa');
    }

    // Hitung total nilai akademik
    public function getTotalAkademikAttribute()
    {
        return $this->ipa + $this->ips + $this->bhs_indonesia + $this->matematika;
    }

    // Hitung total nilai agama
    public function getTotalAgamaAttribute()
    {
        return $this->doa_iftitah + $this->tahiyat_awal + $this->qunut +
               $this->membaca_al_quran + $this->fatihah_4 + $this->doa + $this->menulis;
    }

    // Hitung rata-rata semua nilai
    public function getRataRataAttribute()
    {
        $total = $this->total_akademik + $this->total_agama;
        return round($total / 11, 2);
    }


}