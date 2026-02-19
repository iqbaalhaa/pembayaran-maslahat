@extends('layouts.master')
@section('title', 'Data Santri')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Data Santri</h2>
            <p style="color: var(--muted); margin: 4px 0 0; font-size: 0.9rem;">Kelola data santri, import/export, dan pencarian.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            {{-- Tombol Import --}}
            <button onclick="openModal('importModal')" class="btn btn-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Import
            </button>

            {{-- Tombol Export --}}
            <a href="{{ route('santri.export') }}" class="btn btn-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5-5 5 5M12 15V3"/>
                </svg>
                Export
            </a>

            {{-- Tombol Tambah --}}
            <a href="{{ route('santri.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Tambah Santri
            </a>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div style="margin-bottom: 24px;">
        <form action="{{ route('santri.index') }}" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" class="form-control" placeholder="Cari Nama / NIS / Kelas..." value="{{ request('search') }}" style="width: 300px;">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>

    {{-- Table --}}
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th width="50">No.</th>
                    <th>Foto</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Wali Santri</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($santris as $santri)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($santri->foto)
                            <img src="{{ asset('assets-file/' . $santri->foto) }}" alt="Foto" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border);">
                        @else
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--panel-2); display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: 600; font-size: 0.9rem; border: 1px solid var(--border);">
                                {{ substr($santri->nama, 0, 1) }}
                            </div>
                        @endif
                    </td>
                    <td style="font-weight: 600;">{{ $santri->nis }}</td>
                    <td style="font-weight: 600;">{{ $santri->nama }}</td>
                    <td>
                        <span class="badge badge-muted">
                            {{ ($santri->relationLoaded('kelas') ? optional($santri->getRelation('kelas'))->nama_kelas : null) ?? $santri->kelas ?? '-' }}
                        </span>
                    </td>
                    <td>
                        {{ $santri->wali_santri ?? '-' }}
                        @if($santri->no_hp_wali)
                            <div style="font-size: 0.8rem; color: var(--muted); margin-top: 2px;">{{ $santri->no_hp_wali }}</div>
                        @endif
                    </td>
                    <td>
                        @if($santri->status == 'aktif')
                            <span class="badge badge-primary">Aktif</span>
                        @else
                            <span class="badge badge-muted">{{ ucfirst($santri->status) }}</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('santri.edit', $santri->id) }}" class="btn-icon" title="Edit">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('santri.destroy', $santri->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon danger" title="Hapus">
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
                    <td colspan="8" style="padding: 24px; text-align: center; color: var(--muted);">
                        Belum ada data santri.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="margin-top: 20px;">
        {{ $santris->links() }}
    </div>
</div>

<!-- Modal Import -->
<div id="importModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Import Data Santri</h3>
            <span onclick="closeModal('importModal')" class="close-modal">&times;</span>
        </div>
        <form action="{{ route('santri.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">File Excel (.xlsx, .xls)</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx, .xls" required>
                    <p style="margin-top: 8px; font-size: 0.85rem; color: var(--muted);">
                        Pastikan format file sesuai dengan template.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('importModal')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = "block";
    }

    function closeModal(id) {
        document.getElementById(id).style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    }
</script>
@endsection
