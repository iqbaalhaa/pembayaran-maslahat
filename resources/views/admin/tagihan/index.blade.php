@extends('layouts.master')
@section('title', 'Data Tagihan')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Data Tagihan</h2>
            <p style="color: var(--muted); margin: 4px 0 0; font-size: 0.9rem;">Kelola data tagihan santri.</p>
        </div>
        <div>
            <button onclick="openModal('generateModal')" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Generate Tagihan
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div style="margin-bottom: 20px; background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid var(--border);">
        <form action="{{ route('tagihan.index') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label" style="font-size: 0.85rem; margin-bottom: 5px;">Cari Santri</label>
                <input type="text" name="search" class="form-control" placeholder="Nama / NIS..." value="{{ request('search') }}">
            </div>
            <div style="width: 150px;">
                <label class="form-label" style="font-size: 0.85rem; margin-bottom: 5px;">Bulan</label>
                <select name="bulan" class="form-control">
                    <option value="">Semua</option>
                    @foreach($months as $m)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div style="width: 100px;">
                <label class="form-label" style="font-size: 0.85rem; margin-bottom: 5px;">Tahun</label>
                <input type="number" name="tahun" class="form-control" value="{{ request('tahun') }}" placeholder="Tahun">
            </div>
            <div style="width: 150px;">
                <label class="form-label" style="font-size: 0.85rem; margin-bottom: 5px;">Tingkatan</label>
                <select name="tingkatan" class="form-control">
                    <option value="">Semua</option>
                    @foreach($tingkatans as $t)
                        <option value="{{ $t }}" {{ request('tingkatan') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div style="width: 150px;">
                <label class="form-label" style="font-size: 0.85rem; margin-bottom: 5px;">Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary" style="height: 42px;">Filter</button>
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 12px; width: 50px;">No.</th>
                    <th style="padding: 12px;">Nama Santri</th>
                    <th style="padding: 12px;">Kelas</th>
                    <th style="padding: 12px;">Jenis Tagihan</th>
                    <th style="padding: 12px;">Periode</th>
                    <th style="padding: 12px;">Nominal</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihans as $item)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 12px;">{{ $loop->iteration + $tagihans->firstItem() - 1 }}</td>
                    <td style="padding: 12px; font-weight: 600;">
                        {{ optional($item->santri)->nama ?? '-' }}
                        <div style="font-size: 0.8rem; color: var(--muted); font-weight: normal;">{{ optional($item->santri)->nis ?? '-' }}</div>
                    </td>
                    <td style="padding: 12px;">
                        {{ optional($item->santri)->kelas ?? optional(optional($item->santri)->getRelation('kelas'))->nama_kelas ?? '-' }}
                    </td>
                    <td style="padding: 12px;">{{ optional($item->tarif)->nama_tarif ?? '-' }}</td>
                    <td style="padding: 12px;">{{ $item->bulan }} {{ $item->tahun }}</td>
                    <td style="padding: 12px;">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td style="padding: 12px;">
                        @if($item->status == 'lunas')
                            <span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Lunas</span>
                        @else
                            <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Belum Lunas</span>
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: right;">
                        <button type="button" 
                            data-json="{{ $item->toJson() }}"
                            onclick="showDetail(this)" 
                            class="btn-icon" title="Detail" style="color: var(--primary); background: none; border: none; cursor: pointer; margin-right: 5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                        <button type="button" onclick="confirmDelete('{{ $item->id }}')" class="btn-icon" title="Hapus" style="color: var(--danger); background: none; border: none; cursor: pointer;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                        <form id="delete-form-{{ $item->id }}" action="{{ route('tagihan.destroy', $item->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 24px; color: var(--muted);">Data tagihan belum tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $tagihans->links() }}
    </div>
</div>

