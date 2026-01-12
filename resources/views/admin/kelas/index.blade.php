@extends('layouts.master')
@section('title', 'Data Kelas')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Data Kelas</h2>
            <p style="color: var(--muted); margin: 4px 0 0; font-size: 0.9rem;">Kelola daftar kelas untuk santri.</p>
        </div>
        <div>
            <button onclick="openModal()" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Tambah Kelas
            </button>
        </div>
    </div>

    {{-- Search --}}
    <div style="display: flex; justify-content: space-between; margin-bottom: 20px; gap: 10px; flex-wrap: wrap;">
        <div class="search-box">
            <form action="{{ route('kelas.index') }}" method="GET" style="display: flex; gap: 10px;">
                <input type="text" name="search" class="form-control" placeholder="Cari Nama Kelas..." value="{{ request('search') }}" style="width: 300px;">
                <button type="submit" class="btn btn-secondary">Cari</button>
            </form>
        </div>
    </div>

    {{-- Tables --}}
    @foreach(['MI' => $kelasMI, 'MTs' => $kelasMTs, 'MA' => $kelasMA] as $tingkat => $data)
    <div style="margin-bottom: 30px;">
        <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 10px; color: var(--primary);">Tingkat {{ $tingkat }}</h3>
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <th style="padding: 12px; width: 50px;">No.</th>
                        <th style="padding: 12px;">Nama Kelas</th>
                        <th style="padding: 12px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 12px;">{{ $loop->iteration }}</td>
                        <td style="padding: 12px; font-weight: 600;">{{ $item->nama_kelas }}</td>
                        <td style="padding: 12px; text-align: right;">
                            <a href="{{ route('kelas.edit', $item->id) }}" class="btn-icon" title="Edit" style="color: var(--warning); margin-right: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                            <button type="button" onclick="confirmDelete('{{ $item->id }}')" class="btn-icon" title="Hapus" style="color: var(--danger); background: none; border: none; cursor: pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('kelas.destroy', $item->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 24px; color: var(--muted);">Data kelas {{ $tingkat }} belum tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    @if($kelasLain->count() > 0)
    <div style="margin-bottom: 30px;">
        <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 10px; color: var(--primary);">Lainnya</h3>
        <div style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <th style="padding: 12px; width: 50px;">No.</th>
                        <th style="padding: 12px;">Nama Kelas</th>
                        <th style="padding: 12px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelasLain as $item)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 12px;">{{ $loop->iteration }}</td>
                        <td style="padding: 12px; font-weight: 600;">{{ $item->nama_kelas }}</td>
                        <td style="padding: 12px; text-align: right;">
                            <a href="{{ route('kelas.edit', $item->id) }}" class="btn-icon" title="Edit" style="color: var(--warning); margin-right: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                            <button type="button" onclick="confirmDelete('{{ $item->id }}')" class="btn-icon" title="Hapus" style="color: var(--danger); background: none; border: none; cursor: pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('kelas.destroy', $item->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Modal Tambah Kelas --}}
    <div id="modalTambah" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Kelas</h3>
                <button type="button" onclick="closeModal()" class="close-btn">&times;</button>
            </div>
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 0.9rem;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                openModal();
                            });
                        </script>
                    @endif

                    <div id="input-container">
                        @if(old('nama_kelas'))
                            @foreach(old('nama_kelas') as $index => $value)
                                <div class="input-group" style="margin-bottom: 10px; display: flex; gap: 10px;">
                                    <select name="tingkatan[]" class="form-control" style="width: 100px;" required>
                                        <option value="" disabled {{ old('tingkatan.'.$index) ? '' : 'selected' }}>Pilih Tingkat</option>
                                        <option value="MI" {{ old('tingkatan.'.$index) == 'MI' ? 'selected' : '' }}>MI</option>
                                        <option value="MTs" {{ old('tingkatan.'.$index) == 'MTs' ? 'selected' : '' }}>MTs</option>
                                        <option value="MA" {{ old('tingkatan.'.$index) == 'MA' ? 'selected' : '' }}>MA</option>
                                    </select>
                                    <input type="text" name="nama_kelas[]" class="form-control" placeholder="Nama Kelas (misal: 10 IPA 1)" value="{{ $value }}" required style="flex: 1;">
                                    @if($index > 0)
                                        <button type="button" onclick="removeInput(this)" class="btn btn-danger btn-sm">&times;</button>
                                    @else
                                        <button type="button" class="btn btn-danger btn-sm remove-row" style="display: none;">&times;</button>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="input-group" style="margin-bottom: 10px; display: flex; gap: 10px;">
                                <select name="tingkatan[]" class="form-control" style="width: 100px;" required>
                                    <option value="" disabled selected>Pilih Tingkat</option>
                                    <option value="MI">MI</option>
                                    <option value="MTs">MTs</option>
                                    <option value="MA">MA</option>
                                </select>
                                <input type="text" name="nama_kelas[]" class="form-control" placeholder="Nama Kelas (misal: 10 IPA 1)" required style="flex: 1;">
                                <button type="button" class="btn btn-danger btn-sm remove-row" style="display: none;">&times;</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" onclick="addInput()" class="btn btn-secondary btn-sm" style="margin-top: 5px;">+ Tambah Baris</button>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeModal()" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Semua</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data kelas yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function openModal() {
        document.getElementById('modalTambah').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalTambah').style.display = 'none';
    }

    function addInput() {
        const container = document.getElementById('input-container');
        const div = document.createElement('div');
        div.className = 'input-group';
        div.style.marginBottom = '10px';
        div.style.display = 'flex';
        div.style.gap = '10px';
        
        div.innerHTML = `
            <select name="tingkatan[]" class="form-control" style="width: 100px;" required>
                <option value="" disabled selected>Pilih Tingkat</option>
                <option value="MI">MI</option>
                <option value="MTs">MTs</option>
                <option value="MA">MA</option>
            </select>
            <input type="text" name="nama_kelas[]" class="form-control" placeholder="Nama Kelas" required style="flex: 1;">
            <button type="button" onclick="removeInput(this)" class="btn btn-danger btn-sm">&times;</button>
        `;
        
        container.appendChild(div);
    }

    function removeInput(btn) {
        btn.parentElement.remove();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('modalTambah');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

<style>
    /* Modal Styles */
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
        padding: 24px;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--muted);
    }

    .modal-footer {
        margin-top: 24px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .btn-sm {
        padding: 4px 8px;
        font-size: 0.8rem;
    }
    
    .btn-danger {
        background-color: #ef4444;
        color: white;
        border: 1px solid #dc2626;
    }
    
    .btn-danger:hover {
        background-color: #dc2626;
    }

    .form-control {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 0.9rem;
    }
    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        border: 1px solid transparent;
    }
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    .btn-secondary {
        background: #f1f5f9;
        color: var(--text);
        border-color: var(--border);
    }
    .btn-icon {
        padding: 4px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .btn-icon:hover {
        background: var(--panel-2);
    }
</style>
@endsection
