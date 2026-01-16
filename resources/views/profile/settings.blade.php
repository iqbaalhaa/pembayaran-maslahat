@extends('layouts.master')
@section('title', 'Pengaturan Akun')

@section('content')
<div class="card" style="max-width: 1100px; margin: 0 auto; padding: 24px 24px 28px; border-radius: 16px;">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 8px;">Pengaturan Akun</h2>
        <p style="color: var(--muted); margin: 0;">Perbarui informasi profil dan password akun Anda.</p>
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

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="settings-grid">
            <div class="settings-card">
                <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 6px;">Informasi Akun</h3>
                <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 18px;">Detail identitas dan kontak akun Anda.</p>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="name" style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="email" style="display: block; margin-bottom: 8px; font-weight: 500;">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                </div>
            </div>

            <div class="settings-card">
                <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 6px;">Keamanan & Password</h3>
                <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 18px;">Atur ulang password untuk menjaga keamanan akun.</p>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="password" style="display: block; margin-bottom: 8px; font-weight: 500;">Password Baru (Opsional)</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengganti">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="password_confirmation" style="display: block; margin-bottom: 8px; font-weight: 500;">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                </div>
            </div>

            @if(auth()->user()->role === 'admin')
            <div class="settings-card settings-card-full">
                <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 6px;">Pengaturan Logo Sistem</h3>
                <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 18px;">Logo akan digunakan di sidebar, halaman login, dan dokumen cetak.</p>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Logo Saat Ini</label>
                    @if($logoUrl)
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="{{ $logoUrl }}" alt="Logo" style="height: 56px; width: auto; border-radius: 10px; border: 1px solid var(--border); background: white; padding: 6px;">
                            <span style="font-size: 0.85rem; color: var(--muted);">Pastikan logo terlihat jelas pada background terang dan gelap.</span>
                        </div>
                    @else
                        <span style="font-size: 0.9rem; color: var(--muted);">Belum ada logo yang diupload. Sistem menggunakan logo default.</span>
                    @endif
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="logo" style="display: block; margin-bottom: 8px; font-weight: 500;">Upload Logo Baru</label>
                    <input type="file" name="logo" id="logo" class="form-control">
                    <small style="display: block; margin-top: 6px; font-size: 0.8rem; color: var(--muted);">
                        Format: JPG, PNG, SVG, WEBP — Maks. 2MB. Disarankan logo dengan background transparan.
                    </small>
                </div>
            </div>
            @endif
        </div>

        <div class="settings-actions">
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
    .settings-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 24px;
        margin-top: 20px;
    }
    .settings-card {
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 18px 18px 20px;
        background: #ffffff;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
    }
    .settings-card-full {
        grid-column: 1 / -1;
    }
    .settings-actions {
        margin-top: 24px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    @media (min-width: 900px) {
        .settings-grid {
            grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
        }
    }
</style>
@endsection
