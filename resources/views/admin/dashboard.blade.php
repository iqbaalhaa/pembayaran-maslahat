@extends('layouts.master')
@section('title', 'Dashboard Admin')

@section('content')
<div class="dashboard-header" style="margin-bottom: 32px;">
    <h1 style="font-size: 1.8rem; font-weight: 700; color: var(--text);">Dashboard Admin</h1>
    <p style="color: var(--muted);">Selamat datang di panel administrasi Pembayaran Maslahat.</p>
</div>

<!-- Welcome Card -->
<div class="card" style="margin-bottom: 32px; border-left: 5px solid var(--primary);">
    <div style="display: flex; align-items: center; gap: 20px;">
        <div class="avatar-placeholder" style="width: 64px; height: 64px; background: var(--panel-2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.5rem; font-weight: 700;">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div>
            <h2 style="font-size: 1.25rem; margin: 0 0 4px;">Selamat Datang, {{ auth()->user()->name }}!</h2>
            <p style="margin: 0; color: var(--muted);">Anda login sebagai Administrator. Kelola data santri dan pembayaran dengan mudah.</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 32px;">
    <!-- Total Santri -->
    <div class="card">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
            <div style="width: 40px; height: 40px; background: #eff6ff; color: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <span style="color: var(--muted); font-weight: 500;">Total Santri</span>
        </div>
        <div style="font-size: 1.5rem; font-weight: 700;">{{ $totalSantri }}</div>
        <div style="font-size: 0.875rem; color: var(--muted); margin-top: 4px;">Data santri terdaftar</div>
    </div>

    <!-- Total Akun -->
    <div class="card">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
            <div style="width: 40px; height: 40px; background: #f0f9ff; color: #0ea5e9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </div>
            <span style="color: var(--muted); font-weight: 500;">Total Akun User</span>
        </div>
        <div style="font-size: 1.5rem; font-weight: 700;">{{ $totalAkun }}</div>
        <div style="font-size: 0.875rem; color: var(--muted); margin-top: 4px;">Admin & Santri</div>
    </div>

    <!-- Pembayaran (Placeholder) -->
    <div class="card">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
            <div style="width: 40px; height: 40px; background: #dcfce7; color: #16a34a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <span style="color: var(--muted); font-weight: 500;">Pembayaran Masuk</span>
        </div>
        <div style="font-size: 1.5rem; font-weight: 700;">Rp 0</div>
        <div style="font-size: 0.875rem; color: var(--muted); margin-top: 4px;">Bulan ini</div>
    </div>
    
     <!-- Tagihan (Placeholder) -->
    <div class="card">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
            <div style="width: 40px; height: 40px; background: #fee2e2; color: #ef4444; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            <span style="color: var(--muted); font-weight: 500;">Tagihan Aktif</span>
        </div>
        <div style="font-size: 1.5rem; font-weight: 700;">0</div>
        <div style="font-size: 0.875rem; color: var(--muted); margin-top: 4px;">Menunggu pembayaran</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 16px;">Aksi Cepat</h3>
    <div style="display: flex; gap: 16px; flex-wrap: wrap;">
        <a href="{{ route('santri.create') }}" class="btn btn-primary" style="background: var(--primary); color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5" style="width: 20px; height: 20px;">
              <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
            </svg>
            Tambah Santri
        </a>
        <a href="{{ route('akun-santri.index') }}" class="btn btn-outline" style="border: 1px solid var(--border); color: var(--text); text-decoration: none; padding: 10px 20px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5" style="width: 20px; height: 20px;">
              <path fill-rule="evenodd" d="M1 11.27c0-.246.033-.492.099-.73l1.523-5.521A2.75 2.75 0 0 1 5.273 3h9.454a2.75 2.75 0 0 1 2.651 2.019l1.523 5.52c.066.239.099.485.099.732V15a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3.73Zm3.068-5.852.92 3.333a.75.75 0 0 1-.724.95h-.75a.75.75 0 0 1-.724-.95l.92-3.333a1.25 1.25 0 0 1 1.208-.917h.35ZM7.5 11.25a.75.75 0 0 1 .75-.75h3.5a.75.75 0 0 1 0 1.5h-3.5a.75.75 0 0 1-.75-.75Zm-1.875.75a.75.75 0 0 0 0 1.5h-1.5a.75.75 0 0 0 0-1.5h1.5Zm9.375 0a.75.75 0 0 0 0 1.5h-1.5a.75.75 0 0 0 0-1.5h1.5ZM13.348 5.417a1.25 1.25 0 0 1 1.208.917l.92 3.333a.75.75 0 0 1-.724.95h-.75a.75.75 0 0 1-.724-.95l.92-3.333a1.25 1.25 0 0 1-1.208-.917h.35Z" clip-rule="evenodd" />
            </svg>
            Kelola Akun Santri
        </a>
    </div>
</div>
@endsection