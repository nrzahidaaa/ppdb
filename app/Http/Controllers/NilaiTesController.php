<?php

namespace App\Http\Controllers;

use App\Models\NilaiTes;
use App\Imports\NilaiTesImport;
use Illuminate\Http\Request;

class NilaiTesController extends Controller
{
    public function index()
    {
        $nilaiTes = NilaiTes::with('siswa')->paginate(15);
        return view('nilai_tes.index', compact('nilaiTes'));
    }

    public function importExcel(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
$file = $request->file('file')->store('temp', 'local');
$file = storage_path('app/' . $file);

        $file = $request->file('file')->getPathname();

        $import = new NilaiTesImport();
        $import->import($file);

        return redirect()->route('nilai-tes.index')
                         ->with('success', 'Data nilai berhasil diimport!');
    }

    public function destroy($id)
    {
        NilaiTes::findOrFail($id)->delete();
        return redirect()->route('nilai-tes.index')
                         ->with('success', 'Data nilai berhasil dihapus.');
    }
}