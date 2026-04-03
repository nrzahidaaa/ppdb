<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftarans';


protected $fillable = [
    'nomor_pendaftaran',
    'nama',
    'nisn',
    'tempat_lahir',
    'tanggal_lahir',
    'jenis_kelamin',
    'asal_sekolah',
    'jalur',
    'nama_orang_tua',
    'no_telp',
    'alamat',
    'status',
    'berkas_lengkap',
    'nisn_file',
    'kartu_keluarga',
    'akta_kelahiran',
    'foto',
    'ijazah',
    'catatan_revisi',
    'predikat',
    'total_nilai',
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