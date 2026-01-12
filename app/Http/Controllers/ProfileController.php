<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function settings()
    {
        return view('profile.settings');
    }

    public function details()
    {
        $user = Auth::user();
        if ($user->role !== 'santri') {
            return redirect()->route('profile.settings');
        }
        return view('profile.details', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

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
        ]);

        $user->santri->update([
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
        ]);

        return back()->with('success', 'Data diri berhasil diperbarui.');
    }
}
