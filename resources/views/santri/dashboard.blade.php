@extends('layouts.master')
@section('title', 'Dashboard Santri')

@section('content')
<div class="dashboard-header" style="margin-bottom: 32px;">
    <h1 style="font-size: 1.8rem; font-weight: 700; color: var(--text);">Dashboard</h1>
    <p style="color: var(--muted);">Selamat datang di panel santri Pembayaran Maslahat.</p>
</div>

<!-- Welcome Card -->
<div class="card" style="margin-bottom: 24px; border-left: 5px solid var(--primary);">
    <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
        <div class="avatar-placeholder" style="width: 64px; height: 64px; background: var(--panel-2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.5rem; font-weight: 700; flex-shrink: 0;">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div>
            <h2 style="font-size: 1.25rem; margin: 0 0 4px;">Ahlan wa Sahlan, {{ auth()->user()->name }}!</h2>
            <p style="margin: 0; color: var(--muted);">
                NIS: {{ auth()->user()->username }} 
                @if(auth()->user()->santri && auth()->user()->santri->kelas)
                    | Kelas: {{ auth()->user()->santri->kelas }}
                @endif
                | Status: <span style="background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Aktif</span>
            </p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <!-- Tagihan Card -->
    <div class="card">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
            <div style="width: 40px; height: 40px; background: #fee2e2; color: #ef4444; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <span style="color: var(--muted); font-weight: 500;">Tagihan Belum Lunas</span>
        </div>
        <div style="font-size: 1.5rem; font-weight: 700;">Rp 0</div>
        <div style="font-size: 0.875rem; color: var(--muted); margin-top: 4px;">Tidak ada tagihan aktif</div>
    </div>

    <!-- Pembayaran Card -->
    <div class="card">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
             <div style="width: 40px; height: 40px; background: #dcfce7; color: #16a34a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <span style="color: var(--muted); font-weight: 500;">Total Pembayaran</span>
        </div>
        <div style="font-size: 1.5rem; font-weight: 700;">Rp 0</div>
        <div style="font-size: 0.875rem; color: var(--muted); margin-top: 4px;">Tahun Ajaran ini</div>
    </div>
</div>

<!-- Profile Completion Alert -->
@php
    $santri = auth()->user()->santri;
    $isProfileComplete = $santri && $santri->tempat_lahir && $santri->tanggal_lahir && $santri->jenis_kelamin && $santri->alamat;
@endphp

@if(!$isProfileComplete)
<div class="card" style="background: #eff6ff; border: 1px solid #bfdbfe;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="flex: 1;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px; color: #2563eb;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
                <h3 style="font-size: 1.1rem; color: #1e40af; margin: 0; font-weight: 600;">Lengkapi Data Diri Anda</h3>
            </div>
            <p style="color: #1e3a8a; margin: 0; font-size: 0.95rem; padding-left: 36px;">Data diri yang lengkap diperlukan untuk keperluan administrasi akademik dan kesiswaan.</p>
        </div>
        <a href="{{ route('profile.details') }}" class="btn btn-primary" style="background: #2563eb; border: none; padding: 10px 20px; border-radius: 8px; color: white; text-decoration: none; font-weight: 500; white-space: nowrap;">Lengkapi Sekarang</a>
    </div>
</div>
@endif

@endsection