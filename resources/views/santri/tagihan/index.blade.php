@extends('layouts.master')
@section('title', 'Tagihan Saya')

@section('content')
<div class="dashboard-header" style="margin-bottom: 32px;">
    <h1 style="font-size: 1.8rem; font-weight: 700; color: var(--text);">Tagihan Saya</h1>
    <p style="color: var(--muted);">Daftar tagihan pembayaran sekolah Anda.</p>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border);">
                    <th style="padding: 12px; width: 50px;">No.</th>
                    <th style="padding: 12px;">Periode</th>
                    <th style="padding: 12px;">Jenis Pembayaran</th>
                    <th style="padding: 12px;">Nominal</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px;">Tanggal Bayar</th>
                    <th style="padding: 12px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihans as $index => $tagihan)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 12px;">{{ $tagihans->firstItem() + $index }}</td>
                    <td style="padding: 12px;">{{ $tagihan->bulan }} {{ $tagihan->tahun }}</td>
                    <td style="padding: 12px;">{{ $tagihan->tarif->nama_tarif }}</td>
                    <td style="padding: 12px;">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</td>
                    <td style="padding: 12px;">
                        @if($tagihan->status == 'lunas')
                            <span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Lunas</span>
                        @elseif($tagihan->status == 'menunggu_konfirmasi')
                            <span style="background: #fff7ed; color: #9a3412; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Menunggu Konfirmasi</span>
                        @else
                            <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Belum Lunas</span>
                        @endif
                    </td>
                    <td style="padding: 12px;">
                        @if($tagihan->status == 'lunas')
                            {{ $tagihan->updated_at->format('d M Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="padding: 12px; text-align: right;">
                        @if($tagihan->status == 'belum_lunas')
                            <a href="{{ route('santri.tagihan.bayar', $tagihan->id) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.85rem;">
                                Bayar
                            </a>
                        @endif

                        @if(in_array($tagihan->status, ['menunggu_konfirmasi', 'lunas']) && $tagihan->bukti_bayar)
                            <a href="{{ asset('assets-file/' . $tagihan->bukti_bayar) }}" target="_blank" style="margin-left: 8px; color: var(--primary); display: inline-flex; align-items: center; justify-content: center;" title="Lihat Bukti Pembayaran">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 24px; color: var(--muted);">Tidak ada data tagihan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $tagihans->links() }}
    </div>
</div>
@endsection
