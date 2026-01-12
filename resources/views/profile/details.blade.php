@extends('layouts.master')
@section('title', 'Data Diri Santri')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 8px;">Data Diri Santri</h2>
        <p style="color: var(--muted); margin: 0;">Lengkapi biodata Anda untuk keperluan administrasi.</p>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 12px; border-radius: 8px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update.details') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="tempat_lahir" style="display: block; margin-bottom: 8px; font-weight: 500;">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $user->santri->tempat_lahir) }}" placeholder="Contoh: Jakarta" required>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="tanggal_lahir" style="display: block; margin-bottom: 8px; font-weight: 500;">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $user->santri->tanggal_lahir) }}" required>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="jenis_kelamin" style="display: block; margin-bottom: 8px; font-weight: 500;">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L" {{ old('jenis_kelamin', $user->santri->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin', $user->santri->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="alamat" style="display: block; margin-bottom: 8px; font-weight: 500;">Alamat Lengkap</label>
            <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $user->santri->alamat) }}</textarea>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px;">
            <button type="submit" class="btn btn-primary">Simpan Data Diri</button>
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
</style>
@endsection
