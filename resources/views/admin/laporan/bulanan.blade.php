@extends('layouts.master')
@section('title', 'Laporan Rekap Bulanan')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Laporan Rekap Bulanan</h2>
            <p style="color: var(--muted); margin: 4px 0 0; font-size: 0.9rem;">Ringkasan pembayaran yang sudah lunas per bulan.</p>
        </div>
    </div>

    <div style="margin-bottom: 20px; background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid var(--border);">
        <form action="{{ route('laporan.bulanan') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;">
            <div style="width: 160px;">
                <label class="form-label" style="font-size: 0.85rem; margin-bottom: 5px;">Bulan</label>
                <select name="bulan" class="form-control">
                    @foreach($months as $m)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div style="width: 110px;">
                <label class="form-label" style="font-size: 0.85rem; margin-bottom: 5px;">Tahun</label>
                <input type="number" name="tahun" class="form-control" value="{{ $selectedYear }}">
            </div>
            <div style="width: 150px;">
                <label class="form-label" style="font-size: 0.85rem; margin-bottom: 5px;">Tingkatan</label>
                <select name="tingkatan" class="form-control">
                    <option value="">Semua</option>
                    @foreach($tingkatans as $t)
                        <option value="{{ $t }}" {{ $selectedTingkatan == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div style="width: 180px;">
                <label class="form-label" style="font-size: 0.85rem; margin-bottom: 5px;">Kelas</label>
                <select name="kelas_id" class="form-control">
                    <option value="">Semua</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} ({{ $k->tingkatan }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <button type="submit" class="btn btn-secondary" style="height: 42px;">Terapkan</button>
                <a href="{{ route('laporan.bulanan.export', request()->query()) }}" class="btn btn-primary" style="height: 42px; display: inline-flex; align-items: center; gap: 6px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
        </svg>
        Excel
    </a>
    <a href="{{ route('laporan.bulanan.export-pdf', request()->query()) }}" class="btn btn-danger" style="height: 42px; display: inline-flex; align-items: center; background: #dc2626; border-color: #dc2626; color: white; gap: 6px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
        </svg>
        PDF
    </a>
            </div>
        </form>
    </div>

    <div style="margin-bottom: 16px; padding: 12px 16px; border-radius: 8px; background: #ecfdf5; border: 1px solid #bbf7d0; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <div>
            <div style="font-size: 0.85rem; color: var(--muted);">Periode</div>
            <div style="font-weight: 600;">{{ $selectedMonth }} {{ $selectedYear }}</div>
        </div>
        <div>
            <div style="font-size: 0.85rem; color: var(--muted);">Total Transaksi Lunas</div>
            <div style="font-weight: 600;">{{ $totalTransaksi }} transaksi</div>
        </div>
        <div>
            <div style="font-size: 0.85rem; color: var(--muted);">Total Nominal</div>
            <div style="font-weight: 700; color: #16a34a;">Rp {{ number_format($totalJumlah, 0, ',', '.') }}</div>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 12px; width: 50px;">No.</th>
                    <th style="padding: 12px;">Nama Santri</th>
                    <th style="padding: 12px;">Kelas</th>
                    <th style="padding: 12px;">Tingkatan</th>
                    <th style="padding: 12px;">Jenis Tagihan</th>
                    <th style="padding: 12px;">Periode</th>
                    <th style="padding: 12px;">Nominal</th>
                    <th style="padding: 12px;">Dibayar Pada</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihans as $item)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 12px;">{{ $loop->iteration }}</td>
                    <td style="padding: 12px; font-weight: 600;">
                        {{ $item->santri->nama }}
                        <div style="font-size: 0.8rem; color: var(--muted); font-weight: normal;">{{ $item->santri->nis }}</div>
                    </td>
                    <td style="padding: 12px;">{{ $item->santri->kelas->nama_kelas ?? '-' }}</td>
                    <td style="padding: 12px;">{{ $item->santri->kelas->tingkatan ?? '-' }}</td>
                    <td style="padding: 12px;">{{ $item->tarif->nama_tarif }}</td>
                    <td style="padding: 12px;">{{ $item->bulan }} {{ $item->tahun }}</td>
                    <td style="padding: 12px;">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td style="padding: 12px;">{{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 24px; color: var(--muted);">Belum ada pembayaran lunas pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
