<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        // $jadwal = \App\Models\Jadwal::orderBy('tanggal_mulai')->get();
        return view('beranda.index', [
            'jadwal' => [],
        ]);
    }
}
