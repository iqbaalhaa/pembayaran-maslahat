<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Tagihan;

class TagihanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $santri = $user->santri;

        if (!$santri) {
            return back()->with('error', 'Data santri tidak ditemukan.');
        }

        $tagihans = Tagihan::with('tarif')
            ->where('santri_id', $santri->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        return view('santri.tagihan.index', compact('tagihans'));
    }

    public function riwayat()
    {
        $user = Auth::user();
        $santri = $user->santri;

        if (!$santri) {
            return back()->with('error', 'Data santri tidak ditemukan.');
        }

        $tagihans = Tagihan::with('tarif')
            ->where('santri_id', $santri->id)
            ->whereIn('status', ['menunggu_konfirmasi', 'lunas'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('santri.riwayat', compact('tagihans'));
    }

    public function bayar($id)
    {
        $user = Auth::user();
        $santri = $user->santri;

        $tagihan = Tagihan::where('santri_id', $santri->id)->findOrFail($id);

        if ($tagihan->status === 'lunas') {
            return back()->with('warning', 'Tagihan ini sudah lunas.');
        }
        
        if ($tagihan->status === 'menunggu_konfirmasi') {
            return back()->with('warning', 'Tagihan ini sedang menunggu konfirmasi admin.');
        }

        return view('santri.tagihan.bayar', compact('tagihan'));
    }

    public function processBayar(Request $request, $id)
    {
        $user = Auth::user();
        $santri = $user->santri;

        $tagihan = Tagihan::where('santri_id', $santri->id)->findOrFail($id);

        if ($tagihan->status === 'lunas') {
            return back()->with('error', 'Tagihan sudah lunas.');
        }

        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('bukti_bayar')) {
            // Delete old file if exists (though unlikely if status is not rejected, but good practice)
            if ($tagihan->bukti_bayar) {
                Storage::disk('public')->delete($tagihan->bukti_bayar);
            }

            $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');
            
            $tagihan->update([
                'bukti_bayar' => $path,
                'tgl_upload' => now(),
                'status' => 'menunggu_konfirmasi',
            ]);

            return redirect()->route('santri.tagihan')->with('success', 'Bukti pembayaran berhasil diupload. Mohon tunggu konfirmasi admin.');
        }

        return back()->with('error', 'Gagal mengupload bukti pembayaran.');
    }

    public function kwitansi($id)
    {
        $user = Auth::user();
        $santri = $user->santri;

        if (!$santri) {
            return back()->with('error', 'Data santri tidak ditemukan.');
        }

        $tagihan = Tagihan::with(['santri.kelas', 'tarif'])
            ->where('santri_id', $santri->id)
            ->where('status', 'lunas')
            ->findOrFail($id);

        $tagihans = collect([$tagihan]);
        $groupedTagihans = $tagihans->groupBy('santri_id');

        return view('admin.pembayaran.print', compact('groupedTagihans'));
    }
}
