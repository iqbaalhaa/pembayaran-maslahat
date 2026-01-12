<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarif;

class TarifController extends Controller
{
    public function index(Request $request)
    {
        $query = Tarif::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_tarif', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
        }

        $tarifs = $query->latest()->paginate(10)->withQueryString();

        return view('admin.tarif.index', compact('tarifs'));
    }

    public function store(Request $request)
    {
        // Remove dots/separators from nominal
        $request->merge([
            'nominal' => str_replace('.', '', $request->nominal),
        ]);

        $request->validate([
            'nama_tarif' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        Tarif::create($request->all());

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // Remove dots/separators from nominal
        $request->merge([
            'nominal' => str_replace('.', '', $request->nominal),
        ]);

        $request->validate([
            'nama_tarif' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $tarif = Tarif::findOrFail($id);
        $tarif->update($request->all());

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tarif = Tarif::findOrFail($id);
        $tarif->delete();

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil dihapus.');
    }
}