<!-- Modal Generate Tagihan -->
<div id="generateModal" class="modal">
    <div class="modal-content" style="width: 750px; max-width: 95%;">
        <div class="modal-header">
            <h3>Generate Tagihan</h3>
            <span onclick="closeModal('generateModal')" class="close-modal">&times;</span>
        </div>
        <form action="{{ route('tagihan.store') }}" method="POST">
            <div class="modal-body">
                @csrf
                <div style="display: grid; grid-template-columns: 250px 1fr; gap: 24px;">
                    <!-- Left Column: Settings -->
                    <div style="border-right: 1px solid var(--border); padding-right: 24px;">
                        <h4 style="margin: 0 0 20px; font-size: 0.95rem; color: var(--text); font-weight: 600;">Pengaturan Tagihan</h4>
                        
                        <div class="form-group">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-control" required>
                                <option value="">Pilih Bulan</option>
                                @foreach($months as $bulan)
                                    <option value="{{ $bulan }}">{{ $bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenis Tarif</label>
                            <select name="tarif_id" class="form-control" required>
                                <option value="">Pilih Tarif</option>
                                @foreach($tarifs as $tarif)
                                    <option value="{{ $tarif->id }}">
                                        {{ $tarif->nama_tarif }} - Rp {{ number_format($tarif->nominal, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Right Column: Target -->
                    <div>
                        <div class="form-group">
                            <h4 style="margin: 0 0 10px; font-size: 0.95rem; color: var(--text); font-weight: 600;">Target Santri</h4>
                            <p style="color: var(--muted); font-size: 0.85rem; margin-bottom: 20px; line-height: 1.5;">
                                Pilih Tingkatan atau Kelas. Jika tidak ada yang dipilih, tagihan akan dibuat untuk <strong style="color: var(--primary);">SEMUA</strong> santri aktif.
                            </p>
                            
                            <div style="margin-bottom: 24px;">
                                <label style="font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); display: block; margin-bottom: 12px;">Berdasarkan Tingkatan</label>
                                <div class="selection-group">
                                    @foreach($tingkatans as $tingkatan)
                                    <label class="selection-card">
                                        <input type="checkbox" name="tingkatans[]" value="{{ $tingkatan }}">
                                        <div class="card-content">
                                            {{ $tingkatan }}
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <label style="font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); display: block; margin: 0;">Berdasarkan Kelas</label>
                                    <input type="text" id="searchKelas" class="search-input-sm" placeholder="Cari kelas..." style="width: 150px; margin: 0;">
                                </div>
                                
                                <div class="kelas-container" style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
                                    @php
                                        // Group kelas by tingkatan manually if needed or just display nicely
                                        $kelasByTingkatan = $kelas->groupBy('tingkatan');
                                    @endphp

                                    @foreach($kelasByTingkatan as $tingkatan => $listKelas)
                                        <div class="kelas-group" data-tingkatan="{{ $tingkatan }}" style="margin-bottom: 20px;">
                                            <div style="font-size: 0.8rem; font-weight: 700; color: var(--text); margin-bottom: 8px; position: sticky; top: 0; background: var(--surface); z-index: 1; padding: 5px 0; border-bottom: 1px solid var(--border);">
                                                {{ $tingkatan }}
                                            </div>
                                            <div class="selection-grid" style="grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 8px;">
                                                @foreach($listKelas as $item)
                                                <label class="selection-card" data-nama="{{ strtolower($item->nama_kelas) }}">
                                                    <input type="checkbox" name="kelas_ids[]" value="{{ $item->id }}">
                                                    <div class="card-content" style="padding: 6px 8px; font-size: 0.8rem;">
                                                        {{ $item->nama_kelas }}
                                                    </div>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('generateModal')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Generate Tagihan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Tagihan -->
<div id="detailModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Detail Tagihan</h3>
            <span onclick="closeModal('detailModal')" class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <div id="detailContent">
                <!-- Content will be filled by JS -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeModal('detailModal')" class="btn btn-primary">Tutup</button>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = "block";
    }

    function closeModal(id) {
        document.getElementById(id).style.display = "none";
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data tagihan yang dihapus tidak dapat dikembalikan!",
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

    function showDetail(element) {
        let item = JSON.parse(element.getAttribute('data-json'));
        const santri = item.santri || {};
        const kelas = santri.kelas || {};
        const tarif = item.tarif || {};
        let content = `
            <table class="table" style="width: 100%;">
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Nama Santri</td>
                    <td style="padding: 8px;">: ${santri.nama || '-'}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">NIS</td>
                    <td style="padding: 8px;">: ${santri.nis || '-'}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Kelas</td>
                    <td style="padding: 8px;">: ${kelas.nama_kelas || '-'}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Jenis Tagihan</td>
                    <td style="padding: 8px;">: ${tarif.nama_tarif || '-'}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Periode</td>
                    <td style="padding: 8px;">: ${item.bulan || '-'} ${item.tahun || '-'}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Nominal</td>
                    <td style="padding: 8px;">: Rp ${new Intl.NumberFormat('id-ID').format(item.jumlah || 0)}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Status</td>
                    <td style="padding: 8px;">: 
                        ${item.status == 'lunas' ? 
                        '<span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Lunas</span>' : 
                        '<span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Belum Lunas</span>'}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: 600;">Dibuat Pada</td>
                    <td style="padding: 8px;">: ${item.created_at ? new Date(item.created_at).toLocaleString('id-ID') : '-'}</td>
                </tr>
            </table>
        `;
        document.getElementById('detailContent').innerHTML = content;
        openModal('detailModal');
    }

    // Search Kelas Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchKelas');
        if(searchInput) {
            searchInput.addEventListener('input', function(e) {
                const keyword = e.target.value.toLowerCase();
                const groups = document.querySelectorAll('.kelas-group');
                
                groups.forEach(group => {
                    const cards = group.querySelectorAll('.selection-card');
                    let hasVisibleCard = false;
                    
                    cards.forEach(card => {
                        const nama = card.getAttribute('data-nama');
                        if (nama.includes(keyword)) {
                            card.style.display = 'flex';
                            hasVisibleCard = true;
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    
                    // Hide group title if no cards visible in that group
                    if(hasVisibleCard) {
                        group.style.display = 'block';
                    } else {
                        group.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection