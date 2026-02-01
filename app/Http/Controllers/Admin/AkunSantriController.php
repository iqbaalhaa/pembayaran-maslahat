<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Santri;
use Illuminate\Support\Facades\Hash;
use App\Imports\AkunSantriImport;
use App\Exports\AkunSantriTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class AkunSantriController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'santri')->with(['santri', 'santri.kelas']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        return view('admin.akun-santri.index', compact('users'));
    }

    public function create()
    {
        $kelas = \App\Models\Kelas::all();
        return view('admin.akun-santri.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:santri,nis|unique:users,username',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        // Create User
        $user = User::create([
            'name' => $request->nama,
            'username' => $request->nis, // Username is NISN
            'email' => $request->nis . '@santri.com', // Dummy email
            'password' => Hash::make($request->nis), // Default password is NISN
            'role' => 'santri',
        ]);

        // Create Santri Linked to User
        $kelas = \App\Models\Kelas::find($request->kelas_id);
        Santri::create([
            'nama' => $request->nama,
            'nis' => $request->nis,
            'user_id' => $user->id,
            'status' => 'aktif',
            'kelas' => $kelas ? $kelas->nama_kelas : null,
            'kelas_id' => $request->kelas_id,
        ]);

        return redirect()->route('akun-santri.index')->with('success', 'Akun santri berhasil dibuat. Password default adalah NISN.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new AkunSantriImport, $request->file('file'));
            return back()->with('success', 'Data Santri dan Akun berhasil diimport! Password default adalah NIS.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new AkunSantriTemplateExport, 'template_akun_santri.xlsx');
    }

    public function edit($id)
    {
        $user = User::with('santri')->findOrFail($id);
        $kelas = \App\Models\Kelas::all();
        return view('admin.akun-santri.edit', compact('user', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:users,username,' . $user->id . '|unique:santri,nis,' . ($user->santri ? $user->santri->id : 'NULL'),
            'kelas_id' => 'required|exists:kelas,id',
            'password' => 'nullable|string|min:6',
        ]);

        $userData = [
            'name' => $request->nama,
            'username' => $request->nis,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        if ($user->santri) {
            $kelas = \App\Models\Kelas::find($request->kelas_id);
            $user->santri->update([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'kelas' => $kelas->nama_kelas,
                'kelas_id' => $request->kelas_id,
            ]);
        }

        return redirect()->route('akun-santri.index')->with('success', 'Data akun santri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Unlink Santri first
        if ($user->santri) {
            $user->santri->update(['user_id' => null]);
        }

        $user->delete();

        return back()->with('success', 'Akun santri berhasil dihapus.');
    }
}
