@extends('layouts.master')
@section('title', 'Bayar Tagihan')

@section('content')
<div class="dashboard-header" style="margin-bottom: 32px;">
    <h1 style="font-size: 1.8rem; font-weight: 700; color: var(--text);">Bayar Tagihan</h1>
    <p style="color: var(--muted);">Upload bukti pembayaran untuk tagihan Anda.</p>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h3 style="margin: 0 0 16px; font-size: 1.1rem; border-bottom: 1px solid var(--border); padding-bottom: 12px;">Detail Tagihan</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 8px;">
            <div style="color: var(--muted);">Periode</div>
            <div style="font-weight: 600; text-align: right;">{{ $tagihan->bulan }} {{ $tagihan->tahun }}</div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 8px;">
            <div style="color: var(--muted);">Jenis Pembayaran</div>
            <div style="font-weight: 600; text-align: right;">{{ $tagihan->tarif->nama_tarif }}</div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 8px;">
            <div style="color: var(--muted);">Nominal</div>
            <div style="font-weight: 600; text-align: right; color: var(--primary); font-size: 1.1rem;">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div style="color: var(--muted);">Status</div>
            <div style="text-align: right;">
                @if($tagihan->status == 'lunas')
                    <span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Lunas</span>
                @elseif($tagihan->status == 'menunggu_konfirmasi')
                    <span style="background: #fff7ed; color: #9a3412; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Menunggu Konfirmasi</span>
                @else
                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Belum Lunas</span>
                @endif
            </div>
        </div>
    </div>

    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid var(--border);">
        <h4 style="margin: 0 0 12px; font-size: 0.95rem;">Informasi Rekening</h4>
        <p style="margin: 0 0 8px; font-size: 0.9rem;">Silakan transfer ke salah satu rekening berikut:</p>
        <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
            <li style="margin-bottom: 4px;"><strong>BSI (Bank Syariah Indonesia)</strong>: 1234-5678-90 a.n Ponpes Darussalam</li>
            <li><strong>BRI</strong>: 0987-6543-21 a.n Yayasan Maslahat</li>
        </ul>
    </div>

    <form action="{{ route('santri.tagihan.process-bayar', $tagihan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label">Upload Bukti Transfer</label>
            <input type="file" name="bukti_bayar" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
            <small style="display: block; margin-top: 6px; color: var(--muted);">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
        </div>

        <div style="display: flex; gap: 12px;">
            <a href="{{ route('santri.tagihan') }}" class="btn btn-secondary" style="flex: 1; text-align: center;">Batal</a>
            <button type="submit" class="btn btn-primary" style="flex: 1;">Kirim Bukti Pembayaran</button>
        </div>
    </form>
</div>
@endsection
