<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $kelasMI = Kelas::where('tingkatan', 'MI')
            ->when($search, fn($q) => $q->where('nama_kelas', 'like', "%{$search}%"))
            ->latest()
            ->get();

        $kelasMTs = Kelas::where('tingkatan', 'MTs')
            ->when($search, fn($q) => $q->where('nama_kelas', 'like', "%{$search}%"))
            ->latest()
            ->get();

        $kelasMA = Kelas::where('tingkatan', 'MA')
            ->when($search, fn($q) => $q->where('nama_kelas', 'like', "%{$search}%"))
            ->latest()
            ->get();
            
        $kelasLain = Kelas::whereNull('tingkatan')
            ->when($search, fn($q) => $q->where('nama_kelas', 'like', "%{$search}%"))
            ->latest()
            ->get();

        return view('admin.kelas.index', compact('kelasMI', 'kelasMTs', 'kelasMA', 'kelasLain'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|array|min:1',
            'nama_kelas.*' => 'required|string|max:255|distinct|unique:kelas,nama_kelas',
            'tingkatan' => 'required|array|min:1',
            'tingkatan.*' => 'required|in:MI,MTs,MA',
        ], [
            'nama_kelas.*.required' => 'Nama kelas tidak boleh kosong.',
            'nama_kelas.*.unique' => 'Nama kelas sudah ada.',
            'nama_kelas.*.distinct' => 'Nama kelas duplikat dalam input.',
            'tingkatan.*.required' => 'Tingkatan wajib dipilih.',
            'tingkatan.*.in' => 'Tingkatan tidak valid.',
        ]);

        foreach ($request->nama_kelas as $index => $nama) {
            Kelas::create([
                'nama_kelas' => $nama,
                'tingkatan' => $request->tingkatan[$index]
            ]);
        }

        return redirect()->route('kelas.index')->with('success', count($request->nama_kelas) . ' Data kelas berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        return view('admin.kelas.edit', compact('kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kelas->id,
            'tingkatan' => 'required|in:MI,MTs,MA',
        ]);

        $kelas->update($request->all());

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
    {
        try {
            // Cek apakah kelas sedang digunakan oleh santri
            if ($kelas->santri()->exists()) {
                return redirect()->back()->with('error', 'Kelas tidak dapat dihapus karena masih memiliki santri yang terdaftar.');
            }

            $kelas->delete();

            return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kelas: ' . $e->getMessage());
        }
    }
}
