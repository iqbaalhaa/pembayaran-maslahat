<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function create()
    {
        $santris = Santri::with('kelas')->where('status', 'aktif')->orderBy('nama')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.pembayaran.create', compact('santris', 'kelas'));
    }

    public function getPending()
    {
        $tagihans = Tagihan::with(['santri.kelas', 'tarif'])
            ->where('status', 'menunggu_konfirmasi')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($tagihans);
    }

    public function getByClass($kelasId)
    {
        $tagihans = Tagihan::with(['santri', 'tarif'])
            ->whereHas('santri', function($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            })
            ->whereIn('status', ['belum_lunas', 'menunggu_konfirmasi'])
            ->orderBy('santri_id')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        return response()->json($tagihans);
    }

    public function getTagihan($id)
    {
        $tagihans = Tagihan::with(['tarif'])
            ->where('santri_id', $id)
            ->whereIn('status', ['belum_lunas', 'menunggu_konfirmasi'])
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        return response()->json($tagihans);
    }

    public function getRiwayat($id)
    {
        $tagihans = Tagihan::with(['tarif'])
            ->where('santri_id', $id)
            ->where('status', 'lunas')
            ->orderBy('updated_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($tagihans);
    }

    public function getHistoryByClass($kelasId)
    {
        $tagihans = Tagihan::with(['santri', 'tarif'])
            ->whereHas('santri', function($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            })
            ->where('status', 'lunas')
            ->orderBy('santri_id')
            ->orderBy('updated_at', 'desc')
            ->limit(200)
            ->get();

        return response()->json($tagihans);
    }

    public function cetak()
    {
        $santris = Santri::with('kelas')->where('status', 'aktif')->orderBy('nama')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.pembayaran.cetak', compact('santris', 'kelas'));
    }

    public function print(Request $request)
    {
        $request->validate([
            'tagihan_ids' => 'required|array',
            'tagihan_ids.*' => 'exists:tagihans,id',
        ]);

        $tagihans = Tagihan::with(['santri.kelas', 'tarif'])
            ->whereIn('id', $request->tagihan_ids)
            ->get();

        if ($tagihans->isEmpty()) {
            return back()->with('error', 'Tidak ada data yang dipilih.');
        }

        $groupedTagihans = $tagihans->groupBy('santri_id');

        return view('admin.pembayaran.print', compact('groupedTagihans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tagihan_ids' => 'required|array',
            'tagihan_ids.*' => 'exists:tagihans,id',
        ]);

        try {
            DB::beginTransaction();

            $tagihans = Tagihan::whereIn('id', $request->tagihan_ids)->get();
            $count = 0;

            foreach ($tagihans as $tagihan) {
                if ($tagihan->status !== 'lunas') {
                    $tagihan->status = 'lunas';
                    // We might want to add a 'paid_at' timestamp if we modify the migration later
                    // For now just status
                    $tagihan->save();
                    $count++;
                }
            }

            DB::commit();

            return redirect()->route('pembayaran.create')
                ->with('success', "Berhasil memproses pembayaran untuk $count tagihan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }
}
