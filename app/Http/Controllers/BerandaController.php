<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\TahunAjaranHelper;

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
