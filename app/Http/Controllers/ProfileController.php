<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Models\Setting;

class ProfileController extends Controller
{
    public function settings()
    {
        $logoPath = Setting::getValue('app_logo');
        $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;
        return view('profile.settings', compact('logoUrl'));
    }

    public function details()
    {
        $user = Auth::user();
        if ($user->role !== 'santri') {
            return redirect()->route('profile.settings');
        }
        $kelas = \App\Models\Kelas::orderBy('nama_kelas')->get();
        $tingkatan = $kelas->pluck('tingkatan')->unique()->values();
        return view('santri.profile.details', compact('user', 'kelas', 'tingkatan'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($request->hasFile('logo') && $user->role === 'admin') {
            $oldPath = Setting::getValue('app_logo');
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('logo')->store('logo', 'public');

            Setting::updateOrCreate(
                ['key' => 'app_logo'],
                ['value' => $path]
            );
        }

        return back()->with('success', 'Pengaturan akun berhasil diperbarui.');
    }

    public function updateDetails(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'santri' || !$user->santri) {
            return back()->withErrors(['error' => 'Anda tidak memiliki akses untuk mengubah data ini.']);
        }

        $request->validate([
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $user->santri->update([
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'kelas_id' => $request->kelas_id,
        ]);

        return back()->with('success', 'Data diri berhasil diperbarui.');
    }

    public function kelas(): JsonResponse
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'santri' || !$user->santri) {
            return response()->json(['message' => 'Data santri tidak ditemukan'], 404);
        }

        $santri = $user->santri;

        $kelasRelation = $santri->kelas()->first();

        $kelasName = $kelasRelation
            ? $kelasRelation->nama_kelas
            : $santri->kelas;

        return response()->json([
            'kelas' => $kelasName,
        ]);
    }
}
