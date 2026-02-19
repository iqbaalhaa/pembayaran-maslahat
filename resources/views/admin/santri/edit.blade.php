@extends('layouts.master')
@section('title', 'Edit Santri')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 8px;">Edit Data Santri</h2>
        <p style="color: var(--muted); margin: 0;">Update informasi data santri.</p>
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

    <form action="{{ route('santri.update', $santri->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="nis" style="display: block; margin-bottom: 8px; font-weight: 500;">NIS</label>
                <input type="text" name="nis" id="nis" class="form-control" value="{{ old('nis', $santri->nis) }}" required>
            </div>

            <div class="form-group">
                <label for="nama" style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $santri->nama) }}" required>
            </div>

            <div class="form-group">
                <label for="kelas_id" style="display: block; margin-bottom: 8px; font-weight: 500;">Kelas</label>
                <select name="kelas_id" id="kelas_id" class="form-control" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $item)
                        <option value="{{ $item->id }}" {{ old('kelas_id', $santri->kelas_id) == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status" style="display: block; margin-bottom: 8px; font-weight: 500;">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="aktif" {{ old('status', $santri->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="lulus" {{ old('status', $santri->status) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="keluar" {{ old('status', $santri->status) == 'keluar' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>

            <div class="form-group">
                <label for="wali_santri" style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Wali</label>
                <input type="text" name="wali_santri" id="wali_santri" class="form-control" value="{{ old('wali_santri', $santri->wali_santri) }}" required>
            </div>

            <div class="form-group">
                <label for="no_hp_wali" style="display: block; margin-bottom: 8px; font-weight: 500;">No HP Wali</label>
                <input type="text" name="no_hp_wali" id="no_hp_wali" class="form-control" value="{{ old('no_hp_wali', $santri->no_hp_wali) }}">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 32px;">
            <label for="foto" style="display: block; margin-bottom: 8px; font-weight: 500;">Foto Santri</label>
            @if($santri->foto)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('assets-file/' . $santri->foto) }}" alt="Foto Santri" style="max-height: 100px; border-radius: 8px;">
                </div>
            @endif
            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
            <p style="color: var(--muted); font-size: 0.85rem; margin-top: 4px;">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG. Maksimal 2MB.</p>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('santri.index') }}" class="btn btn-outline">Batal</a>
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
    .btn-outline {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text);
    }
    .btn-outline:hover {
        background: var(--bg-hover);
    }
</style>
@endsection
