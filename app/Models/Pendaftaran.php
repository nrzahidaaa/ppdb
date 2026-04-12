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
    'nik',
    'tempat_lahir',
    'tanggal_lahir',
    'jenis_kelamin',
    'hobi',
    'cita_cita',
    'anak_ke',
    'jumlah_saudara',
    'status_tinggal',
    'asal_sekolah',
    'nama_sekolah',
    'jenis_sekolah',
    'status_sekolah',
    'npsn_sekolah',
    'pilihan_jurusan',
    'jalur',
    'nilai_rata_rata',
    'nama_orang_tua',
    'no_telp',
    'alamat',
    'desa_kelurahan',
    'kecamatan',
    'kabupaten_kota',
    'kode_pos',
    'no_kk',
    'nama_kepala_keluarga',
    'status_kepemilikan_rumah',
    'nama_ayah',
    'nik_ayah',
    'status_ayah',
    'pendidikan_ayah',
    'pekerjaan_ayah',
    'penghasilan_ayah',
    'no_hp_ayah',
    'nama_ibu',
    'nik_ibu',
    'status_ibu',
    'pendidikan_ibu',
    'pekerjaan_ibu',
    'penghasilan_ibu',
    'no_hp_ibu',
    'nama_wali',
    'nik_wali',
    'status_wali',
    'pendidikan_wali',
    'pekerjaan_wali',
    'penghasilan_wali',
    'no_hp_wali',
    'no_kks',
    'no_pkh',
    'no_kip',
    'status',
    'predikat',
    'id_kelas',
    'berkas_lengkap',
    'nisn_file', 'kartu_keluarga', 'akta_kelahiran', 'foto', 'ijazah',
    'catatan',
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