<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NilaiTesController;
use App\Http\Controllers\SettingsController;

// ===== PUBLIC ROUTES =====
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/daftar', [PendaftaranController::class, 'formPublik'])->name('pendaftaran.publik');
Route::post('/daftar', [PendaftaranController::class, 'storePublik'])->name('pendaftaran.storePublik');

Route::get('/pengumuman', [PendaftaranController::class, 'pengumuman'])->name('pengumuman');
Route::post('/pengumuman', [PendaftaranController::class, 'cekPengumuman'])->name('pengumuman.cek');

Route::get('/daftar/sukses', function() {
    return view('pendaftaran.sukses');
})->name('pendaftaran.sukses');

// ===== AUTH ROUTES (Breeze) =====
require __DIR__.'/auth.php';

// ===== PROTECTED ROUTES =====
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pendaftaran
    Route::resource('pendaftaran', PendaftaranController::class);
    Route::post('/pendaftaran/import', [PendaftaranController::class, 'importExcel'])->name('pendaftaran.import');
    Route::patch('/pendaftaran/{id}/status', [PendaftaranController::class, 'updateStatus'])->name('pendaftaran.updateStatus');

    // Data Master
    Route::resource('master', MasterController::class);
    // Master User
Route::post('/master/user', [MasterController::class, 'storeUser'])->name('master.user.store');
Route::put('/master/user/{id}', [MasterController::class, 'updateUser'])->name('master.user.update');
Route::delete('/master/user/{id}', [MasterController::class, 'destroyUser'])->name('master.user.destroy');

// Master Kelas
Route::post('/master/kelas', [MasterController::class, 'storeKelas'])->name('master.kelas.store');
Route::put('/master/kelas/{id}', [MasterController::class, 'updateKelas'])->name('master.kelas.update');
Route::delete('/master/kelas/{id}', [MasterController::class, 'destroyKelas'])->name('master.kelas.destroy');

    // Data Siswa
    Route::resource('siswa', SiswaController::class);

    // Proses Klasifikasi
Route::get('/klasifikasi', [KlasifikasiController::class, 'index'])->name('klasifikasi.index');
Route::post('/klasifikasi/proses', [KlasifikasiController::class, 'proses'])->name('klasifikasi.proses');

    // Pembagian Kelas
    Route::resource('kelas', KelasController::class);
    // Route::post('/kelas/{kelas}/assign-siswa', [KelasController::class, 'assignSiswa'])->name('kelas.assign-siswa');
    Route::get('/klasifikasi/pembagian', [KlasifikasiController::class, 'pembagianKelas'])->name('klasifikasi.pembagian');
Route::post('/klasifikasi/pembagian', [KlasifikasiController::class, 'prosesKelas'])->name('klasifikasi.prosesKelas');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

// PDF
Route::get('/laporan/pdf/pendaftar', [LaporanController::class, 'pdfPendaftar'])->name('laporan.pdf.pendaftar');
Route::get('/laporan/pdf/klasifikasi', [LaporanController::class, 'pdfKlasifikasi'])->name('laporan.pdf.klasifikasi');
Route::get('/laporan/pdf/pembagian', [LaporanController::class, 'pdfPembagian'])->name('laporan.pdf.pembagian');
Route::get('/laporan/pdf/nilai', [LaporanController::class, 'pdfNilai'])->name('laporan.pdf.nilai');

// Excel
Route::get('/laporan/excel/pendaftar', [LaporanController::class, 'excelPendaftar'])->name('laporan.excel.pendaftar');
Route::get('/laporan/excel/klasifikasi', [LaporanController::class, 'excelKlasifikasi'])->name('laporan.excel.klasifikasi');
Route::get('/laporan/excel/pembagian', [LaporanController::class, 'excelPembagian'])->name('laporan.excel.pembagian');
Route::get('/laporan/excel/nilai', [LaporanController::class, 'excelNilai'])->name('laporan.excel.nilai');

    // Pengaturan
    Route::get('/settings',   [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings',   [SettingsController::class, 'update'])->name('settings.update');

    Route::get('/nilai-tes', [NilaiTesController::class, 'index'])->name('nilai-tes.index');
Route::post('/nilai-tes/import', [NilaiTesController::class, 'importExcel'])->name('nilai-tes.import');
Route::delete('/nilai-tes/{id}', [NilaiTesController::class, 'destroy'])->name('nilai-tes.destroy');
});
