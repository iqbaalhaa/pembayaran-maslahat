@extends('layouts.master')
@section('title', 'Tarif Mashlahat')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Tarif Mashlahat</h2>
            <p style="color: var(--muted); margin: 4px 0 0; font-size: 0.9rem;">Kelola daftar tarif pembayaran.</p>
        </div>
        <div>
            <button onclick="openModal('modalTambah')" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Tambah Tarif
            </button>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 12px; border-radius: 8px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <div style="display: flex; justify-content: space-between; margin-bottom: 20px; gap: 10px; flex-wrap: wrap;">
        <div class="search-box">
            <form action="{{ route('tarif.index') }}" method="GET" style="display: flex; gap: 10px;">
                <input type="text" name="search" class="form-control" placeholder="Cari Tarif..." value="{{ request('search') }}" style="width: 300px;">
                <button type="submit" class="btn btn-secondary">Cari</button>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div style="overflow-x: auto;">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 12px; width: 50px;">No.</th>
                    <th style="padding: 12px;">Nama Tarif</th>
                    <th style="padding: 12px;">Nominal</th>
                    <th style="padding: 12px;">Keterangan</th>
                    <th style="padding: 12px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tarifs as $item)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 12px;">{{ $loop->iteration }}</td>
                    <td style="padding: 12px; font-weight: 600;">{{ $item->nama_tarif }}</td>
                    <td style="padding: 12px;">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td style="padding: 12px; color: var(--muted);">{{ $item->keterangan ?? '-' }}</td>
                    <td style="padding: 12px; text-align: right;">
                        <button onclick="editTarif({{ $item->id }}, '{{ $item->nama_tarif }}', {{ $item->nominal }}, '{{ $item->keterangan }}')" class="btn-icon" title="Edit" style="color: var(--warning); margin-right: 8px; background: none; border: none; cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>
                        <form action="{{ route('tarif.destroy', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tarif ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon" title="Hapus" style="color: var(--danger); background: none; border: none; cursor: pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 24px; color: var(--muted);">Data tarif belum tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $tarifs->links() }}
    </div>

    {{-- Modal Tambah Tarif --}}
    <div id="modalTambah" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Tarif</h3>
                <button type="button" onclick="closeModal('modalTambah')" class="close-btn">&times;</button>
            </div>
            <form action="{{ route('tarif.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Tarif</label>
                        <input type="text" name="nama_tarif" class="form-control" required placeholder="Contoh: SPP Bulanan">
                    </div>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Nominal (Rp)</label>
                        <input type="text" name="nominal" class="form-control" required placeholder="0" onkeyup="formatRupiah(this)">
                    </div>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('modalTambah')" class="btn btn-outline">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Tarif --}}
    <div id="modalEdit" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Tarif</h3>
                <button type="button" onclick="closeModal('modalEdit')" class="close-btn">&times;</button>
            </div>
            <form id="formEdit" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Tarif</label>
                        <input type="text" name="nama_tarif" id="edit_nama_tarif" class="form-control" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Nominal (Rp)</label>
                        <input type="number" name="nominal" id="edit_nominal" class="form-control" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal('modalEdit')" class="btn btn-outline">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    .modal-content {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .modal-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 {
        margin: 0;
        font-size: 1.1rem;
    }
    .close-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--muted);
    }
    .modal-body {
        padding: 24px;
    }
    .modal-footer {
        padding: 16px 24px;
        background: #f9fafb;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 0.95rem;
        box-sizing: border-box;
    }
    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid transparent;
    }
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    .btn-outline {
        background: white;
        border-color: var(--border);
        color: var(--text);
    }
</style>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function editTarif(id, nama, nominal, keterangan) {
        document.getElementById('edit_nama_tarif').value = nama;
        document.getElementById('edit_nominal').value = nominal;
        document.getElementById('edit_keterangan').value = keterangan || '';
        document.getElementById('formEdit').action = '/admin/tarif/' + id;
        openModal('modalEdit');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            event.target.style.display = 'none';
        }
    }

    function formatRupiah(input) {
        // Hapus karakter selain angka
        var value = input.value.toString().replace(/\D/g, '');
        
        // Format dengan titik setiap 3 digit
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        
        input.value = value;
    }
</script>
@endsection
