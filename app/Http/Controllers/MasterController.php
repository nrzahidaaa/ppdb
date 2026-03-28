<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MasterController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        $kelas = Kelas::withCount('siswa')->latest()->get();
        return view('master.index', compact('users', 'kelas'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('master.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);
        $data = ['name' => $request->name, 'email' => $request->email];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->route('master.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('master.index')->with('success', 'User berhasil dihapus!');
    }

    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            // 'jurusan'    => 'required|in:MIPA,IPS,Bahasa',
            'kapasitas'  => 'required|integer|min:1|max:50',
        ]);
        Kelas::create($request->only('nama_kelas', 'jurusan', 'wali_kelas', 'kouta'));
        return redirect()->route('master.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function updateKelas(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            // 'jurusan'    => 'required|in:MIPA,IPS,Bahasa',
            'kuota' => 'required|integer|min:1|max:50',
        ]);
        $kelas->update($request->only('nama_kelas', 'jurusan', 'wali_kelas', 'kouta'));
        return redirect()->route('master.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroyKelas($id)
    {
        Kelas::findOrFail($id)->delete();
        return redirect()->route('master.index')->with('success', 'Kelas berhasil dihapus!');
    }
}