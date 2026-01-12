@extends('layouts.master')
@section('title', 'Edit Kelas')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Edit Kelas</h2>
        <p style="color: var(--muted); margin: 4px 0 0; font-size: 0.9rem;">Perbarui nama kelas.</p>
    </div>

    <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="tingkatan" style="display: block; margin-bottom: 8px; font-weight: 500;">Tingkatan</label>
            <select name="tingkatan" id="tingkatan" class="form-control @error('tingkatan') is-invalid @enderror" required>
                <option value="" disabled>Pilih Tingkat</option>
                <option value="MI" {{ old('tingkatan', $kelas->tingkatan) == 'MI' ? 'selected' : '' }}>MI</option>
                <option value="MTs" {{ old('tingkatan', $kelas->tingkatan) == 'MTs' ? 'selected' : '' }}>MTs</option>
                <option value="MA" {{ old('tingkatan', $kelas->tingkatan) == 'MA' ? 'selected' : '' }}>MA</option>
            </select>
            @error('tingkatan')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="nama_kelas" style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Kelas</label>
            <input type="text" name="nama_kelas" id="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" placeholder="Contoh: 10 IPA 1" required>
            @error('nama_kelas')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px;">
            <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
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
    .form-control.is-invalid {
        border-color: var(--danger);
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
    .btn-secondary {
        background: #f1f5f9;
        color: var(--text);
        border: 1px solid var(--border);
    }
</style>
@endsection
