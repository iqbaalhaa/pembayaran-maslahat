<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Imports\SantriImport;
use App\Exports\SantriExport;
use Maatwebsite\Excel\Facades\Excel;

class SantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Santri::with('kelas');

        // Search Filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhereHas('kelas', function($q) use ($search) {
                      $q->where('nama_kelas', 'like', "%{$search}%");
                  });
            });
        }

        // Pagination
        $santris = $query->latest()->paginate(10)->withQueryString();
        $kelasMap = Kelas::get()->mapWithKeys(function($k){
            return [strtolower(trim($k->nama_kelas)) => $k->tingkatan];
        })->toArray();

        return view('admin.santri.index', compact('santris', 'kelasMap'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new SantriImport, $request->file('file'));

        return back()->with('success', 'Data Santri berhasil diimport!');
    }

    public function export()
    {
        return Excel::download(new SantriExport, 'data_santri.xlsx');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.santri.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:santri,nis',
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'wali_santri' => 'required|string',
            'no_hp_wali' => 'nullable|string',
            'status' => 'required|in:aktif,lulus,keluar',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Populate kelas string for backward compatibility
        $kelas = Kelas::find($request->kelas_id);
        $data['kelas'] = $kelas->nama_kelas;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('santri-photos', 'public');
        }

        Santri::create($data);

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $santri = Santri::findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.santri.edit', compact('santri', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $santri = Santri::findOrFail($id);

        $request->validate([
            'nis' => 'required|unique:santri,nis,' . $santri->id,
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'wali_santri' => 'required|string',
            'no_hp_wali' => 'nullable|string',
            'status' => 'required|in:aktif,lulus,keluar',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Update nama kelas juga untuk kompatibilitas
        $kelas = Kelas::find($request->kelas_id);
        $data['kelas'] = $kelas->nama_kelas;

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($santri->foto && file_exists(public_path('assets-file/' . $santri->foto))) {
                unlink(public_path('assets-file/' . $santri->foto));
            }
            
            $file = $request->file('foto');
            $filename = 'santri_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets-file/santri-photos'), $filename);
            $data['foto'] = 'santri-photos/' . $filename;
        }

        $santri->update($data);

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $santri = Santri::findOrFail($id);
        
        if ($santri->foto && file_exists(public_path('assets-file/' . $santri->foto))) {
            unlink(public_path('assets-file/' . $santri->foto));
        }

        $santri->delete();

        return back()->with('success', 'Data santri berhasil dihapus.');
    }
}
