@extends('layouts.master')
@section('title', 'Edit Akun Santri')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 8px;">Edit Akun Santri</h2>
        <p style="color: var(--muted); margin: 0;">Update informasi akun santri (Nama, NIS, Password).</p>
    </div>

    @if($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('akun-santri.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="nama" style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Santri</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $user->name) }}" required placeholder="Masukkan nama lengkap santri">
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="kelas_id" style="display: block; margin-bottom: 8px; font-weight: 500;">Kelas</label>
            <select name="kelas_id" id="kelas_id" class="form-control" required>
                <option value="">Pilih Kelas</option>
                @foreach($kelas as $item)
                    <option value="{{ $item->id }}" {{ (old('kelas_id') ?? ($user->santri->kelas_id ?? '')) == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="nis" style="display: block; margin-bottom: 8px; font-weight: 500;">NIS / NISN (Username)</label>
            <input type="text" name="nis" id="nis" class="form-control" value="{{ old('nis', $user->username) }}" required placeholder="Masukkan NIS/NISN">
            <small style="color: var(--muted); display: block; margin-top: 4px;">Digunakan sebagai Username Login.</small>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="password" style="display: block; margin-bottom: 8px; font-weight: 500;">Password Baru (Opsional)</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
            <small style="color: var(--muted); display: block; margin-top: 4px;">Minimal 6 karakter.</small>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px;">
            <a href="{{ route('akun-santri.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

<style>
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        border: 1px solid transparent;
    }
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    .btn-primary:disabled {
        background: var(--muted);
        cursor: not-allowed;
    }
    .btn-outline {
        border-color: var(--border);
        background: white;
        color: var(--text);
    }
</style>
@endsection
