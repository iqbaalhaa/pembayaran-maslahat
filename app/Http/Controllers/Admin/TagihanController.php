<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Tarif;
use App\Models\Kelas;
use App\Models\Santri;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = Tagihan::with(['santri.kelas', 'tarif']);

        if ($request->has('search') && $request->search) {
            $query->whereHas('santri', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('bulan') && $request->bulan) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->has('tahun') && $request->tahun) {
            $query->where('tahun', $request->tahun);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('tingkatan') && $request->tingkatan) {
            $query->whereHas('santri.kelas', function($q) use ($request) {
                $q->where('tingkatan', $request->tingkatan);
            });
        }

        $tagihans = $query->latest()->paginate(10)->withQueryString();
        
        // Data for Generate Tagihan Modal
        $tarifs = Tarif::all();
        $kelas = Kelas::all();
        $tingkatans = ['MI', 'MTs', 'MA']; // Hardcoded enum values or fetch distinct
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        return view('admin.tagihan.index', compact('tagihans', 'tarifs', 'kelas', 'tingkatans', 'months'));
    }

    public function create()
    {
        $tarifs = Tarif::all();
        $kelas = Kelas::all();
        return view('admin.tagihan.create', compact('tarifs', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required|numeric',
            'tarif_id' => 'required|exists:tarifs,id',
            'kelas_ids' => 'nullable|array',
            'tingkatans' => 'nullable|array',
        ]);

        $tarif = Tarif::findOrFail($request->tarif_id);
        
        $query = Santri::where('status', 'aktif');
        
        // Filter based on checkbox selection
        // If checkboxes are selected, we filter. If none, we take ALL active santri.
        $hasFilter = ($request->has('kelas_ids') && count($request->kelas_ids) > 0) || 
                     ($request->has('tingkatans') && count($request->tingkatans) > 0);

        if ($hasFilter) {
            $query->where(function($q) use ($request) {
                if ($request->has('kelas_ids') && count($request->kelas_ids) > 0) {
                    $q->orWhereIn('kelas_id', $request->kelas_ids);
                }
                if ($request->has('tingkatans') && count($request->tingkatans) > 0) {
                    $q->orWhereHas('kelas', function($q2) use ($request) {
                        $q2->whereIn('tingkatan', $request->tingkatans);
                    });
                }
            });
        }
        
        $santris = $query->get();

        if ($santris->isEmpty()) {
            return back()->with('error', 'Tidak ada santri aktif ditemukan untuk kriteria tersebut.');
        }

        $count = 0;
        foreach ($santris as $santri) {
            // Check duplicate
            $exists = Tagihan::where('santri_id', $santri->id)
                ->where('tarif_id', $tarif->id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->exists();

            if (!$exists) {
                Tagihan::create([
                    'santri_id' => $santri->id,
                    'tarif_id' => $tarif->id,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'jumlah' => $tarif->nominal,
                    'status' => 'belum_lunas',
                ]);
                $count++;
            }
        }

        return redirect()->route('tagihan.index')->with('success', "$count tagihan berhasil digenerate.");
    }
    
    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();
        return back()->with('success', 'Tagihan berhasil dihapus.');
    }
}
