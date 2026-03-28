<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftarans';

    protected $fillable = [
        'nomor_pendaftaran',
        'nama',
        // 'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        // 'jenis_kelamin',
        'asal_sekolah',
        // 'pilihan_jurusan',
        'jalur',
        // 'nilai_rata_rata',
        'nama_orang_tua',
        // 'no_telp',
        'alamat',
        'status',
        // 'predikat',
        // 'id_kelas',
        // 'berkas_lengkap',
        // 'catatan',
        'kelas_id',
    ];

    protected $casts = [
        'tanggal_lahir'  => 'date',
        'berkas_lengkap' => 'boolean',
    ];

    public function nilaiTes()
    {
        return $this->hasOne(NilaiTes::class, 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}