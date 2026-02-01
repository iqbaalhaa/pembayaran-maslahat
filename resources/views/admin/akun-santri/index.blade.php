@extends('layouts.master')
@section('title', 'Akun Santri')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Akun Santri</h2>
            <p style="color: var(--muted); margin: 4px 0 0; font-size: 0.9rem;">Kelola akun login untuk santri.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="document.getElementById('importModal').style.display='flex'" class="btn btn-outline">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Import Excel
            </button>
            <a href="{{ route('akun-santri.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Buat Akun Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search Form --}}
    <div style="margin-bottom: 20px;">
        <form action="{{ route('akun-santri.index') }}" method="GET" style="display: flex; gap: 10px; max-width: 400px;">
            <input type="text" name="search" class="form-control" placeholder="Cari Nama atau NIS..." value="{{ request('search') }}" style="flex: 1;">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 12px;">NIS / Username</th>
                    <th style="padding: 12px;">Nama Santri</th>
                    <th style="padding: 12px;">Kelas</th>
                    <th style="padding: 12px;">Email (Opsional)</th>
                    <th style="padding: 12px;">Dibuat Pada</th>
                    <th style="padding: 12px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 12px; font-family: monospace; font-weight: 600;">{{ $user->username }}</td>
                    <td style="padding: 12px;">{{ $user->name }}</td>
                    <td style="padding: 12px;">{{ optional(optional($user->santri)->kelas)->nama_kelas ?? '-' }}</td>
                    <td style="padding: 12px; color: var(--muted);">{{ $user->email }}</td>
                    <td style="padding: 12px; color: var(--muted);">{{ $user->created_at->format('d M Y') }}</td>
                    <td style="padding: 12px; text-align: right;">
                        <a href="{{ route('akun-santri.edit', $user->id) }}" style="color: var(--info); margin-right: 12px; display: inline-block;" title="Edit">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('akun-santri.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus akun ini? Santri tidak akan bisa login lagi.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 0;" title="Hapus Akun">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 24px; text-align: center; color: var(--muted);">
                        Belum ada akun santri.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000;">
    <div style="background: white; padding: 24px; border-radius: 12px; width: 100%; max-width: 400px;">
        <h3 style="margin: 0 0 16px; font-size: 1.25rem;">Import Akun Santri</h3>
        <p style="margin-bottom: 16px; color: var(--muted); font-size: 0.9rem;">
            Upload file Excel (.xlsx) dengan kolom wajib: <br>
            <code>nama, nis</code>
        </p>

        <div style="margin-bottom: 20px;">
            <a href="{{ route('akun-santri.template') }}" style="color: var(--primary); text-decoration: none; font-size: 0.9rem; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Download Template Excel
            </a>
        </div>
        
        <form action="{{ route('akun-santri.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 20px;">
                <input type="file" name="file" class="form-control" required accept=".xlsx, .xls, .csv">
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('importModal').style.display='none'" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

<style>
    .btn {
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid transparent;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    .btn-primary:hover {
        background: var(--primary-2);
    }
    .btn-outline {
        border-color: var(--border);
        background: white;
        color: var(--text);
    }
    .btn-outline:hover {
        background: #f9fafb;
    }
    .btn-secondary {
        background: var(--panel-2);
        color: var(--primary);
        border: 1px solid var(--border);
    }
    .btn-secondary:hover {
        background: var(--border);
    }
    .form-control {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.9rem;
        outline: none;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }
</style>
@endsection